<?php

namespace App\Models;

use App\Models\BaseModel;
use App\Models\Traits\PosionableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class TaskStatus extends BaseModel
{
	use SoftDeletes;
	use PosionableTrait;
	use RevisionableTrait;

	protected $table = 'task_status';
	protected $fillable = ['name', 'category', 'completion_percentage', 'description', 'fixed', 'position'];
	protected $dates = ['deleted_at'];
	protected $revisionCreationsEnabled = true;

	public static function validate($data, $task_status = null)
	{	
		$unique_name = "unique:task_status,name";
		$category_required = "required|";

		if(isset($data['id'])) :
			$id = $data['id'];
			$unique_name = "unique:task_status,name,$id";
			$category_required = (isset($task_status) && !$task_status->fixed) ? $category_required : '';
		endif;	

		$position_ids = self::commaSeparatedIds([0,-1]);

		$rules = ["name"		=> "required|max:200|$unique_name", 
				  "description"	=> "max:65535", 
				  "position"	=> "required|integer|in:$position_ids",
				  "category"	=> $category_required . "in:open,closed",
				  "completion_percentage" => "numeric|min:0|max:100|in:0,10,20,30,40,50,60,70,80,90,100"];

		return \Validator::make($data, $rules);
	}

	public function setPermission()
	{
		return 'custom_dropdowns.task_status';
	}

	public static function getDefaultClosed()
	{
		return self::onlyClosed()->whereFixed(1)->get()->first();
	}

	public static function getDefaultOpen()
	{
		return self::onlyOpen()->orderBy('position')->get()->first();
	}

	public static function getCategoryIds($category)
	{
		return self::whereCategory($category)->pluck('id')->toArray();
	}

	public static function getSmartOrder()
	{
		return self::orderByRaw("FIELD(category, 'open', 'closed')")->orderBy('position')->get();
	}

	public static function getOptionsHtml()
	{
		$status_list = self::orderBy('position')->get();
		$options = '';

		foreach($status_list as $status) :
			$freeze = $status->category == 'closed' ? "freeze='true'" : "";
			$options .= "<option value='" . $status->id . "' relatedval='" . $status->completion_percentage . "' " . $freeze . ">" . $status->name . "</option>";
		endforeach;	

		return $options;
	}

	public static function getTableFormat()
	{
		$table = ['thead' => [['NAME', 'style' => 'min-width: 200px'], 'CATEGORY', ['COMPLETION PERCENTAGE', 'data_class' => 'center'], 'DESCRIPTION'], 'action' => self::allowAction()];
		$table['json_columns'] = table_json_columns(['sequence' => ['className' => 'reorder'], 'name', 'category', 'completion_percentage', 'description', 'action'], self::hideColumns());
		
		return $table;
	}

	public static function getTableData($request)
	{
		$data = self::orderBy('position')->get(['id', 'position', 'name', 'fixed', 'category', 'completion_percentage', 'description']);

		return \Datatables::of($data)
				->addColumn('sequence', function($task_status)
				{
					return $task_status->drag_and_drop;
				})
				->editColumn('name', function($task_status)
				{
					return $task_status->name_html;
				})
				->editColumn('category', function($task_status)
				{
					return $task_status->category_html;
				})
				->editColumn('completion_percentage', function($task_status)
				{
					return $task_status->completion_percentage . '%';
				})
				->addColumn('action', function($task_status)
				{
					$action_permission = ['edit' => permit('custom_dropdowns.task_status.edit'), 'delete' => (permit('custom_dropdowns.task_status.delete') && !$task_status->fixed)];							
					return $task_status->getCompactActionHtml('Status', null, 'admin.administration-dropdown-taskstatus.destroy', $action_permission);
				})
				->make(true);
	}

	/*
	|--------------------------------------------------------------------------
	| SCOPES
	|--------------------------------------------------------------------------
	*/
	public function scopeOnlyClosed($query)
	{
	    $query->where('category', 'closed');
	}

	public function scopeOnlyOpen($query)
	{
	    $query->where('category', 'open');
	}

	/*
	|--------------------------------------------------------------------------
	| ACCESSOR
	|--------------------------------------------------------------------------
	*/
	public function getNameHtmlAttribute()
	{
		$outcome = $this->name;

		if($this->category == 'closed' && $this->fixed == 1) :
			$closed_count = self::whereCategory('closed')->count();

			if($closed_count > 1) :
				$outcome .= " <span class='para-hint-sm'>(default closed)</span>";
			endif;	
		endif;

		return $outcome;
	}

	/*
	|--------------------------------------------------------------------------
	| RELATIONS
	|--------------------------------------------------------------------------
	*/
	// relation: hasMany
	public function tasks()
	{
		return $this->hasMany(Task::class, 'task_status_id');
	}
}