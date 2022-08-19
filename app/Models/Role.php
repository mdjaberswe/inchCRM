<?php

namespace App\Models;

use Zizaco\Entrust\EntrustRole;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class Role extends EntrustRole
{
	use SoftDeletes;
	use RevisionableTrait;

	protected $dates = ['deleted_at'];
	protected $revisionCreationsEnabled = true;

	public static function validate($data)
	{
		if(isset($data['id'])) :
			$id = $data['id'];
			$unique_name = "unique:roles,display_name,$id";
		else :
			$unique_name = "unique:roles,display_name";
		endif;

		$rules = ["name" 		=> "required|max:200|$unique_name",
				 "description"	=> "max:255"];

		return \Validator::make($data, $rules);
	}

	/*
	|--------------------------------------------------------------------------
	| SCOPES
	|--------------------------------------------------------------------------
	*/
	public function scopeOnlyGeneral($query)
	{
		return $query->whereLabel('general');
	}

	public function scopeOnlyClient($query)
	{
		return $query->whereLabel('client');
	}

	public function scopeClientDefault($query)
	{
		return $query->whereLabel('client')->where('name', 'LIKE', '%.view');
	}

	/*
	|--------------------------------------------------------------------------
	| ACCESSOR
	|--------------------------------------------------------------------------
	*/
	public function getCheckboxHtmlAttribute($css = null)
	{
		if($this->fixed == true) :
			return null;
		endif;

		$checkbox_name = $this->table . '[]';
		$checkbox = "<div class='pretty danger smooth'><input class='single-row' type='checkbox' name='" . $checkbox_name . "' value='" . $this->id . "'><label><i class='mdi mdi-check'></i></label></div>";
		return $checkbox;	
	}

	public function getDisplayNameHtmlAttribute()
	{
		$display_name = "<a href='" . route('admin.role.show', $this->id) . "' class='role-name'>" . $this->display_name . "</a>";
		return $display_name;
	}

	public function getTotalUsersHtmlAttribute()
	{
		$total_users_count = $this->users->count();
		$total_users_html = "<a class='like-txt role-users' rowid='" . $this->id . "'>" . $total_users_count . "</a>";
		return  $total_users_count ? $total_users_html : $total_users_count;
	}

	public function getViewUsersHtmlAttribute()
	{
		$view_users = "<a class='tbl-btn role-users' rowid='" . $this->id . "' data-toggle='tooltip' data-placement='top' title='Role&nbsp;Users'><i class='pe-7s-user pe-va lg'></i></a>";
		return $view_users;
	}

	public function getUsersListHtmlAttribute()
	{
		$html = '';

		if($this->users->count()) :
			foreach($this->users as $user) :
				$html .= "<div class='plain-list'>" . $user->linked->profile_plain_html . "</div>";
			endforeach;	
		else :
			$html .= "<p class='center-lg'><i class='pe-7s-user pe-va lg'></i><br>No users found in this role</p>";
		endif;	

		return $html;
	}

	public function getCompactActionHtml($item, $edit_route = null, $delete_route, $action_permission = [], $common_modal = false)
	{
		$edit = '';
		if(isset($action_permission['edit']) && $action_permission['edit'] == true && !$this->fixed) :
			$edit = "<div class='inline-action'>";

			$edit_btn = "<a class='edit' editid='" . $this->id . "'><i class='fa fa-pencil'></i></a>";			
			if($edit_route != null) :
				$edit_btn = "<a href='" . route($edit_route, $this->id) . "'><i class='fa fa-pencil'></i></a>";
			endif;

			$edit .= $edit_btn . "</div>";
		endif;				

		$complete_dropdown_menu = '';
		$dropdown_menu = '';

		if(isset($action_permission['delete']) && $action_permission['delete'] == true && !$this->fixed) :
			$dropdown_menu .= "<li>" .
								\Form::open(['route' => [$delete_route, $this->id], 'method' => 'delete']) .
									\Form::hidden('id', $this->id) .
									"<button type='submit' class='delete'><i class='mdi mdi-delete'></i> Delete</button>" .
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

	public static function allowAction()
	{
		if(permit('role.edit') || permit('role.delete')) :
			return true;
		endif;	

		return false;
	}

	public static function allowMassAction()
	{
		if(permit('mass_delete.role')) :
			return true;
		endif;	

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

	public static function getClientDefaultIds()
	{
		return self::clientDefault()->pluck('id')->toArray();
	}

	public static function getClientRoleMapping()
	{
		$client_roles = self::onlyClient()->get();
		$role_id_map = [];
		$role_map = [];

		foreach($client_roles as $client_role) :
			$module = str_replace('client.', '', $client_role->name);
			$module = substr($module, 0, strpos($module, '.'));
			$role_id_map[$module][] = $client_role->id;
			$role_map[$module][] = $client_role;
		endforeach;	

		$outcome = ['modules' => array_keys($role_id_map), 'role_id_map' => $role_id_map, 'role_map' => $role_map];

		return $outcome;
	}	

	public static function getClientModule()
	{
		return self::getClientRoleMapping()['modules'];
	}

	public static function getClientRoleIdMap()
	{
		return self::getClientRoleMapping()['role_id_map'];
	}

	public static function getClientRoleMap()
	{
		return self::getClientRoleMapping()['role_map'];
	}	

	/*
	|--------------------------------------------------------------------------
	| RELATIONS
	|--------------------------------------------------------------------------
	*/
	// relation: belongsToMany
	public function users()
	{
		return $this->belongsToMany(User::class)->withTimestamps();
	}

	public function permissions()
	{
		return $this->belongsToMany(Permission::class)->withTimestamps();
	}
}