<?php

namespace App\Http\Controllers\Admin;

use App\Models\Task;
use App\Models\Staff;
use App\Models\Activity;
use App\Models\TaskStatus;
use App\Models\FilterView;
use App\Models\AllowedStaff;
use App\Jobs\SaveAllowedStaff;
use Illuminate\Http\Request;
use App\Library\DatatablesManager;
use App\Http\Controllers\Admin\AdminBaseController;

class AdminTaskController extends AdminBaseController
{
	public function __construct()
	{
		parent::__construct();

		$this->middleware('admin:task.view', ['only' => ['index', 'projectData', 'show']]);
		$this->middleware('admin:task.create', ['only' => ['store']]);
		$this->middleware('admin:task.edit', ['only' => ['edit', 'update']]);
		$this->middleware('admin:task.delete', ['only' => ['destroy', 'bulkDestroy']]);
	}



	public function index()
	{
		$page = ['title' => 'Tasks List', 'item' => 'Task', 'breadcrumb' => Task::getBreadcrumb(), 'field' => 'tasks', 'view' => 'admin.task', 'route' => 'admin.task', 'permission' => 'task', 'import' => permit('import.task'), 'bulk' => 'update', 'mass_update_permit' => permit('mass_update.task'), 'mass_del_permit' => permit('mass_delete.task'), 'filter' => true, 'current_filter' => FilterView::getCurrentFilter('task')];
		$table = Task::getTableFormat();

		return view('admin.task.index', compact('page', 'table'));
	}



	public function taskData(Request $request)
	{
		if($request->ajax()) :
			return Task::getTableData($request);
		endif;
	}



	public function store(Request $request)
	{
		if($request->ajax()) :
			$status = true;
			$errors = null;
			$kanban = [];
			$kanban_count = [];
			$calendar_task = null;
			$inner_html = [];
			$notifees = [];
			$notification = null;
			$data = $request->all();
			$validation = Task::validate($data);

			if($validation->passes()) :
				$position = Task::getTargetPositionVal(-1);

				$task = new Task;
				$task->task_owner = null_if_empty($request->task_owner);
				$task->name = $request->name;	
				$task->start_date = null_if_empty($request->start_date);
				$task->due_date = null_if_empty($request->due_date);
				$task->priority = null_if_empty($request->priority);	
				$task->description = null_if_empty($request->description);				
				$task->access = $request->access;	
				$task->position = $position;	

				$task_status = TaskStatus::find($request->task_status_id);
				$task->task_status_id = $request->task_status_id;
				$task->completion_percentage = ($task_status->category == 'open') ? $request->completion_percentage : 100;

				if($request->related_type !== '') :
					$task->linked_id = $request->related_id;
					$task->linked_type = $request->related_type;
				endif;

				$task->save();

				Activity::create(['linked_id' => $task->id, 'linked_type' => 'task']);

				if($request->access == 'private') :
					dispatch(new SaveAllowedStaff($request->staffs, 'task', $task->id, $request->can_write, $request->can_delete));
				endif;

				$inner_html[] = ["#next-action", non_property_checker($task->linked, 'next_task_html')];

				$kanban[$task->kanban_stage_key][] = $task->kanban_card_html;
				$kanban_count = Task::getKanbanStageCount();

				$calendar_task = json_encode(['id' => $task->id, 'title' => $request->name, 'start' => $task->start, 'end' => $task->end, 'color' => $task->color, 'show_route' => $task->show_route, 'auth_can_edit' => $task->auth_can_edit]);

				array_push($notifees, $request->task_owner);
				$notifees = array_unique($notifees);
				$notification = notification_log('task_assigned', 'task', $task->id, 'staff', $notifees);
			else :
				$status = false;
				$errors = $validation->getMessageBag()->toArray();
			endif;

			return response()->json(['status' => $status, 'kanban' => $kanban, 'kanbanCount' => $kanban_count, 'errors' => $errors, 'renderEvent' => $calendar_task, 'innerHtml' => $inner_html, 'notification' => $notification]);
		endif;
	}



