<?php

namespace App\Models;

use App\Models\BaseModel;
use App\Models\Traits\OwnerTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class Event extends BaseModel
{
	use SoftDeletes;
	use RevisionableTrait;
	use OwnerTrait;

	protected $table = 'events';
	protected $fillable = ['event_owner', 'linked_id', 'linked_type', 'name', 'start_date', 'end_date', 'location', 'description', 'priority', 'access', 'recurring'];
	protected $appends = ['title', 'start', 'end', 'color', 'period'];
	protected $dates = ['deleted_at', 'start_date', 'end_date'];
	protected $revisionCreationsEnabled = true;

	public static function validate($data)
	{
		$data['start_date'] = ampm_to_sql_datetime($data['start_date']);
		$data['end_date'] = ampm_to_sql_datetime($data['end_date']);	
		$related_types = "lead,contact,account,project,campaign,deal,estimate,invoice";
		$related_array = explode(',', $related_types);

		$rules = ["name"			=> "required|max:200",
				  "location"		=> "max:200",
				  "event_owner"		=> "required|exists:users,linked_id,linked_type,staff,status,1,deleted_at,NULL",
				  "start_date"		=> "required|date",
				  "end_date"		=> "required|date|after:start_date",
				  "related"			=> "in:$related_types",
				  "reminder_time"	=> "required|numeric",
				  "reminder_time_unit"	=> "required|in:minute,hour,day,week",
				  "priority"		=> "in:high,highest,low,lowest,normal",				  
				  "description"		=> "max:65535",
				  "access"			=> "required|in:private,public,public_rwd"];

		if(in_array($data['related'], $related_array)) :
			$field = $data['related'] . '_id';
			$table = $data['related'] . 's';
			$rules[$field] = "required|exists:$table,id,deleted_at,NULL";
		endif;			  

		if(isset($data['repeat'])) :
			$rules["repeat_interval"] = "required|integer";
			$rules["repeat_type"] = "required|in:day,week,month,year";
			$rules["repeat_closing_date"] = "date";
		endif;	  

		return \Validator::make($data, $rules);
	}

	public function setPermission()
	{
		return 'advanced.calendar';
	}

	public function authCan($action)
	{
		$action = $action != 'view' ? $action . '_event' : $action;
		$permission = $this->permission . '.' . $action;
		$can_permission = 'can_' . $action;
		$owner = $this->identifier . '_owner';		
		$is_auth_permit = permit($permission);
		$access = ($action == 'view') ? 'public' : 'public_rwd';

		if(!$is_auth_permit) : return false; endif;
		if(auth_staff()->admin) : return true; endif;

		if(($this->access == $access) && $is_auth_permit) : return true; endif;

		if(($this->$owner == auth_staff()->id) && $is_auth_permit) : return true; endif;

		$is_creator = (non_property_checker($this->createdBy(), 'linked_id') == auth_staff()->id);
		if($is_creator && $is_auth_permit) : return true; endif;

		$is_auth_allowed = in_array(auth_staff()->id, $this->allowedstaffs->pluck('staff_id')->toArray()) ? $this->allowedstaffs()->whereStaff_id(auth_staff()->id)->first()->$can_permission : false;
		if($is_auth_allowed && $is_auth_permit) : return true; endif;

		return false;
	}

	public static function getAttendeeHtml($attendee)
	{
		$route = $attendee->linked_type == 'staff' ? 'admin.user.show' : 'admin.' . $attendee->linked_type . '.show';
		$html = "<a href='" . route($route, $attendee->linked_id) . "' class='link-with-img' data-toggle='tooltip' data-placement='top' title='" . $attendee->linked->name . "'>" . 
					"<img src='" . $attendee->linked->avatar . "'>" . 
				"</a>";

		return $html;		
	}

	public static function getRestAttendees($attendees, $start_key)
	{
		$html = '';

		foreach($attendees as $key => $attendee) :
			if($key >= $start_key) :
				$html .= $attendee->linked->name . '<br>';
			endif;	
		endforeach;	

		return $html;
	}

	public static function getAttendeeTableFormat()
	{
		$json_columns = table_json_columns(['name', 'phone', 'email', 'type' ]);
		$table = ['json_columns' => $json_columns, 'thead' => ['NAME', 'PHONE', 'EMAIL', 'TYPE'], 'checkbox' => false, 'action' => false];
		$table['filter_input']['type'] = ['type' => 'dropdown', 'no_search' => true, 'options' => ['all' => 'All Attendees', 'contact' => 'Contacts', 'lead' => 'Leads', 'staff' => 'Users']];
		
		return $table;
	}

	public static function eventAttendeeData($request, $event)
	{
		return \Datatables::of($event->attendees)
				->addColumn('name', function($attendee)
				{
					return $attendee->linked->profile_html;
				})
				->addColumn('phone', function($attendee)
				{
					return $attendee->linked->phone;
				})
				->addColumn('email', function($attendee)
				{
					return $attendee->linked->email;
				})
				->addColumn('type', function($attendee)
				{
					return $attendee->display_type;
				})
				->filter(function($instance) use ($request)
				{
	            	$instance->collection = $instance->collection->filter(function ($row) use ($request)
	            	{
	            		$status = true;

	            		$search_val = $request->search['value'];
	            		if($request->has('search') && $search_val != '') :
	            			$name = str_contains($row->linked->name, $search_val) ? true : false;
	            			$email = str_contains($row->linked->email, $search_val) ? true : false;
	            			$phone = str_contains($row->linked->phone, $search_val) ? true : false;
	            			$type = str_contains($row->display_type, $search_val) ? true : false;

	            			if(!$name && !$type && !$email && !$phone) :
	            				$status = false;
	            			endif;
	            		endif;

	            		if($request->has('type') && $request->type != 'all' && $request->type != $row->linked_type) :
	            			$status = false;
	            		endif;

	            	    return $status;
	            	});	            	
	            })
				->make(true);
	}

	/*
	|--------------------------------------------------------------------------
	| ACCESSOR
	|--------------------------------------------------------------------------
	*/
	public function getTitleAttribute()
	{
		return $this->attributes['name'];
	}

	public function getNameHtmlAttribute($show_related = false)
	{
		$related_name = '';
		$show_related = isset($show_related) ? $show_related : false;
		if(!is_null($this->linked_type) && $show_related) :
			$related_name = "<br><span class='shadow'><span class='capitalize'>" . $this->linked_type . "</span> - " .  $this->linked->name . "</span>";
		endif;

		$priority_html = '';
		$name_length = 60;
		if($this->priority_html !== '') :
			$priority_html = "<span class='m-left-10'>" . $this->priority_html . "</span>";
			$name_length = 48;
		endif;	

		$tooltip = '';
		if(strlen($this->name) > $name_length) :
			$tooltip = "data-toggle='tooltip' data-placement='top' title='" . $this->name . "'";
		endif;
			
		return "<a $tooltip>" . str_limit($this->name, $name_length) . $priority_html . "</a>" . $related_name;
	}

	public function getActivityNameHtmlAttribute()
	{
		return $this->name_icon_html;
	}

	public function getActivityFromAttribute()
	{
		return $this->readableDateHtml('start_date');
	}

	public function getPriorityHtmlAttribute()
	{
		$priority = $this->priority;
		$priority_html = '';

		switch($priority) :
			case 'low' :
				$priority_html = "<span class='btn btn-primary blur status'>Low</span>";
			break;

			case 'lowest' :
				$priority_html = "<span class='btn btn-primary light status'>Lowest</span>";
			break;

			case 'normal' :
				$priority_html = "<span class='btn btn-warning light status'>Normal</span>";
			break;

			case 'high' :
				$priority_html = "<span class='btn btn-hot light status'>High</span>";
			break;

			case 'highest' :
				$priority_html = "<span class='btn btn-danger light status'>Highest</span>";
			break;

			default : $priority_html = '';
		endswitch;

		return $priority_html;
	}

	public function getOwnerHtmlAttribute()
	{
		return $this->owner->profile_html;
	}

	public function getStartAttribute()
	{
		return $this->attributes['start_date'];
	}

	public function getEndAttribute()
	{
		return $this->attributes['end_date'];
	}

	public function getDueDateAttribute()
	{
		return $this->end_date;
	}

	public function getPeriodAttribute()
	{
		$period = null;

		if($this->start_date->toDateString() == $this->end_date->toDateString()) :
			$period = $this->start_date->format('D, M j, Y') . ' ' . $this->start_date->format('h:i A') . ' - ' . $this->end_date->format('h:i A');
		else :
			$period = $this->start_date->toDayDateTimeString() . ' - ' . $this->end_date->toDayDateTimeString();
		endif;

		return $period;
	}

	public function getColorAttribute()
	{
		if(is_null($this->priority)) :
			return null;
		endif;
		
		switch($this->priority) :
			case 'high' :
				return 'rgba(255, 135, 30, 0.8)';				
			break;

			case 'highest' :
				return 'rgba(255, 65, 55, 0.8)';
			break;

			case 'low' :
				return 'rgba(65, 155, 115, 1)';
			break;

			case 'lowest' :
				return 'rgba(50, 175, 175, 1)';
			break;

			case 'normal' :
				return 'rgba(115, 155, 200, 1)';
			break;

			default : return null;
		endswitch;	
	}

	public function getAttendeesListAttribute()
	{
		return $this->attendees->pluck('id_type')->toArray();
	}

	public function getAttendeesHtmlAttribute()
	{
		$attendees_html = '';

		foreach($this->attendees as $key => $attendee) :
			if($key < 2) :
				$attendees_html .= self::getAttendeeHtml($attendee);

				if($key == 1) :
					if($this->attendees->count() == 3) :
						$attendees_html .= self::getAttendeeHtml($this->attendees[2]);
					elseif($this->attendees->count() > 3) :
						$count = $this->attendees->count() - 2;
						$rest_attendees = self::getRestAttendees($this->attendees, 2);
						$attendees_html .= "<a class='link-with-img more add-multiple' data-toggle='tooltip' data-placement='top' data-html='true' title='" . $rest_attendees . "' modal-title='Event Attendees' modal-sub-title='" . $this->name . "' modal-datatable='true' datatable-url='event-attendee-data/" . $this->id . "' data-action='' data-content='event.partials.modal-event-attendee' save-new='false-all' cancel-txt='Close'>" .
												$count .
							  				"</a>";
					endif;

					break;
				endif;		  			
			endif;			  			
		endforeach;
		
		return $attendees_html;
	}

	public function getRelatedAttribute()
	{
		return $this->linked;
	}

	/*
	|--------------------------------------------------------------------------
	| RELATIONS
	|--------------------------------------------------------------------------
	*/
	// relation: belongsTo
	public function owner()
	{
		return $this->belongsTo(Staff::class, 'event_owner')->withTrashed();
	}

	// relation: hasMany
	public function attendees()
	{
		return $this->hasMany(EventAttendee::class);
	}

	// relation: morphTo
	public function linked()
	{
		return $this->morphTo();
	}

	// relation: morphOne
	public function reminder()
	{
		return $this->morphOne(Reminder::class, 'linked');
	}

	public function activity()
	{
		return $this->morphOne(Activity::class, 'linked');
	}

	// relation: morphMany
	public function allowedstaffs()
	{
		return $this->morphMany(AllowedStaff::class, 'linked');
	}

	public function calls()
	{
		return $this->morphMany(Call::class, 'related');
	}
	
	public function notificationInfos()
	{
		return $this->morphMany(NotificationInfo::class, 'linked');
	}

	public function linearNotes()
	{
		return $this->morphMany(NoteInfo::class, 'linked');
	}

	public function notes()
	{
		return $this->morphMany(Note::class, 'linked');
	}

	public function attachfiles()
	{
		return $this->morphMany(AttachFile::class, 'linked');
	}
}