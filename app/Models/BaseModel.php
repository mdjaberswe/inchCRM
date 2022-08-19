<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{
	protected $identifier;
	protected $route;
	protected $permission;
	protected $mass_permission;
	protected $action;
	protected $mass_action;
	protected $order_type;
	protected $icon;

	public function __construct(array $attributes = [])
	{
		parent::__construct($attributes);

		$this->identifier = $this->setIdentifier();
		$this->route = $this->setRoute();
		$this->permission = $this->setPermission();
		$this->mass_permission = $this->setMassPermission();
		$this->action = $this->setAction();
		$this->mass_action = $this->setMassAction();
		$this->order_type = $this->setOrderType();
		$this->icon = $this->setIcon();
	}

	public function setIdentifier()
	{
		return substr($this->table, 0, -1);
	}

	public function setRoute()
	{
		return substr($this->table, 0, -1);
	}

	public function setPermission()
	{
		return substr($this->table, 0, -1);
	}

	public function setMassPermission()
	{
		return substr($this->table, 0, -1);
	}

	public function setAction()
	{
		return ['edit', 'delete'];
	}

	public function setMassAction()
	{
		return ['mass_update', 'mass_delete'];
	}

	public function setOrderType()
	{
		return 'desc';
	}

	public function setIcon()
	{
		return module_icon($this->identifier);
	}

	public function getPrevRecordAttribute()
	{
		if($this->order_type == 'desc') :
			return self::where('id', '>', $this->id)->first();
		endif;	

		return self::where('id', '<', $this->id)->latest('id')->first();
	}

	public function getNextRecordAttribute()
	{
		if($this->order_type == 'desc') :
			return self::where('id', '<', $this->id)->latest('id')->first();
		endif;	

		return self::where('id', '>', $this->id)->first();
	}

	public function getIdentifierCallNameAttribute()
	{
		return ucfirst($this->identifier);
	}

	public function getCheckboxHtmlAttribute($css = null)
	{
		$checkbox_name = $this->table . '[]';
		$css = isset($css) ? $css : 'danger';
		$checkbox = "<div class='pretty " . $css . " smooth'><input class='single-row' type='checkbox' name='" . $checkbox_name . "' value='" . $this->id . "'><label><i class='mdi mdi-check'></i></label></div>";
		return $checkbox;		
	}

	public function getCompactActionHtml($item, $edit_route = null, $delete_route, $action_permission = [], $common_modal = false)
	{
		$edit = '';
		if(isset($action_permission['edit']) && $action_permission['edit'] == true) :
			$edit = "<div class='inline-action'>";

			$edit_attribute = "class='edit'";
			if($common_modal) :
				$edit_attribute = "class='common-edit-btn' ";
				$edit_attribute .= "data-item='" . $this->identifier . "' ";
				$edit_attribute .= "data-url='" . route('admin.' . $this->route . '.edit', $this->id) . "' ";
				$edit_attribute .= "data-posturl='" . route('admin.' . $this->route . '.update', $this->id) . "'";
			
				if(is_array($common_modal)) :
					foreach($common_modal as $attribute => $value) :
						$edit_attribute .= " $attribute='$value'";
					endforeach;	
				endif;	
			endif;	

			$edit_btn = "<a " . $edit_attribute . " editid='" . $this->id . "'><i class='fa fa-pencil'></i></a>";			
			if($edit_route != null) :
				$edit_route_param = is_null($this->parent_module) ? $this->id : [$this->id, 'parent_module' => $this->parent_module];
				$edit_btn = "<a href='" . route($edit_route, $edit_route_param) . "'><i class='fa fa-pencil'></i></a>";
			endif;

			$edit .= $edit_btn . "</div>";
		endif;				

		$complete_dropdown_menu = '';
		$edit_permission = isset($action_permission['edit']) ? $action_permission['edit'] : false;
		$dropdown_menu = $this->extendActionHtml($edit_permission);

		if(isset($action_permission['delete']) && $action_permission['delete'] == true) :
			$delete_attribute = '';
			if($common_modal) :
				$delete_attribute .= "data-item='" . $this->identifier . "'";
			endif;	
			
			$dropdown_menu .= "<li>" .
								\Form::open(['route' => [$delete_route, $this->id], 'method' => 'delete']) .
									\Form::hidden('id', $this->id) .
									\Form::hidden('delete_all', true) .
									"<button type='submit' class='delete' $delete_attribute><i class='mdi mdi-delete'></i> Delete</button>" .
					  			\Form::close() .
					  		  "</li>";
		endif;

		if(isset($dropdown_menu) && $dropdown_menu != '') :
			$complete_dropdown_menu = "<ul class='dropdown-menu'>" . $dropdown_menu . "</ul>";
		endif;	

		$toggle = 'dropdown';
		$toggle_class = '';
		$toggle_tooltip = '';		
		if(empty($edit) && empty($complete_dropdown_menu)) :
			$toggle = '';
			$toggle_class = 'disable';	
			$toggle_tooltip = "data-toggle='tooltip' data-placement='left' title='Permission&nbsp;denied'";	
		endif;
		
		if(!empty($edit) && empty($complete_dropdown_menu)) :
			$toggle_class = 'inactive';
		endif;	

		$open = "<div class='action-box $toggle_class' $toggle_tooltip>";

		$dropdown = "<div class='dropdown'>
						<a class='dropdown-toggle $toggle_class' data-toggle='" . $toggle . "'>
							<i class='fa fa-ellipsis-v'></i>
						</a>";

		$close = "</div></div>";

		$action = $open . $edit . $dropdown . $complete_dropdown_menu . $close;

		return $action;
	}

	public function extendActionHtml($edit_permission = true)
	{
		return null;
	}
	
	public function getActionHtml($item, $edit_route = null, $delete_route, $action_permission = [], $show_tooltip = null)
	{
		$edit = '';
		$delete = '';

		$edit_tooltip = "data-toggle='tooltip' data-placement='top' title='Edit " . $item . "'";
		$delete_tooltip = "data-toggle='tooltip' data-placement='top' title='Delete " . str_limit($item, 5, '.') . "'";

		if(isset($show_tooltip) && $show_tooltip == false) :
			$edit_tooltip = '';
			$delete_tooltip = '';
		endif;	

		if(isset($action_permission['edit']) && $action_permission['edit'] == true) :
			$edit = "<a class='tbl-btn edit' editid='" . $this->id . "' data-toggle='tooltip' $edit_tooltip><i class='pe-7s-eyedropper pe-va sm'></i></a>";
			if($edit_route != null) :
				$edit = "<a href='" . route($edit_route, $this->id) . "' class='tbl-btn edit' $edit_tooltip><i class='pe-7s-eyedropper pe-va sm'></i></a>";
			endif;
		endif;

		if(isset($action_permission['delete']) && $action_permission['delete'] == true) :
			$delete = \Form::open(['route' => [$delete_route, $this->id], 'method' => 'delete', 'class' => 'inline-block']) .
						\Form::hidden('id', $this->id) .
						"<button type='submit' class='tbl-btn delete' $delete_tooltip><i class='pe-7s-close pe-va lg'></i></button>" .
					  \Form::close();
		endif;		  

		$action = $edit . $delete;
		
		return $action;		  
	}

	public function getRemoveHtmlAttribute()
	{
		$remove_html = "<button type='button' class='close' data-toggle='tooltip' data-placement='top' title='Remove'><span aria-hidden='true'>&times;</span></button>";
		return $remove_html;
	}

	public function getShowRouteAttribute($param = null)
	{
		$param = is_null($param) ? $this->id : $param;
		return route('admin.' . $this->route . '.show', $param);
	}

	public function getNameHtmlAttribute()
	{
		return "<a href='" . $this->show_route . "'>" . $this->name . "</a>";
	}

	public function getNameIconHtmlAttribute()
	{
		$icon = method_exists($this, 'getIconAttribute') ? $this->getIconAttribute() : $this->icon;
		return "<a href='" . $this->show_route . "' class='link-icon'><span class='icon $icon' data-toggle='tooltip' data-placement='top' title='" . ucfirst($this->identifier) . "'></span> " . $this->name . "</a>";
	}

	public function getNameLinkAttribute()
	{
		return "<a href='" . $this->show_route . "' class='like-txt'>" . $this->name . "</a>";
	}

	public function getNameLinkIconAttribute()
	{
		return "<a href='$this->show_route' class='like-txt'><span class='icon $this->icon' data-toggle='tooltip' data-placement='top' title='" . ucfirst($this->identifier) . "'></span> $this->name</a>";
	}

	public function getShowLinkAttribute()
	{
		return "<a href='" . $this->show_route . "'>" . $this->name . "</a>";
	}

	public function getHasSocialMediaAttribute()
	{
		return in_array($this->identifier, ['lead', 'contact', 'account']);
	}

	public static function last()
	{
		return self::latest('id')->first();
	}

	public function sqlDate($date)
	{
		if(!is_null($this->$date)) :
			$sql_date = is_object($this->$date) ? $this->$date->format('Y-m-d H:i:s') : date('Y-m-d H:i:s', strtotime($this->$date));
			return $sql_date;
		endif;
		
		return null;	
	}

	public function dateTimestamp($date)
	{
		return strtotime($this->sqlDate($date));
	}	

	public function readableDateAmPm($date)
	{
		if(isset($this->$date)) :
			return is_object($this->$date) ? $this->$date->format('h:i A, M j, Y') : date('h:i A, M j, Y', strtotime($this->$date));
		endif;
		
		return null;	
	}

	public function readableDate($date)
	{
		if(isset($this->$date)) :
			return is_object($this->$date) ? $this->$date->format('M j, Y') : date('M j, Y', strtotime($this->$date));
		endif;
		
		return null;	
	}

	public function carbonDate($date)
	{
		return new Carbon($this->$date);
	}

	public function passedDateVal($date)
	{
		$date_obj = is_object($this->$date) ? $this->$date : $this->carbonDate($date);
		return $date_obj->diffInDays(Carbon::now(), false);
	}

	public function readableDateHtml($date, $time = false)
	{
		$html = '';

		if(isset($this->$date)) :
			$readable_date = is_object($this->$date) ? $this->$date->format('M j, Y') : date('M j, Y', strtotime($this->$date));
			$html .= "<span>" . no_space($readable_date) . "</span>";
			if($time == true) :
				$readable_time = is_object($this->$date) ? $this->$date->format('h:i:s a') : date('h:i:s a', strtotime($this->$date));
				$html .= '<br>';
				$html .= "<span class='shadow'>" . $readable_time . "</span>";
			endif;	
		endif;

		return $html;
	}

	public static function commaSeparatedIds($push = null)
	{
		$ids = self::get(['id'])->pluck('id')->toArray();

		if(is_array($push)) :
			foreach($push as $single_push) :
				array_push($ids, $single_push);
			endforeach;			
		endif;	
		
		$ids = implode(',', $ids);

		return $ids;
	}

	public function getCreatedAmpmAttribute()
	{
		return $this->created_at->format('Y-m-d h:i:s a');
	}

	public function getUpdatedAmpmAttribute()
	{
		return $this->updated_at->format('Y-m-d h:i:s a');
	}

	public function getModifiedAtAttribute()
	{
		return $this->updated_at;
	}

	public function getDeletedAmpmAttribute()
	{
		return $this->deleted_at->format('Y-m-d h:i:s a');
	}

	public function getCreatedShortFormatAttribute()
	{
		if($this->created_at->format('Y') == date('Y')) :
			return $this->created_at->format('M j');
		endif;
		
		return $this->created_at->format('Y-m-d');
	}

	public function getCreatedTimeAmpmAttribute()
	{
		return $this->created_at->format('h:i:s a');
	}

	public function createdBy()
	{
		$first = $this->revisionHistory->first();

		if(isset($first) && $first->key == 'created_at') :
			return $first->userResponsible();
		endif;

		return Staff::superAdmin();
	}

	public function updatedBy()
	{
		$last = $this->revisionHistory->last();
		
		if(isset($last)) :
			return $last->userResponsible();
		endif;

		return Staff::superAdmin();
	}

	public function getCreatedByAttribute()
	{
		return $this->createdBy()->linked_id;
	}

	public function createdByName()
	{
		$name = is_null($this->createdBy()) ? null : $this->createdBy()->linked->name;
		return $name;
	}

	public function createdByNameLink()
	{
		$link = is_null($this->createdBy()) ? null : $this->createdBy()->linked->name_link;
		return $link;
	}

	public function createdByProfile()
	{
		$profile = is_null($this->createdBy()) ? null : $this->createdBy()->linked->profile_html;
		return $profile;
	}

	public function getModifiedByAttribute()
	{
		return $this->updatedBy()->linked_id;
	}

	public function updatedByName()
	{
		$name = is_null($this->updatedBy()) ? null : $this->updatedBy()->linked->name;
		return $name;
	}

	public function createdByAvatar()
	{
		$avatar = is_null($this->createdBy()) ? null : $this->createdBy()->linked->avatar;
		return $avatar;
	}

	public static function getRoute()
    {
        return with(new static)->route;
    }

	public static function getPermission()
    {
        return with(new static)->permission;
    }

	public static function getMassPermission()
    {
        return with(new static)->mass_permission;
    }

	public static function getAction()
    {
        return with(new static)->action;
    }

	public static function getMassAction()
    {
        return with(new static)->mass_action;
    }

    public static function allowAction()
    {
    	$actions = self::getAction();
    	$basic = ['edit', 'delete'];    
    	$permission_key = self::getPermission();	 

    	foreach($actions as $action) :
    		$permission = in_array($action, $basic) ? $permission_key . '.' . $action : $action . '.' . $permission_key;

    		if(permit($permission)) :
    			return true;
    		endif;	
    	endforeach;

    	return false;
    }

    public static function allowMassAction()
    {
    	$actions = self::getMassAction();
    	$permission_key = self::getMassPermission();   

    	foreach($actions as $action) :
    		$permission = $action . '.' . $permission_key;

    		if(permit($permission)) :
    			return true;
    		endif;	
    	endforeach;

    	return false;
    }

    public static function hideColumns()
    {
    	$hide_columns = [];

    	if(!self::allowAction()) :
    		$hide_columns[] = 'action';
    	endif;

    	if(!self::allowMassAction()) :
    		$hide_columns[] = 'checkbox';
    	endif;

    	return $hide_columns;	 	
    }

    public function scopeFilterViewData($query)
    {
    	$filter_view = FilterView::getCurrentFilter($this->identifier);

    	if(count($filter_view->param_array)) :
    		foreach($filter_view->param_array as $attribute => $condition_val) :
    			$condition = $condition_val['condition'];
    			$conditional_value = $condition_val['value'];

    			if(strpos($attribute, 'owner') !== FALSE && in_array('0', $conditional_value)) :
    				$param_key = array_search('0', $conditional_value);
    				$conditional_value[$param_key] = auth_staff()->id;
    			endif;	

    			$query = conditional_filter_query($query, $attribute, $condition, $conditional_value);
    		endforeach;	
    	endif;

    	return $query;
    }

    public function scopeCreatedByUser($query, $user_id, $created_by_user = true)
    {
    	$identifier = $this->identifier;
    	$user_condition = $created_by_user ? '=' : '!=';

    	return $query->leftjoin('revisions', $this->table . '.id', '=', 'revisions.revisionable_id')
					 ->where(function($query) use ($identifier, $user_id, $user_condition)
				      {
						$query->whereRevisionable_type($identifier)
							  ->where('user_id', $user_condition, $user_id)
							  ->wherekey('created_at');
				      });
    }	
}