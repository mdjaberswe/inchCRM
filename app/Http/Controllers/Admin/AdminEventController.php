<?php

namespace App\Http\Controllers\Admin;

use App\Models\Event;
use App\Models\Staff;
use App\Models\Reminder;
use App\Models\Activity;
use App\Models\AllowedStaff;
use App\Models\EventAttendee;
use Illuminate\Http\Request;
use App\Library\DatatablesManager;
use App\Http\Controllers\Admin\AdminBaseController;

class AdminEventController extends AdminBaseController
{
	public function __construct()
	{
		parent::__construct();

		$this->middleware('admin:advanced.calendar.view', ['only' => ['index']]);
		$this->middleware('admin:advanced.calendar.create_event', ['only' => ['store']]);
		$this->middleware('admin:advanced.calendar.edit_event', ['only' => ['edit', 'update']]);
		$this->middleware('admin:advanced.calendar.delete_event', ['only' => ['destroy']]);
	}



	public function index()
	{
		$page = ['title' => 'Calendar', 'item' => 'Event', 'modal_bulk_update' => false, 'modal_bulk_delete' => false, 'modal_title_link' => true, 'modal_footer_delete' => true];
		return view('admin.event.index', compact('page'));
	}



	public function eventData()
	{
		$events = Event::get(['id', 'name', 'priority', 'start_date', 'end_date'])->toArray();
		return response()->json($events);
	}



	public function store(Request $request)
	{
		if($request->ajax()) :
			$status = true;
			$errors = null;		
			$notification = null;	
			$data = $request->all();			
			$validation = Event::validate($data);

			$render_event = null;
			$start_date = ampm_to_sql_datetime($request->start_date);
			$end_date = ampm_to_sql_datetime($request->end_date);

			if($validation->passes()) :
				$event = new Event;
				$event->name = $request->name;
				$event->event_owner = $request->event_owner;
				$event->start_date = $start_date;
				$event->end_date = $end_date;
				$event->location = null_if_empty($request->location);
				$event->description = null_if_empty($request->description);
				$event->priority = null_if_empty($request->priority);
				$event->access = $request->access;
				$event->recurring = isset($request->repeat) ? 1 : 0;

				if($request->related !== '') :
					$linked_id = $request->related . '_id';
					$event->linked_id = $request->$linked_id;
					$event->linked_type = $request->related;
				endif;	

				$event->save();

				Activity::create(['linked_id' => $event->id, 'linked_type' => 'event']);

				if($request->access == 'private') :
					if(isset($request->staffs) && count($request->staffs) > 0) :
						foreach($request->staffs as $staff_id) :
								$staff = Staff::find($staff_id);

								if(!is_null($staff)) :
									$allowed_staff = new AllowedStaff;
									$allowed_staff->staff_id = $staff_id;
									$allowed_staff->linked_id = $event->id;
									$allowed_staff->linked_type = 'event';
									$allowed_staff->can_edit = isset($request->can_write) ? 1 : 0;
									$allowed_staff->can_delete = isset($request->can_delete) ? 1 : 0;
									$allowed_staff->save();
								endif;
						endforeach;	
					endif;
				endif;

				$reminder = new Reminder;
				$reminder->reminder_to = $request->event_owner;
				$reminder->reminder_before = $request->reminder_time;
				$reminder->reminder_before_type = $request->reminder_time_unit;
				$reminder->reminder_date = before_date($start_date, $request->reminder_time, $request->reminder_time_unit);
				$reminder->description = 'Event: ' . $request->name . ' will start at ' . $request->start_date;
				$reminder->linked_id = $event->id;
				$reminder->linked_type = 'event';
				$reminder->save();

				$types = ['lead', 'contact', 'staff'];
				if(isset($request->attendees) && count($request->attendees) > 0) :
					foreach($request->attendees as $attendee) :
						$divider = strpos($attendee, '-');
						$type = substr($attendee, 0, $divider);
						$id = substr($attendee, $divider+1);

						if(in_array($type, $types)) :
							$find = \DB::table($type.'s')->find($id);

							if(!is_null($find)) :
								$event_attendee = new EventAttendee;
								$event_attendee->event_id = $event->id;
								$event_attendee->linked_id = $id;
								$event_attendee->linked_type = $type;
								$event_attendee->save();
							endif;	
						endif;
					endforeach;	
				endif;

				$render_event = json_encode(['id' => $event->id, 'title' => $request->name, 'start' => $start_date, 'end' => $end_date, 'color' => $event->color]);
			
				$notification = notification_log('event_created', 'event', $event->id, 'staff', $request->event_owner);
			else :
				$status = false;
				$errors = $validation->getMessageBag()->toArray();
			endif;

			return response()->json(['status' => $status, 'errors' => $errors, 'renderEvent' => $render_event, 'notification' => $notification]);
		endif;
	}



	public function show(Event $event)
	{
		echo $event->name;
	}



