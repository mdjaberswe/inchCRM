<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\BaseModel;
use App\Models\Traits\OwnerTrait;
use App\Models\Traits\SubModuleTrait;
use App\Models\Traits\PosionableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class Task extends BaseModel
{
	use SoftDeletes;
	use OwnerTrait;
	use SubModuleTrait;
	use PosionableTrait;
	use RevisionableTrait;	
	
	protected $table = 'tasks';
	protected $fillable = ['task_owner', 'name', 'description', 'priority', 'access', 'task_status_id', 'completion_percentage', 'start_date', 'due_date', 'linked_type', 'linked_id', 'milestone_id', 'position'];
	protected $appends = ['related_id', 'related_type', 'title', 'start', 'end', 'color', 'show_route', 'auth_can_edit'];
	protected $dates = ['deleted_at'];
	protected $revisionCreationsEnabled = true;
	protected static $related_types = ['lead', 'contact', 'account', 'project', 'campaign', 'deal', 'estimate', 'invoice'];
	protected static $priority_list = ['high', 'highest', 'low', 'lowest', 'normal'];
	protected static $fieldlist = ['access' => 'Access', 'completion_percentage' => 'Completion Percentage', 'description' => 'Description', 'due_date' => 'Due Date', 'priority' => 'Priority', 'linked_type' => 'Related To', 'linked_id' => 'Related Name', 'start_date' => 'Start Date', 'name' => 'Task Name', 'task_owner' => 'Task Owner', 'task_status_id' => 'Task Status'];
	protected static $mass_fieldlist = ['access', 'completion_percentage', 'description', 'due_date', 'priority', 'linked_type', 'start_date', 'name', 'task_owner', 'task_status_id'];
	protected static $filter_fieldlist = ['access', 'completion_percentage', 'description', 'due_date', 'priority', 'linked_type', 'start_date', 'name', 'task_owner', 'task_status_id'];

	public static function validate($data)
	{	
		$start_date = $data['start_date'];
		$start_date_minus = date('Y-m-d', strtotime($start_date . ' -1 day'));
		$due_date_rule = not_null_empty($data['start_date']) ? "after:$start_date_minus" : "";
		$related_types = implode(',', self::$related_types);
		$priority_list = implode(',', self::$priority_list);

		$rules = ["name"			=> "required|max:200",
				  "task_owner"		=> "exists:users,linked_id,linked_type,staff,status,1,deleted_at,NULL",
				  "priority"		=> "in:$priority_list",	
				  "start_date"		=> "date",
				  "due_date"		=> "date|$due_date_rule",
				  "related_type"	=> "in:$related_types",				  
				  "description"		=> "max:65535",
				  "access"			=> "required|in:private,public,public_rwd",
				  "task_status_id"	=> "required|exists:task_status,id,deleted_at,NULL",
				  "completion_percentage" => "numeric|min:0|max:100|in:0,10,20,30,40,50,60,70,80,90,100"];

		if(array_key_exists('related_type', $data) && !empty($data['related_type'])) :
			$rules['related_id'] = 'required|exists:' . $data['related_type'] . 's,id,deleted_at,NULL';
		endif;   

		return \Validator::make($data, $rules);
	}

	public static function singleValidate($data, $task = null)
	{
		$name_required = array_key_exists('name', $data) ? "required" : '';
		$access_required = array_key_exists('access', $data) ? "required" : '';
		$status_required = array_key_exists('task_status_id', $data) ? "required" : '';
		$related_types = implode(',', self::$related_types);
		$priority_list = implode(',', self::$priority_list);

		$after_date_rules = '';
		if(array_key_exists('due_date', $data) && !empty($data['due_date']) && !is_null($task) && not_null_empty($task->start_date)) :
			$after_date = date('Y-m-d', strtotime($task->start_date . ' -1 day'));
			$after_date_rules = "after:$after_date";
		endif;

		$rules = ["name"			=> "$name_required|max:200",
				  "task_owner"		=> "exists:users,linked_id,linked_type,staff,status,1,deleted_at,NULL",
				  "priority"		=> "in:$priority_list",	
				  "start_date"		=> "date",
				  "due_date"		=> "date|$after_date_rules",
				  "linked_type"		=> "in:$related_types",				  
				  "description"		=> "max:65535",
				  "access"			=> "$access_required|in:private,public,public_rwd",
				  "task_status_id"	=> "$status_required|exists:task_status,id,deleted_at,NULL",
				  "completion_percentage" => "numeric|min:0|max:100|in:0,10,20,30,40,50,60,70,80,90,100"];		  

		if(array_key_exists('linked_type', $data) && !empty($data['linked_type'])) :
			$rules['linked_id'] = 'required|exists:' . $data['linked_type'] . 's,id,deleted_at,NULL';
		endif;

		return \Validator::make($data, $rules);
	}

	public static function massValidate($data)
	{
		$valid_field = implode(',', self::massfieldlist());
		$name_required = $data['related'] == 'name' ? 'required' : '';
		$status_required = $data['related'] == 'task_status_id' ? 'required' : '';
		$access_required = $data['related'] == 'access' ? 'required' : '';
		$related_types = implode(',', self::$related_types);
		$priority_list = implode(',', self::$priority_list);

		$rules = ["related"			=> "required|in:$valid_field",
				  "name"			=> "$name_required|max:200",
				  "task_owner"		=> "exists:users,linked_id,linked_type,staff,status,1,deleted_at,NULL",
				  "priority"		=> "in:$priority_list",	
				  "start_date"		=> "date",
				  "due_date"		=> "date",
				  "linked_type"		=> "in:$related_types",				  
				  "description"		=> "max:65535",
				  "access"			=> "$access_required|in:private,public,public_rwd",
				  "task_status_id"	=> "$status_required|exists:task_status,id,deleted_at,NULL",
				  "completion_percentage" => "numeric|min:0|max:100|in:0,10,20,30,40,50,60,70,80,90,100"];		  

		if(array_key_exists('linked_type', $data) && !empty($data['linked_type'])) :
			$rules['linked_id'] = 'required|exists:' . $data['linked_type'] . 's,id,deleted_at,NULL';
		endif;

		return \Validator::make($data, $rules);
	}

	public static function importValidate($data)
	{
		$status = true;
		$errors = [];

		if(!in_array('name', $data)) :
			$status = false;
			$errors[] = 'The task name field is required.';
		endif;

		$outcome = ['status' => $status, 'errors' => $errors];

		return $outcome;
	}

	public static function filterValidate($data)
	{
		$string_condition = 'required|in:equal,not_equal,contain,not_contain,empty,not_empty';
		$date_condition = 'required|in:before,after,last,next,empty,not_empty';
		$dropdown_condition = 'required|in:equal,not_equal,empty,not_empty';
		$numeric_condition = 'required|in:equal,not_equal,less,greater';

		$name_condition_rule = '';
		$name_rule = '';
		if(array_key_exists('name_condition', $data)) :
			$name_condition_rule = $string_condition;

			if($data['name_condition'] != 'empty' && $data['name_condition'] != 'not_empty') :
				$name_rule = 'required|array|max:200';
			endif;	
		endif;	

		$description_condition_rule = '';
		$description_rule = '';
		if(array_key_exists('description_condition', $data)) :
			$description_condition_rule = $string_condition;

			if($data['description_condition'] != 'empty' && $data['description_condition'] != 'not_empty') :
				$description_rule = 'required|array|max:65535';
			endif;	
		endif;	

		$access_condition_rule = '';
		$access_rule = '';
		if(array_key_exists('access_condition', $data)) :
			$access_condition_rule = $dropdown_condition;

			if($data['access_condition'] != 'empty' && $data['access_condition'] != 'not_empty') :
				$access_rule = 'required|array|in:private,public,public_rwd';
			endif;
		endif;

		$priority_condition_rule = '';
		$priority_rule = '';
		if(array_key_exists('priority_condition', $data)) :
			$priority_condition_rule = $dropdown_condition;

			if($data['priority_condition'] != 'empty' && $data['priority_condition'] != 'not_empty') :
				$priority_list = implode(',', self::$priority_list);
				$priority_rule = 'required|array|in:' . $priority_list;
			endif;
		endif;

		$linked_type_condition_rule = '';
		$linked_type_rule = '';
		$linked_id_rule = '';
		if(array_key_exists('linked_type_condition', $data)) :
			$linked_type_condition_rule = $dropdown_condition;

			if($data['linked_type_condition'] != 'empty' && $data['linked_type_condition'] != 'not_empty') :
				$related_types = implode(',', self::$related_types);
				$linked_type_rule = 'required|in:' . $related_types;
				$linked_id_rule = 'required|exists:' . $data['linked_type'] . 's,id,deleted_at,NULL';
			endif;
		endif;

		$task_owner_condition_rule = '';
		$task_owner_rule = '';
		if(array_key_exists('task_owner_condition', $data)) :
			$task_owner_condition_rule = $dropdown_condition;

			if($data['task_owner_condition'] != 'empty' && $data['task_owner_condition'] != 'not_empty') :
				$valid_owners = User::onlyStaff()->where('status', 1)->pluck('linked_id')->toArray();
				$valid_owners = '0,' . implode(',', $valid_owners);
				$task_owner_rule = 'required|array|in:' . $valid_owners;
			endif;
		endif;

		$status_condition_rule = '';
		$status_rule = '';
		if(array_key_exists('task_status_id_condition', $data)) :
			$status_condition_rule = $dropdown_condition;

			if($data['task_status_id_condition'] != 'empty' && $data['task_status_id_condition'] != 'not_empty') :
				$status_rule = 'required|exists:task_status,id,deleted_at,NULL';
			endif;
		endif;

		$start_date_condition_rule = '';
		$start_date_rule = '';
		if(array_key_exists('start_date_condition', $data)) :
			$start_date_condition_rule = $date_condition;

			if($data['start_date_condition'] != 'empty' && $data['start_date_condition'] != 'not_empty') :
				$start_date_rule = 'required|in:7,30,90';
			endif;
		endif;

		$due_date_condition_rule = '';
		$due_date_rule = '';
		if(array_key_exists('due_date_condition', $data)) :
			$due_date_condition_rule = $date_condition;

			if($data['due_date_condition'] != 'empty' && $data['due_date_condition'] != 'not_empty') :
				$due_date_rule = 'required|in:7,30,90';
			endif;
		endif;

		$completion_percentage_condition_rule = '';
		$completion_percentage_rule = '';
		if(array_key_exists('completion_percentage_condition', $data)) :
			$completion_percentage_condition_rule = $numeric_condition;
			$completion_percentage_rule = "required|numeric|min:0|max:100|in:0,10,20,30,40,50,60,70,80,90,100";
		endif;

		$rules = ['name'					=> $name_rule,
				  'name_condition'			=> $name_condition_rule,
				  'task_owner'				=> $task_owner_rule,
				  'task_owner_condition'	=> $task_owner_condition_rule,
				  'priority'				=> $priority_rule,
				  'priority_condition'		=> $priority_condition_rule,	
				  'start_date'				=> $start_date_rule,
				  'start_date_condition'	=> $start_date_condition_rule,
				  'due_date'				=> $due_date_rule,
				  'due_date_condition'		=> $due_date_condition_rule,
				  'linked_id'				=> $linked_id_rule,	
				  'linked_type'				=> $linked_type_rule,
			  	  'linked_type_condition' 	=> $linked_type_condition_rule,
				  'description'				=> $description_rule,
				  'description_condition' 	=> $description_condition_rule,
				  'access'					=> $access_rule,
				  'access_condition'		=> $access_condition_rule,
				  'task_status_id'			=> $status_rule,
				  'task_status_id_condition'=> $status_condition_rule,
				  'completion_percentage'	=> $completion_percentage_rule,
				  'completion_percentage_condition' => $completion_percentage_condition_rule];		  

		return \Validator::make($data, $rules);
	}

	public static function getBreadcrumb()
	{
		$filter_views = FilterView::getFilterViews('task');
		$current_filter = FilterView::getCurrentFilter('task');
		$prestar = $current_filter->custom_view_name ? 'prestar' : '';
		$save_as_view = $current_filter->custom_view_name ? "<a class='bread-link save-as-view' data-item='task'>Save as View</a>" : "";

		$action_btns = '';
		if(!$current_filter->is_fixed && empty($prestar))
		{
			$action_btns = $current_filter->action_btns_html;	  		    
		}

		$breadcrumb = "<ol class='breadcrumb'>";

		$breadcrumb .= "<li><a href='" . route('admin.activity.index') . "'>Activities</a></li>";
		$breadcrumb .= "<li><a href='" . route('admin.task.index') . "'>Tasks</a></li>";

		$breadcrumb .= "<li class='active $prestar'>" . 
							\Form::open(['route' => 'admin.view.dropdown', 'method' => 'post']) .
								"<select name='view' class='form-control breadcrumb-select' data-module='task'>
									<optgroup label='SYSTEM'>";

		foreach($filter_views['system_default'] as $system_view) :
			$selected = $system_view->id == $current_filter->id ? 'selected' : '';
			$breadcrumb .= "<option value='" . $system_view->id . "' $selected>" . $system_view->view_name . "</option>";
		endforeach;

		$breadcrumb .= "</optgroup>";

		if($filter_views['my_views']->count()) :
			$breadcrumb .= "<optgroup label='MY VIEWS'>";

			foreach($filter_views['my_views'] as $my_view) :
				$selected = $my_view->id == $current_filter->id ? 'selected' : '';
				$breadcrumb .= "<option value='" . $my_view->id . "' $selected>" . $my_view->view_name . "</option>";
			endforeach;	

			$breadcrumb .= "</optgroup>";
		endif;

		if($filter_views['shared_views']->count()) :
			$breadcrumb .= "<optgroup label='SHARED VIEWS'>";

			foreach($filter_views['shared_views'] as $shared_view) :
				$selected = $shared_view->id == $current_filter->id ? 'selected' : '';
				$breadcrumb .= "<option value='" . $shared_view->id . "' $selected>" . $shared_view->view_name . "</option>";
			endforeach;	

			$breadcrumb .= "</optgroup>";
		endif;

		$breadcrumb .= "</select>" . \Form::close() . "<div class='inline-block view-btns'>" . $save_as_view . $action_btns . "</div></li></ol>";

		return $breadcrumb;
	}

	public static function prioritylist()
	{
		return self::$priority_list;
	}

	public static function relatedTypes()
	{
		return self::$related_types;
	}

	public static function getFieldValueDropdownList()
	{
		$dropdown['access'] = ['private' => 'Private', 'public' => 'Public read only', 'public_rwd' => 'Public read/write/delete'];
		$dropdown['days'] = ['7' => '7 days', '30' => '30 days', '90' => '90 days'];
		$dropdown['priority'] = ['high' => 'High', 'highest' => 'Highest', 'low' => 'Low', 'lowest' => 'Lowest', 'normal' => 'Normal'];
		$dropdown['task_owner'] = ['0' => 'Me'] + Staff::getAdminList();
		$dropdown['task_status'] = TaskStatus::orderBy('position')->get(['id', 'name'])->pluck('name', 'id')->toArray();
		$dropdown['related_type'] = ['' => '-None-', 'lead' => 'Lead', 'contact' => 'Contact', 'account' => 'Account', 'project' => 'Project', 'campaign' => 'Campaign', 'deal' => 'Deal', 'estimate' => 'Estimate', 'invoice' => 'Invoice'];
		$dropdown['related_to']['lead'] = ['' => '-None-'] + Lead::get(['id', 'first_name', 'last_name', 'company'])->pluck('full_name', 'id')->toArray();
		$dropdown['related_to']['contact'] = ['' => '-None-'] + Contact::orderBy('account_id')->get(['id', 'first_name', 'last_name', 'account_id'])->pluck('full_name', 'id')->toArray();
		$dropdown['related_to']['account'] = ['' => '-None-'] + Account::get(['id', 'account_name'])->pluck('account_name', 'id')->toArray();
		$dropdown['related_to']['project'] = ['' => '-None-'] + Project::get(['id', 'name'])->pluck('name', 'id')->toArray();
		$dropdown['related_to']['campaign'] = ['' => '-None-'] + Campaign::get(['id', 'name'])->pluck('name', 'id')->toArray();
		$dropdown['related_to']['deal'] = ['' => '-None-'] + Deal::get(['id', 'name'])->pluck('name', 'id')->toArray();
		$dropdown['related_to']['estimate'] = ['' => '-None-'] + Estimate::get(['id', 'number'])->pluck('name', 'id')->toArray();
		$dropdown['related_to']['invoice'] = ['' => '-None-'] + Invoice::get(['id', 'number'])->pluck('name', 'id')->toArray();

		return $dropdown;
	}

	public static function kanbanValidate($data)
	{
		$picked_exists = '';
		if(array_key_exists('picked', $data) && $data['picked'] != 0) :
			$picked_exists = 'exists:tasks,id,deleted_at,NULL';
		endif;
			
		$rules = ["source"	=> "required|in:task",
				  "id"		=> "required|exists:tasks,id,deleted_at,NULL",
				  "picked"	=> "required|different:id|$picked_exists",
				  "field"	=> "required|in:task_status_id",
				  "stage"	=> "required|exists:task_status,id,deleted_at,NULL",
				  "ordertype" => "required|in:desc"];

		return \Validator::make($data, $rules);
	}

	public static function kanbanCardValidate($data)
	{
		$rules = ['stageId'	=> 'required|exists:task_status,id,deleted_at,NULL',
				  'ids'		=> 'required|array|exists:tasks,id,deleted_at,NULL'];

		return \Validator::make($data, $rules);
	}

	public static function getKanbanData()
	{
		$outcome = [];

		$all_status = TaskStatus::getSmartOrder();

		foreach($all_status as $status) :
			$key = 'taskstatus-' . $status->id;
			$outcome[$key]['data'] = self::getAuthViewData()->where('task_status_id', $status->id)->latest('position')->get();			
			$outcome[$key]['quick_data'] = $outcome[$key]['data']->take(5);	
			$outcome[$key]['status'] = $status->toArray();
			$outcome[$key]['status']['load_status'] = $outcome[$key]['data']->count() > 5 ? 'true' : 'false';
			$outcome[$key]['status']['load_url'] = route('admin.task.kanban.card', $status->id);
		endforeach;	

		return $outcome;
	}

	public static function getKanbanStageCount()
	{
		$outcome = [];

		$all_status = TaskStatus::getSmartOrder();

		foreach($all_status as $status) :
			$key = 'taskstatus-' . $status->id;
			$count = self::getAuthViewData()->where('task_status_id', $status->id)->get()->count();
			$outcome[$key] = '(' . $count . ')'; 
		endforeach;	

		return $outcome;
	}

	public static function informationTypes()
	{
		$information_types = ['overview'	=> 'Overview', 
							  'notes'		=> 'Notes',
							  'worklogs'	=> no_space('Work Logs'),
							  'emails'		=> 'Emails',
							  'calls'		=> 'Calls',
							  'sms'			=> 'SMS',
							  'files'		=> 'Files',
							  'timeline'	=> 'Timeline',
							  'statistics'	=> 'Statistics'];

		return $information_types;
	}

	public static function getTableFormat()
	{
		$table = ['thead' => [no_space('task name'), [no_space('due date'), 'style' => 'min-width: 80px'], ['status', 'style' => 'min-width: 80px'], ['progress', 'style' => 'max-width: 80px'], 'priority', no_space('related to'), 'owner'], 'checkbox' => self::allowMassAction(), 'action' => self::allowAction()];
		$table['json_columns'] = table_json_columns(['checkbox', 'name', 'due_date', 'status', 'completion_percentage', 'priority', 'related_to', 'task_owner', 'action'], self::hideColumns());
		
		return $table;
	}

	public static function getTableData($request)
	{
		$tasks = self::getAuthViewData()->filterViewData()->latest('id')->get();

		return \Datatables::of($tasks)
				->addColumn('checkbox', function($task)
				{
					return $task->checkbox_html;
				})
				->editColumn('name', function($task)
				{
					return $task->name_html;
				})
				->editColumn('due_date', function($task)
				{
					return $task->due_date_html;
				})
				->addColumn('status', function($task)
				{
					return $task->status->name;
				})
				->editColumn('completion_percentage', function($task)
				{
					return $task->completion_html;
				})
				->editColumn('priority', function($task)
				{
					return $task->priority_html;
				})
				->addColumn('related_to', function($task)
				{
					return non_property_checker($task->linked, 'name_link_icon');
				})
				->editColumn('task_owner', function($task)
				{
					return $task->owner_html;
				})
				->addColumn('action', function($task)
				{
					$action_permission = ['edit' => permit('task.edit'), 'delete' => permit('task.delete')];
					return $task->getCompactActionHtml('Task', null, 'admin.task.destroy', $action_permission);
				})
				->make(true);
	}

	public static function getTabTableFormat()
	{
		$json_column = table_json_columns(['name', 'due_date', 'status', 'completion_percentage', 'priority', 'task_owner', 'action']);
		$table = ['json_columns' => $json_column, 'thead' => ['NAME', 'DUE DATE', 'STATUS', 'PROGRESS', 'PRIORITY', 'OWNER'], 'checkbox' => false];
		$table['filter_input']['status'] = ['type' => 'dropdown', 'no_search' => true, 'options' => ['-1' => 'All Tasks', '1' => 'Open Tasks', '0' => 'Closed Tasks']];

		return $table;
	}

	public static function getTabTableData($tasks, $request)
	{
		return \Datatables::of($tasks)
				->editColumn('name', function($task)
				{
					return $task->name_html;
				})
				->editColumn('due_date', function($task)
				{
					return $task->due_date_html;
				})
				->addColumn('status', function($task)
				{
					return $task->status->name;
				})
				->editColumn('completion_percentage', function($task)
				{
					return $task->completion_html;
				})
				->editColumn('priority', function($task)
				{
					return $task->priority_html;
				})
				->editColumn('task_owner', function($task)
				{
					return $task->owner_html;
				})
				->addColumn('action', function($task)
				{
					$action_permission = ['edit' => permit('task.edit'), 'delete' => permit('task.delete')];
					return $task->getCompactActionHtml('Task', null, 'admin.task.destroy', $action_permission, true);
				})
				->filter(function($instance) use ($request)
				{
					$instance->collection = $instance->collection->filter(function ($row) use ($request)
					{
						$status = true;

						$search_val = $request->search['value'];
						if($request->has('search') && $search_val != '') :
							$name = str_contains($row->name, $search_val) ? true : false;
							$due_date = str_contains($row->due_date, $search_val) ? true : false;
							$task_status = str_contains($row->status->name, $search_val) ? true : false;
							$completion_percentage = str_contains($row->completion_percentage, $search_val) ? true : false;
							$priority = str_contains($row->priority, $search_val) ? true : false;
							$owner = str_contains(non_property_checker($row->owner, 'name'), $search_val) ? true : false;

							if(!$name && !$due_date && !$task_status && !$completion_percentage && !$priority && !$owner) :
								$status = false;
							endif;
						endif;

						if($request->has('status') && $request->status == 0 && $row->status->category == 'open') :
							$status = false;
						endif;

						if($request->has('status') && $request->status == 1 && $row->status->category == 'closed') :
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

	public function getStartAttribute()
	{
		$time = 'T00:00:00';
		if(!is_null($this->start_date)) :
			return $this->start_date;
		elseif(!is_null($this->due_date) && strtotime($this->due_date) < strtotime($this->attributes['created_at'])) :
			return $this->due_date;
		else :
			return $this->created_at->format('Y-m-d');
		endif;	
	}

	public function getEndAttribute()
	{
		$time = 'T23:59:59';
		if(!is_null($this->due_date)) :
			$end = $this->due_date;
		elseif(!is_null($this->start_date)) :
			$end = $this->start_date;
		else :
			$end = $this->created_at->format('Y-m-d');
		endif;	

		if($this->start == $end) :
			return $end;
		endif;

		return get_date_after(1, $end);
	}

	public function getNameHtmlAttribute()
	{
		$tooltip = '';
		if(strlen($this->name) > 55) :
			$tooltip = "data-toggle='tooltip' data-placement='top' title='" . $this->name . "'";
		endif;
			
		return "<a href='" . $this->show_route . "' class='status-checkbox-link' $tooltip>" . $this->closed_open_checkbox . str_limit($this->name, 55) . "</a>";
	}

	public function getClosedStatusAttribute()
	{
		return ($this->status->category == 'closed');
	}

	public function getClosedOpenCheckboxAttribute()
	{
		$disabled = $this->auth_can_edit ? '' : 'disabled';
		$css = $this->closed_status == true ? 'reopen mdi-check-circle' : 'complete mdi-check-circle-outline';
		$status_title = $this->closed_status == true ? 'Reopen' : no_space('Mark as Complete');
		$tag_attributes = empty($disabled) ? "data-toggle='tooltip' data-placement='top' title='" . $status_title . "' data-url='" . route('admin.task.closed.reopen', $this->id) . "'" : '';
		$checkbox = "<span class='status-checkbox mdi $css $disabled' $tag_attributes></span>";
		
		return $checkbox;
	}

	public function getNameIconHtmlAttribute()
	{
		$icon = method_exists($this, 'getIconAttribute') ? $this->getIconAttribute() : $this->icon;

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

		return "<a href='" . $this->show_route . "' class='link-icon' $tooltip><span class='icon $icon' data-toggle='tooltip' data-placement='top' title='" . ucfirst($this->identifier) . "'></span> " . str_limit($this->name, $name_length) . $priority_html . "</a>";
	}

	public function getActivityNameHtmlAttribute()
	{
		return $this->name_icon_html;
	}	

	public function getActivityFromAttribute()
	{
		return $this->readableDateHtml('start_date');
	}

	public function getCompletionHtmlAttribute()
	{
		$completion_html = "<a class='completion-show'>								  	
							  <div class='progress'>
						            <div class='progress-bar progress-bar-info' role='progressbar' aria-valuenow='" . $this->completion_percentage . "' aria-valuemin='0' aria-valuemax='100' style='width: " . $this->completion_percentage . "%'>
						                <span class='sr-only'>" . $this->completion_percentage . "% Complete</span>
						            </div>
						            <span class='shadow'>" . $this->completion_percentage . "%</span>
					       		</div>
					        </a>";

		return $completion_html;	
	}

	public function getDateHtmlAttribute()
	{
		$date = '';
		$span_class = 'shadow normal';

		if(isset($this->due_date)) :
			$span_class = 'shadow';
			$date .= "<span class='c-danger' data-toggle='tooltip' data-placement='right' title='Due&nbsp;Date'>" . $this->due_date . "</span>";
			$date .= '<br>';
		endif;

		if(isset($this->start_date)) :
			$date .= "<span class='" . $span_class . "' data-toggle='tooltip' data-placement='right' title='Start&nbsp;Date'>" . $this->start_date . "</span>";
		endif;

		return $date;
	}

	public function getDurationAttribute()
	{
		if(!is_null($this->start_date) && !is_null($this->due_date)) :
			$duration = $this->carbonDate('due_date')->diffInDays($this->carbonDate('start_date'), false);
			$duration = abs($duration);
			return $duration;
		endif;
		
		return null;	
	}

	public function getDurationHtmlAttribute()
	{
		if(!is_null($this->duration)) :
			return $this->duration . ' ' . str_plural('day', $this->duration);
		endif;
		
		return null;	
	}

	public function getDurationTooltipAttribute()
	{
		if(!is_null($this->start_date) && !is_null($this->due_date)) :
			$title = "Start Date: " . $this->readableDateHtml('start_date') . "<br>";
			$title .= "Duration: " . $this->duration_html;
			$tooltip = "data-toggle='tooltip' data-placement='left' data-html='true' title='" . $title . "'";
			return $tooltip;
		endif;
		
		return null;	
	}

	public function getDueDateHtmlAttribute()
	{
		$html = "<span " . $this->duration_tooltip. ">" . $this->readableDateHtml('due_date') . "</span>";
		return $html;
	}

	public function getClassifiedCompletionAttribute()
	{
		if($this->completion_percentage >= 0 && $this->completion_percentage <= 30) :
			$css = 'cold';
		elseif($this->completion_percentage > 31 && $this->completion_percentage <= 70) :
			$css = 'warm';
		elseif($this->completion_percentage > 70 && $this->completion_percentage <= 100) :
			$css = 'hot';
		else :
			$css = 'cold';
		endif;
		$html = "<span class='" . $css . "'>" . $this->completion_percentage . "<i>%</i></span>";
		return $html;
	}

	public function getPriorityHtmlAttribute()
	{
		$priority = $this->priority;
		$priority_html = '';

		switch($priority) :
			case 'low' :
				$priority_html = "<span class='btn btn-primary blur status xs'>Low</span>";
			break;

			case 'lowest' :
				$priority_html = "<span class='btn btn-primary light status xs'>Lowest</span>";
			break;

			case 'normal' :
				$priority_html = "<span class='btn btn-warning light status xs'>Normal</span>";
			break;

			case 'high' :
				$priority_html = "<span class='btn btn-hot light status xs'>High</span>";
			break;

			case 'highest' :
				$priority_html = "<span class='btn btn-danger light status xs'>Highest</span>";
			break;

			default : $priority_html = '';
		endswitch;

		return $priority_html;
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

	public function getOwnerHtmlAttribute()
	{
		if($this->task_owner == null) :
			return null;
		endif;

		return $this->owner->profile_html;
	}

	public function getDueDateStatusAttribute()
	{
		if(!is_null($this->due_date)) :
			$passed_val = $this->passedDateVal('due_date');

			if($passed_val == 0) :
				$status = 'warning';
			elseif($passed_val < 0) :
				$status = 'success';
			else :
				$status = 'danger';
			endif;	

			return $status;
		endif;
		
		return null;	
	}

	public function getKanbanStageKeyAttribute()
	{
		return 'taskstatus-' . $this->task_status_id;
	}

	public function getKanbanCardKeyAttribute()
	{
		return 'task-' . $this->id;
	}

	public function getRelatedIconOrDotAttribute()
	{
		if(!is_null($this->linked_type)) :
			return module_icon($this->linked_type);
		endif;	

		return 'fa fa-circle';
	}

	public function getKanbanCardAttribute()
	{
		$action = '';
		$action_html = '';
		$card_btn = '';

		if($this->auth_can_delete) :
			$action .= "<div class='funnel-btn'>" .							
							\Form::open(['route' => ['admin.task.destroy', $this->id], 'method' => 'delete']) .
								\Form::hidden('id', $this->id) .
								"<button type='submit' class='delete'><i class='mdi mdi-delete'></i></button>" .
				  			\Form::close() . 
					   "</div>";
		endif;	

		if($this->auth_can_edit) :
			$action .= "<div class='funnel-btn'>
							<a><i class='mdi mdi-phone-plus add-multiple' data-item='call' data-action='" . route('admin.call.store') . "' data-content='call.partials.form' data-default='related_type:task|related_id:$this->id|client_type:$this->client_type|client_id:$this->client_id' save-new='false'></i></a>
						</div>";
		endif;

		if($this->auth_can_edit) :
			$action .= "<div class='funnel-btn'>
							<a><i class='mdi mdi-email'></i></a>
						</div>";
		endif;

		if($this->auth_can_edit) :
			$action .= "<div class='funnel-btn'>
							<a><i class='mdi mdi-message'></i></a>
						</div>";
		endif;

		if($this->auth_can_edit) :
			$action .= "<div class='funnel-btn'>
							<a class='edit' editid='" . $this->id . "' data-url='" . route('admin.task.index') . "'><i class='fa fa-pencil'></i></a>
						</div>";
		endif;	

		if($action != '') :
			$card_btn = "<a class='funnel-bottom-btn'><i class='fa fa-ellipsis-v md'></i></a>";
			$action_html = "<div class='full funnel-btn-group'>" . $action . "</div>";
		endif;

		$card = "<div class='funnel-card' data-init-stage='" . $this->task_status_id . "'>
					<div class='funnel-top-btn'>" .
						\Form::hidden('positions[]', $this->id, ['data-stage' => $this->task_status_id]) .
						$card_btn . "
					</div>	

					<div class='full'><a href='" . route('admin.task.show', $this->id) . "' class='title-link'>" . str_limit($this->name, 30, '.') . "</a></div>

					<div class='full'>
						<div class='funnel-card-info'>
							<i class='mdi mdi-trophy-award warning'></i>
							<span data-toggle='tooltip' data-placement='bottom' title='Task&nbsp;Owner'>" . str_limit($this->owner->name, 17, '.') . "</span>
						</div>

						<div class='funnel-card-info'>
							<i class='fa fa-circle'></i>
							<span data-toggle='tooltip' data-placement='bottom' title='Priority'>" . ucfirst($this->priority) . "</span>
						</div>
					</div>

					<div class='full'>
						<div class='funnel-card-info'>
							<i class='" . $this->related_icon_or_dot  . "'></i>
							<span data-toggle='tooltip' data-placement='bottom' title='Related&nbsp;To&nbsp;" . ucfirst($this->linked_type) . "'>" . str_limit(non_property_checker($this->linked, 'name'), 15, '.') . "</span>
						</div>
						
						<div class='funnel-card-info'>
							<i class='fa fa-circle'></i>
							<span data-toggle='tooltip' data-placement='bottom' title='Due&nbsp;Date'>" . $this->readableDate('due_date') . "</span>
						</div>
					</div>" .
					$action_html . "					
				</div>";

		return $card;		
	}

	public function getKanbanCardHtmlAttribute()
	{
		$disable_css = !$this->auth_can_edit ? 'disable' : '';

		$card_html = "<li id='task-" . $this->id . "' class='" . $disable_css . "'>" . 
						$this->kanban_card .
					 "</li>";

		return $card_html;
	}

	public function getRelatedAttribute()
	{
		return $this->linked;
	}

	public function getRelatedTypeAttribute()
	{
		return $this->linked_type;
	}

	public function getRelatedIdAttribute()
	{
		return $this->linked_id;
	}

	public function getIsClientRelatedAttribute()
	{
		return in_array($this->linked_type, ['lead', 'contact']);
	}

	public function getClientTypeAttribute()
	{
		if($this->is_client_related) :
			return $this->linked_type;
		endif;
		
		return null;	
	}

	public function getClientIdAttribute()
	{
		if($this->is_client_related) :
			return $this->linked_id;
		endif;
		
		return null;	
	}

	/*
	|--------------------------------------------------------------------------
	| RELATIONS
	|--------------------------------------------------------------------------
	*/
	// relation: belongsTo
	public function owner()
	{
		return $this->belongsTo(Staff::class, 'task_owner')->withTrashed();
	}

	public function status()
	{
		return $this->belongsTo(TaskStatus::class, 'task_status_id');
	}

	public function milestone()
	{
		return $this->belongsTo(Milestone::class);
	}

	// relation: morphTo
	public function linked()
	{
		return $this->morphTo()->withTrashed();
	}

	// relation: morphOne
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