	public function show(Request $request, Task $task, $infotype = null)
	{
		$page = ['title' => 'Task: ' . $task->name, 'item_title' => breadcrumbs_render("admin.activity.index:Activities|admin.task.index:Tasks|<span data-realtime='name'>" . str_limit($task->name, 50) . "</span>"), 'item' => 'Task', 'view' => 'admin.task', 'tabs' => ['list' => Task::informationTypes(), 'default' => Task::defaultInfoType($infotype), 'item_id' => $task->id, 'url' => 'tab/task']];
		return view('admin.task.show', compact('page', 'task'));
	}



	public function edit(Request $request, Task $task)
	{
		if($request->ajax()) :
			$status = true;
			$info = null;
			$html = null;

			if(isset($task) && isset($request->id)) :
				if($task->id == $request->id) :
					$info = $task->toArray();

					$info['freeze'] = [];

					if(!$task->auth_can_change_owner) :
						$info['freeze'][] = 'task_owner';
					endif;	

					$info['show'] = [];
				
					if(!is_null($info['related_type'])) :
						$related_field = $info['related_type'] . '_id';						
						$info[$related_field] = $info['related_id'];	
						$info['show'][] = $related_field;
					endif;

					$info['modal_title_link'] = ['href' => route('admin.task.show', $task->id), 'title' => str_limit($info['name'], 70, '.')];
					$info['modal_footer_delete'] = ['action' => route('admin.task.destroy', $task->id), 'id' => $task->id];

					$info = (object)$info; 

					if(isset($request->html)) :
						$html = view('admin.task.partials.form', ['form' => 'edit'])->render();
					endif;	
				else :
					$status = false;
				endif;
			else :
				$status = false;
			endif;

			return response()->json(['status' => $status, 'info' => $info, 'html' => $html]);
		endif;

		return redirect()->route('admin.task.index');
	}



	public function update(Request $request, Task $task)
	{
		if($request->ajax()) :
			$status = true;
			$kanban = [];
			$kanban_count = [];
			$update_calendar = null;
			$errors = null;
			$data = $request->all();

			if(isset($task) && isset($request->id) && $task->id == $request->id) :
				$validation = Task::validate($data);
				if($validation->passes()) :
					if($task->auth_can_change_owner) :
						$task->task_owner = null_if_empty($request->task_owner);
					endif;

					$old_status = $task->task_status_id;
					$new_status = (int)$request->task_status_id;

					if($old_status != $new_status) :
						$position = Task::getTargetPositionVal(-1);
						$task->position = $position;
					endif;

					$task->name = $request->name;	
					$task->start_date = null_if_empty($request->start_date);
					$task->due_date = null_if_empty($request->due_date);
					$task->priority = null_if_empty($request->priority);	
					$task->description = null_if_empty($request->description);			
					$task->access = $request->access;	

					$task_status = TaskStatus::find($request->task_status_id);
					$task->task_status_id = $request->task_status_id;
					$task->completion_percentage = ($task_status->category == 'open') ? $request->completion_percentage : 100;	

					if($request->related_type == '') :
						$task->linked_id = null;
						$task->linked_type = null;
					else :							
						$task->linked_id = $request->related_id;
						$task->linked_type = $request->related_type;
					endif;	

					$task->update();

					if($request->access != 'private') :
						$task->allowedstaffs()->forceDelete();
					endif;

					$kanban[$task->kanban_stage_key][$task->kanban_card_key] = ($old_status != $new_status) ? $task->kanban_card_html : $task->kanban_card;
					$kanban_count = Task::getKanbanStageCount();
					$update_calendar = json_encode(['id' => $task->id, 'title' => $request->name, 'start' => $task->start, 'end' => $task->end, 'color' => $task->color, 'show_route' => $task->show_route, 'auth_can_edit' => $task->auth_can_edit]);
				else :
					$status = false;
					$errors = $validation->getMessageBag()->toArray();
				endif;
			else :
				$status = false;
			endif;

			return response()->json(['status' => $status, 'kanban' => $kanban, 'kanbanCount' => $kanban_count, 'errors' => $errors, 'updateEvent' => $update_calendar, 'saveId' => $request->id]);
		endif;
	}