	public function edit(Request $request, Event $event)
	{
		if($request->ajax()) :
			$status = true;
			$info = null;
			$html = null;

			if(isset($event) && isset($request->id)) :
				if($event->id == $request->id) :
					$info = $event->toArray();

					$info['attendees[]'] = $event->attendees_list;
					$info['start_date'] = $event->start_date->format('Y-m-d h:i A');
					$info['end_date'] = $event->end_date->format('Y-m-d h:i A');
					$info['repeat'] = $info['recurring'];
					$info['public'] = $info['access'];
					$info['reminder_time'] = non_property_checker($event->reminder, 'reminder_before');
					$info['reminder_time_unit'] = non_property_checker($event->reminder, 'reminder_before_type');

					$info['show'] = [];
					$info['hide'] = [];
					if($info['recurring'] == false) :
						$info['hide'][] = 'repeatevery';
					endif;	

					if(is_null($info['linked_type'])) :
						$info['hide'][] = 'related';
					else :
						$info['related'] = $info['linked_type'];
						$related_field = $info['linked_type'] . '_id';						
						$info[$related_field] = $info['linked_id'];	
						$info['show'][] = $related_field;
					endif;

					$info['modal_title_link'] = ['href' => route('admin.event.show', $event->id), 'title' => str_limit($info['name'], 70, '.')];
					$info['modal_footer_delete'] = ['action' => route('admin.event.destroy', $event->id), 'id' => $event->id];

					$info = (object)$info;

					if(isset($request->html)) :
						$html = view('admin.event.partials.form', ['form' => 'edit'])->render();
					endif;	
				else :
					$status = false;
				endif;
			else :
				$status = false;
			endif;

			return response()->json(['status' => $status, 'info' => $info, 'html' => $html]);
		endif;

		return redirect()->route('admin.event.index');
	}



	public function update(Request $request, Event $event)
	{
		if($request->ajax()) :
			$status = true;
			$errors = null;
			$data = $request->all();

			$update_event = null;
			$start_date = ampm_to_sql_datetime($request->start_date);
			$end_date = ampm_to_sql_datetime($request->end_date);

			if(isset($event) && isset($request->id) && $event->id == $request->id) :
				$validation = Event::validate($data);
				if($validation->passes()) :
					if($event->auth_can_change_owner) :
						$event->event_owner = null_if_empty($request->event_owner);
					endif;

					$event->name = $request->name;
					$event->start_date = $start_date;
					$event->end_date = $end_date;
					$event->location = null_if_empty($request->location);
					$event->description = null_if_empty($request->description);
					$event->priority = null_if_empty($request->priority);
					$event->access = $request->access;
					$event->recurring = isset($request->repeat) ? 1 : 0;

					if($request->related == '') :
						$event->linked_id = null;
						$event->linked_type = null;
					else :	
						$linked_id = $request->related . '_id';
						$event->linked_id = $request->$linked_id;
						$event->linked_type = $request->related;
					endif;	

					$event->update();

					if($request->access != 'private') :
						$event->allowedstaffs()->forceDelete();
					endif;

					$reminder = $event->reminder;
					if(isset($reminder)) :
						$reminder->reminder_to = $request->event_owner;
						$reminder->reminder_before = $request->reminder_time;
						$reminder->reminder_before_type = $request->reminder_time_unit;
						$reminder->reminder_date = before_date($start_date, $request->reminder_time, $request->reminder_time_unit);
						$reminder->description = 'Event: ' . $request->name . ' will start at ' . $request->start_date;
						$reminder->update();
					else :
						$reminder = new Reminder;
						$reminder->reminder_to = $request->event_owner;
						$reminder->reminder_before = $request->reminder_time;
						$reminder->reminder_before_type = $request->reminder_time_unit;
						$reminder->reminder_date = before_date($start_date, $request->reminder_time, $request->reminder_time_unit);
						$reminder->description = 'Event: ' . $request->name . ' will start at ' . $request->start_date;
						$reminder->linked_id = $event->id;
						$reminder->linked_type = 'event';
						$reminder->save();
					endif;					

					$event->attendees()->delete();

					$types = ['lead', 'contact', 'staff'];
					if(isset($request->attendees) && count($request->attendees) > 0) :
						foreach($request->attendees as $attendee) :
							$divider = strpos($attendee, '-');
							$type = substr($attendee, 0, $divider);
							$id = substr($attendee, $divider+1);

							if(in_array($type, $types)) :
								$find = \DB::table($type.'s')->find($id);

								if(!is_null($find)) :
									$event_attendee = new EventAttendee;
									$event_attendee->event_id = $event->id;
									$event_attendee->linked_id = $id;
									$event_attendee->linked_type = $type;
									$event_attendee->save();
								endif;	
							endif;
						endforeach;	
					endif;

					$update_event = json_encode(['id' => $event->id, 'title' => $request->name, 'start' => $start_date, 'end' => $end_date, 'color' => $event->color]);
				else :
					$status = false;
					$errors = $validation->getMessageBag()->toArray();
				endif;
			else :
				$status = false;
			endif;

			return response()->json(['status' => $status, 'errors' => $errors, 'updateEvent' => $update_event, 'saveId' => $request->id]);
		endif;
	}



	public function updatePosition(Request $request)
	{
		if($request->ajax()) :
			$status = false;
			$errors = null;

			$event = Event::find($request->id);

			if(isset($event)) :
				$status = true;
				$start_date = str_replace('T', ' ', $request->start);
				$end_date = str_replace('T', ' ', $request->end);
				$event->start_date = $start_date;
				$event->end_date = $end_date;
				$event->save();
			endif;	

			return response()->json(['status' => $status, 'errors' => $errors]);
		endif;	
	}



	public function destroy(Request $request, Event $event)
	{
		if($request->ajax()) :
			$status = true;
			$event_id = null;

			if($event->id != $request->id) :
				$status = false;
			endif;

			if($status == true) :
				$event_id = $event->id;
				$event->activity->delete();
				$event->delete();
			endif;	
			
			return response()->json(['status' => $status, 'eventId' => $event_id]);
		endif;	
	}



	public function connectedEventData(Request $request, $module_name, $module_id)
	{
		if($request->ajax()) :
			$module = morph_to_model($module_name)::find($module_id);
			if(isset($module)) :
				$events = $module->events()->latest('id')->get();
				return DatatablesManager::connectedEventData($events, $request);
			endif;
			
			return null;	
		endif;
	}



	public function eventAttendeeData(Request $request, Event $event)
	{
		if($request->ajax()) :
			return Event::eventAttendeeData($request, $event);
		endif;
	}
}