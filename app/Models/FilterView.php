<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class FilterView extends BaseModel
{
	use SoftDeletes;
	use RevisionableTrait;

	protected $table = 'filter_views';
	protected $fillable = ['module_name', 'view_name', 'filter_params', 'visible_type', 'visible_to', 'is_fixed', 'is_default'];
	protected $appends = ['auth_can_view', 'shared_viewable', 'auth_can_edit', 'auth_can_delete'];
	protected $dates = ['deleted_at'];
	protected $revisionCreationsEnabled = true;
	protected static $valid_module = ['lead', 'contact', 'account', 'campaign', 'deal', 'estimate', 'invoice', 'task', 'event', 'call'];

	public static function getFilterViews($module)
	{
		$outcome['system_default'] = self::where('module_name', $module)->where('is_fixed', 1)->get();
		
		$outcome['my_views'] = self::where('module_name', $module)
									->where('is_fixed', 0)
									->createdByUser(auth()->user()->id)
									->select('filter_views.*')
									->get();
		
		$outcome['shared_views'] = self::where('module_name', $module)
										->where('is_fixed', 0)
										->createdByUser(auth()->user()->id, false)
										->where('visible_type', '!=', 'only_me')
										->select('filter_views.*')
										->get()
										->where('shared_viewable', true);							

		return $outcome;
	}

	public static function getCurrentFilter($module)
	{
		$current_filter = auth_staff()->views()->where('module_name', $module)->get();

		if($current_filter->count()) :
			$current_filter = $current_filter->first();
			if($current_filter->auth_can_view) :
				return $current_filter;
			endif;
		endif;

		$default_filter = self::where('module_name', $module)->where('is_default', 1)->first();
		return $default_filter;
	}

	public static function getValidModuleList()
	{
		return self::$valid_module;
	}

	public static function viewValidate($data)
	{
		$valid_modules = implode(',', self::getValidModuleList());
		$required_users = $data['visible_to'] == 'selected_users' ? 'required' : '';

		$rules = ["view_name"		=> "required|max:200",
				  "visible_to"		=> "required|in:only_me,everyone,selected_users",
				  "selected_users"	=> "$required_users|array|exists:users,linked_id,linked_type,staff,status,1,deleted_at,NULL",
				  "module"			=> "required|in:$valid_modules"];

		return \Validator::make($data, $rules);
	}

	public static function getFormattedFieldParams($request)
	{
		$data = [];
		$formated_params = [];

		if(count($request->fields)) :
			foreach($request->fields as $key => $field) :
				$condition_field = $field . '_condition';
				$data[$condition_field] = array_key_exists($key, $request->conditions) ? $request->conditions[$key] : null;
				$data[$field] = array_key_exists($key, $request->values) ? $request->values[$key] : null;
				$save_value = $data[$field];

				if($field == 'linked_type' && strpos($data[$field], '|') !== false)
				{
					$linked_data = explode('|', $data['linked_type']);
					$data['linked_id'] = $linked_data[1];
					$data['linked_type'] = $linked_data[0];
					$save_value = ['linked_type' => $linked_data[0], 'linked_id' => $linked_data[1]];
				}

				$formated_params[$field] = ['condition' => $data[$condition_field], 'value' => $save_value]; 
			endforeach;
		endif;	

		$outcome = ['formated_params' => $formated_params, 'data' => $data];

		return $outcome;
	}

	/*
	|--------------------------------------------------------------------------
	| ACCESSOR
	|--------------------------------------------------------------------------
	*/
	public function getAuthCanViewAttribute()
	{
		if($this->is_fixed) :
			return true;
		elseif($this->auth_is_creator || $this->visible_type == 'everyone') :
			return true;
		elseif(!$this->auth_is_creator && $this->visible_type == 'only_me') :
			return false;
		elseif($this->visible_type == 'selected_users' && in_array(auth_staff()->id, $this->allowed_users)) :
			return true;
		else :
			return false;			
		endif;	
	}

	public function getAuthCanEditAttribute()
	{
		if($this->is_fixed || !$this->auth_is_creator) :
			return false;
		endif;

		return true;
	}

	public function getAuthCanDeleteAttribute()
	{
		if(!$this->is_fixed && (auth_staff()->admin || $this->auth_is_creator)) :
			return true;
		endif;

		return false;
	}

	public function getSharedViewableAttribute()
	{
		if($this->visible_type == 'everyone') :
			return true;
		elseif($this->visible_type == 'selected_users') :
			return in_array(auth()->user()->id, $this->allowed_users);
		endif;

		return false;
	}

	public function getAllowedUsersAttribute()
	{
		if($this->visible_type == 'selected_users' && $this->visible_to != null) :
			return json_decode($this->visible_to, true);
		endif;
		
		return [];	
	}

	public function getAuthIsCreatorAttribute()
	{
		return ($this->created_by == auth_staff()->id);
	}

	public function getCustomViewNameAttribute()
	{
		if(!is_null($this->filter_temp_params)) :
			return true;
		endif;

		return false;
	}

	public function getFilterTempParamsAttribute()
	{
		$auth_view = $this->staffs()->where('staff_id', auth_staff()->id)->get();

		if($auth_view->count()) :
			$filter_temp_params = $auth_view->first()->pivot->temp_params;

			if(!is_null($filter_temp_params)) :
				return $filter_temp_params;
			endif;
		endif;
		
		return null;	
	}

	public function getIsCurrentAttribute()
	{
		$has_current = auth_staff()->views()->where('module_name', $this->module_name)->get();

		if($has_current->count()) :
			return ($has_current->first()->id == $this->id);
		endif;

		return ($this->is_default == 1);
	}


	public function getParamArrayAttribute()
	{
		if(is_null($this->filter_temp_params)) :
			return !is_null($this->filter_params) ? json_decode($this->filter_params, true) : [];
		endif;

		return json_decode($this->filter_temp_params, true);
	}

	public function getParamValArrayAttribute()
	{
		$outcome = [];

		if(count($this->param_array)) :
			foreach($this->param_array as $key => $info) :
				$outcome[$key . '_condition'] = $info['condition'];
				$outcome[$key] = $info['value'];

				if($key == 'linked_type' && is_array($info['value'])) :
					$outcome['linked_type'] = $info['value']['linked_type'];
					$outcome['linked_id'] = $info['value']['linked_id'];
				endif;
			endforeach;
		endif;
		
		return $outcome;	
	}

	public function getParamCountAttribute()
	{
		return count($this->param_array);
	}

	public function getParamCondition($param)
	{
		if(array_key_exists($param, $this->param_array)) :
			return $this->param_array[$param]['condition'];
		endif;

		return null;
	}

	public function getParamVal($param)
	{
		if(array_key_exists($param, $this->param_array)) :
			return $this->param_array[$param]['value'];
		endif;	

		return null;
	}

	public function getOptionHtmlAttribute()
	{
		$html = "<option value='" . $this->id . "'>" . $this->view_name . "</option>";
		return $html;
	}

	public function getActionBtnsHtmlAttribute()
	{
		$action_btns = '';

		if($this->auth_can_edit) :
			$action_btns .= "<a class='breadcrumb-action first common-edit-btn' data-toggle='tooltip' data-placement='bottom' title='Edit' data-item='view' data-url='" .  route('admin.view.edit', $this->id) . "' data-posturl='" . route('admin.view.update', $this->id) . "' editid='" . $this->id . "' modal-small='true'><i class='pe-va fa fa-pencil'></i></a>";
		endif;

		if($this->auth_can_delete) :
			$action_btns .= \Form::open(['route' => ['admin.view.destroy', $this->id], 'method' => 'delete', 'class' => 'inline-block']) .
								\Form::hidden('id', $this->id) .
								"<a class='breadcrumb-action delete last' data-toggle='tooltip' data-placement='bottom' title='Delete' data-item='view' data-associated='false' modal-sub-title='" . $this->view_name . "'><i class='pe-va mdi mdi-delete'></i></a>" .
						  	\Form::close();
		endif;		  	

		return $action_btns;
	}

	/*
	|--------------------------------------------------------------------------
	| RELATIONS
	|--------------------------------------------------------------------------
	*/
	public function staffs()
	{
		return $this->belongsToMany(Staff::class, 'staff_view')->withPivot('temp_params');
	}
}	