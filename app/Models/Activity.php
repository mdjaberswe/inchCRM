<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class Activity extends BaseModel
{
	use SoftDeletes;
	use RevisionableTrait;

	protected $table = 'activities';
	protected $fillable = ['linked_id', 'linked_type'];
	protected $dates = ['deleted_at'];
	protected $revisionCreationsEnabled = true;

	public static function getTableFormat()
	{
		$table = ['thead' => ['name', 'from', 'due&nbsp;date', 'related&nbsp;to', 'owner'], 'checkbox' => true, 'action' => true];
		$table['json_columns'] = table_json_columns(['checkbox', 'name', 'start_date', 'due_date', 'related_to', 'owner', 'action'], []);
		
		return $table;
	}

	public static function getTableData($request)
	{
		$activities = self::latest('id')->get();

		return \Datatables::of($activities)
				->addColumn('checkbox', function($activity)
				{
					return $activity->checkbox_html;
				})
				->addColumn('name', function($activity)
				{
					return $activity->linked->activity_name_html;
				})
				->addColumn('start_date', function($activity)
				{
					return $activity->linked->activity_from;
				})
				->addColumn('due_date', function($activity)
				{
					return $activity->linked->readableDateHtml('due_date');
				})
				->addColumn('related_to', function($activity)
				{
					return non_property_checker($activity->linked->related, 'name_link_icon');;
				})
				->addColumn('owner', function($activity)
				{
					return $activity->linked->owner->profile_html;
				})
				->addColumn('action', function($activity)
				{
					$action_permission = ['edit' => true, 'delete' => true];							
					return $activity->linked->getCompactActionHtml($activity->dislay_type, null, $activity->delete_route, $action_permission, true);
				})
				->make(true);
	}

	public static function getBreadcrumb()
	{
		$breadcrumb = "<ol class='breadcrumb'>";

		$breadcrumb .= "<li><a href='" . route('admin.activity.index') . "'>Activities</a></li>";

		$breadcrumb .= "<li class='active'>" . 
							\Form::open(['route' => 'admin.deal.pipeline.kanban', 'method' => 'post']) .
								"<select name='view' class='form-control breadcrumb-select'>
									<optgroup label='SYSTEM'>
										<option value='my_open'>My Open Activities</option>
										<option value='my_overdue'>My Overdue Activities</option>
										<option value='my_calls'>My Calls</option>
										<option value='my_incoming_calls'>My Incoming Calls</option>
										<option value='my_outgoing_calls'>My Outgoing Calls</option>
										<option value='my_tasks'>My Tasks</option>
										<option value='my_open_tasks'>My Open Tasks</option>
										<option value='my_overdue_tasks'>My Overdue Tasks</option>
										<option value='my_events'>My Events</option>										
										<option value='all'>All Activities</option>
										<option value='all_calls'>All Calls</option>
										<option value='all_tasks'>All Tasks</option>
										<option value='all_events'>All Events</option>
										<option value='all_open'>Open Activities</option>
										<option value='all_overdue'>Overdue Activities</option>
									</optgroup>	

									<optgroup label='MY VIEWS'>
										<option value='1'>Important Tasks</option>		
									</optgroup>	
								</select>" .
							\Form::close() .	
						"</li></ol>";

		return $breadcrumb;
	}

	/*
	|--------------------------------------------------------------------------
	| ACCESSOR
	|--------------------------------------------------------------------------
	*/
	public function getEditPermissionAttribute()
	{
		return $this->linked_type . '.edit';
	}

	public function getDeletePermissionAttribute()
	{
		return $this->linked_type . '.delete';
	}

	public function getDeleteRouteAttribute()
	{
		return 'admin.' . $this->linked_type . '.destroy';
	}

	public function getDisplayTypeAttribute()
	{
		return ucfirst($this->linked_type);
	}

	/*
	|--------------------------------------------------------------------------
	| RELATIONS
	|--------------------------------------------------------------------------
	*/
	// relation: morphTo
	public function linked()
	{
		return $this->morphTo();
	}
}	