	public function singleUpdate(Request $request, Task $task)
	{
		if($request->ajax()) :
			$status = true;
			$html = null;
			$realtime = [];
			$real_replace = [];
			$inner_html = [];
			$edit_false = [];
			$updated_by = null;
			$last_modified = null;
			$errors = null;
			$data = $request->all();

			if(isset($task) && $task->auth_can_edit) :
				$data['id'] = $task->id;
				$data['change_owner'] = (isset($request->task_owner) && $task->auth_can_change_owner);
				if(array_key_exists('linked_type', $data) && !empty($data['linked_type'])) :
					$related_field = $data['linked_type'] . '_id';
					$data['linked_id'] = $data[$related_field];
				endif;

				$validation = Task::singleValidate($data, $task);
				if($validation->passes()) :	
					if(isset($request->task_status_id)) :
						$task_status = TaskStatus::find($request->task_status_id);

						if($task->task_status_id != $request->task_status_id) :
							$task->update(['completion_percentage' => $task_status->completion_percentage]);
						endif;	

						if($task_status->category == 'closed') :
							$edit_false[] = 'completion_percentage';							
						endif;	
					endif;	
						
					$update_data = replace_null_if_empty($request->all());					
					$task->update($update_data);

					if(isset($request->access)) :
						$html = $task->access_html;

						if($request->access != 'private') :
							$task->allowedstaffs()->forceDelete();
						endif;	
					endif;	

					if(isset($request->name)) :
						$html = str_limit($task->name, 50);
					endif;	

					if(isset($request->start_date)) :
						$html = not_null_empty($task->start_date) ? $task->readableDate('start_date') : '';
					endif;

					if(isset($request->due_date)) :
						$html = not_null_empty($task->due_date) ? $task->readableDate('due_date') : '';
					endif;

					if(isset($request->task_status_id) || isset($request->completion_percentage)) :
						if($task->status->category == 'closed' && $task->completion_percentage != 100) :
							$task->update(['completion_percentage' => 100]);
						endif;	

						$completion_html = "<div class='value percent' data-value='" . $task->completion_percentage . "' data-realtime='completion_percentage'>" . $task->completion_percentage . "</div>";
						$real_replace[] = ["[data-realtime='completion_percentage']", $completion_html];
						$inner_html[] = ["#completion-percentage", $task->classified_completion, false];
					endif;	

					if(isset($request->linked_type)) :
						if(is_null($request->linked_type) || empty($request->linked_type)) :
							$task->update(['linked_id' => null, 'linked_type' => null]);
							$html = '';							
						else :
							$task->update(['linked_id' => $data['linked_id'], 'linked_type' => $data['linked_type']]);
							$html = $task->linked->name_link_icon;
						endif;	
					endif;

					$updated_by = "<p class='compact'>" . $task->updatedByName() . "<br><span class='c-shadow sm'>" . $task->updated_ampm . "</span></p>";
					$last_modified = "<p data-toggle='tooltip' data-placement='bottom' title='" . $task->readableDateAmPm('modified_at') . "'>" . time_short_form($task->modified_at->diffForHumans()) . "</p>";
				else :
					$status = false;
					$errors = $validation->getMessageBag()->toArray();
				endif;
			else :
				$status = false;
			endif;

			return response()->json(['status' => $status, 'html' => $html, 'updatedBy' => $updated_by, 'lastModified' => $last_modified, 'realtime' => $realtime, 'realReplace' => $real_replace, 'innerHtml' => $inner_html, 'editFalse' => $edit_false, 'errors' => $errors]);
		endif;
	}



	public function closedOrReopen(Request $request, Task $task)
	{
		if($request->ajax()) :
			$status = false;
			$save_id = null;

			if(isset($task) && $task->auth_can_edit) :
				$default_status = $task->status->category == 'open' ? TaskStatus::getDefaultClosed() : TaskStatus::getDefaultOpen();
				$task->update(['task_status_id' => $default_status->id, 'completion_percentage' => $default_status->completion_percentage]);
				$status = true;
				$save_id = $task->id;
			endif;

			return response()->json(['status' => $status, 'saveId' => $save_id]);
		endif;
	}



	public function destroy(Request $request, Task $task)
	{
		if($request->ajax()) :
			$status = true;
			$kanban = [];
			$kanban_count = [];
			$calendar_task_id = null;
			$redirect = null;

			if($task->id != $request->id || !$task->auth_can_delete) :
				$status = false;
			endif;

			if($status == true) :
				if($request->redirect) :
					$prev = Task::getAuthViewData()->where('id', '>', $task->id)->get()->first();
					$next = Task::getAuthViewData()->where('id', '<', $task->id)->latest('id')->get()->first();
					
					if(isset($next)) :
						$redirect = route('admin.task.show', $next->id);
					elseif(isset($prev)) :
						$redirect = route('admin.task.show', $prev->id);
					else :
						$redirect = route('admin.task.index');
					endif;	
				endif;	

				$calendar_task_id = $task->id;
				$kanban[] = $task->kanban_card_key;
				$task->delete();
				$kanban_count = Task::getKanbanStageCount();
				event(new \App\Events\TaskDeleted([$request->id]));
			endif;	
			
			return response()->json(['status' => $status, 'kanban' => $kanban, 'kanbanCount' => $kanban_count, 'redirect' => $redirect, 'eventId' => $calendar_task_id]);
		endif;
	}



	public function bulkDestroy(Request $request)
	{
		if($request->ajax()) :
			$tasks = $request->tasks;

			$status = true;

			if(isset($tasks) && count($tasks) > 0) :
				$task_ids = Task::whereIn('id', $tasks)->get()->where('auth_can_delete', true)->pluck('id')->toArray();
				Task::whereIn('id', $task_ids)->delete();
				event(new \App\Events\TaskDeleted($task_ids));
			else :
				$status = false;
			endif;

			return response()->json(['status' => $status]);
		endif;
	}



	public function bulkUpdate(Request $request)
	{
		if($request->ajax()) :
			$tasks = $request->tasks;
			$status = true;
			$errors = null;
			$data = $request->all();

			if(isset($tasks) && count($tasks) > 0 && isset($request->related)) :
				$validation = Task::massValidate($data);

				if($validation->passes()) :
					$task_ids = Task::whereIn('id', $tasks)->get()->where('auth_can_edit', true)->pluck('id')->toArray();
					$tasks = Task::whereIn('id', $task_ids);

					if(\Schema::hasColumn('tasks', $request->related)) :
						$field = $request->related;
						$update_data = [$field => null_if_empty($request->$field)];

						if($request->related == 'linked_type') :
							$linked_field = $request->linked_type . '_id';
							$update_data['linked_id'] = $request->$linked_field;
						endif;

						if($request->related == 'start_date') :
							$tasks = $tasks->where('due_date', '>=', $request->start_date)->orWhere('due_date', NULL);
						endif;	

						if($request->related == 'due_date') :
							$tasks = $tasks->where('start_date', '<=', $request->due_date)->orWhere('start_date', NULL);
						endif;

						if($request->related == 'task_status_id') :
							$task_status = TaskStatus::find($request->task_status_id);
							if($task_status->category == 'closed') :
								$update_data['completion_percentage'] = 100;
							else :
								$tasks->where('task_status_id', '!=', $request->task_status_id)->update(['completion_percentage' => $task_status->completion_percentage]);	
							endif;	
						endif;

						if($request->related == 'completion_percentage' && $request->completion_percentage < 100) :
							$tasks = $tasks->whereIn('task_status_id', TaskStatus::getCategoryIds('open'));
						endif;	

						$tasks->update($update_data);
					endif;	
				else :
					$status = false;
					$errors = $validation->getMessageBag()->toArray();
				endif;
			else :
				$status = false;
			endif;

			return response()->json(['status' => $status, 'errors' => $errors]);
		endif;
	}



	public function indexKanban(Request $request)
	{
		$page = ['title' => 'Tasks Kanban', 'item' => 'Task', 'item_title' => Task::getBreadcrumb(), 'view' => 'admin.task', 'route' => 'admin.task', 'permission' => 'task', 'import' => permit('import.task'), 'modal_edit' => true, 'modal_delete' => true, 'modal_bulk_update' => false, 'modal_bulk_delete' => false];
		$tasks_kanban = Task::getKanbanData();

		return view('admin.task.kanban', compact('page', 'tasks_kanban'));
	}



	public function kanbanCard(Request $request, TaskStatus $task_status)
	{
		if($request->ajax()) :
			$status = true;
			$html = '';
			$load_status = true;
			$errors = null;
			$data = $request->all();

			if(isset($task_status) && $task_status->id == $request->stageId && isset($request->ids)) :
				$validation = Task::kanbanCardValidate($data);

				if($validation->passes()) :
					$bottom_id = (int)last($request->ids);
					$bottom_task = Task::find($bottom_id);
					$tasks = Task::getAuthViewData()->where('position', '<', $bottom_task->position)->where('task_status_id', $task_status->id)->latest('position')->get();
					$load_status = ($tasks->count() > 10);
					
					foreach($tasks->take(10) as $task) :
						$html .= $task->kanban_card_html;
					endforeach;
				else :
					$status = false;
					$errors = $validation->getMessageBag()->toArray();
				endif;
			else :
				$status = false;	
			endif;

			return response()->json(['status' => $status, 'errors' => $errors, 'html' => $html, 'loadStatus' => $load_status]);
		endif;	
	}



	public function indexCalendar(Request $request)
	{
		$page = ['title' => 'Tasks Calendar', 'item' => 'Task', 'item_title' => Task::getBreadcrumb(), 'view' => 'admin.task', 'route' => 'admin.task', 'permission' => 'task', 'import' => permit('import.task'), 'modal_edit' => true, 'modal_delete' => true, 'modal_bulk_update' => false, 'modal_bulk_delete' => false, 'modal_title_link' => true, 'modal_footer_delete' => permit('task.delete')];
		return view('admin.task.calendar', compact('page'));
	}



	public function calendarData()
	{
		$tasks = Task::get(['id', 'name', 'priority', 'start_date', 'due_date', 'created_at'])->toArray();
		return response()->json($tasks);
	}



	public function updateCalendarPosition(Request $request)
	{
		if($request->ajax()) :
			$status = false;
			$errors = null;

			$task = Task::find($request->id);

			if(isset($task)) :
				$status = true;
				$start_date = str_replace('T', ' ', $request->start);
				$due_date = str_replace('T', ' ', $request->end);
				$task->start_date = $start_date;

				if(!is_null($task->due_date)) :
					$task->due_date = $due_date;
				endif;
					
				$task->save();
			endif;	

			return response()->json(['status' => $status, 'errors' => $errors]);
		endif;	
	}



	public function connectedTaskData(Request $request, $module_name, $module_id)
	{
		if($request->ajax()) :
			$module = morph_to_model($module_name)::find($module_id);
			if(isset($module)) :
				$tasks = $module->tasks()->latest('id')->get();
				return Task::getTabTableData($tasks, $request);
			endif;
			
			return null;	
		endif;
	}
}