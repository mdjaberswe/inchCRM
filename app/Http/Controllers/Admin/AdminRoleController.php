<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Library\DatatablesManager;
use App\Http\Controllers\Admin\AdminBaseController;

class AdminRoleController extends AdminBaseController
{
	public function __construct()
	{
		parent::__construct();

		$this->middleware('admin:role.view', ['only' => ['index', 'roleData', 'show', 'usersList']]);
		$this->middleware('admin:role.create', ['only' => ['create', 'store']]);
		$this->middleware('admin:role.edit', ['only' => ['edit', 'update']]);
		$this->middleware('admin:role.delete', ['only' => ['destroy', 'bulkDestroy']]);
	}



	public function index()
	{
		$page = ['title' => 'Roles List', 'item' => 'Role', 'field' => 'roles', 'view' => 'admin.role', 'route' => 'admin.role', 'modal_create' => false, 'modal_edit' => false, 'modal_bulk_delete' => permit('role.delete'), 'script' => true, 'mass_del_permit' => permit('mass_delete.role')];
		$table = ['thead' => [['ROLE NAME', 'style' => 'min-width: 170px'], 'DESCRIPTION', ['TOTAL USERS', 'data_class' => 'center'], ['VIEW USERS', 'data_class' => 'center', 'orderable' => 'false']], 'checkbox' => Role::allowMassAction(), 'action' => Role::allowAction()];
		$table['json_columns'] = table_json_columns(['checkbox', 'display_name', 'description', 'total_users', 'view_users', 'action'], Role::hideColumns());

		return view('admin.role.index', compact('page', 'table'));
	}



	public function roleData(Request $request)
	{
		if($request->ajax()) :
			$roles = Role::onlyGeneral()->select('id', 'name', 'display_name', 'description', 'fixed')->orderBy('id')->get();
			return DatatablesManager::roleData($roles, $request);
		endif;
	}



	public function create()
	{
		$page = ['title' => 'Add New Role', 'item_title' => breadcrumbs_render('admin.role.index:Roles|Add New Role')];
		$permissions_groups = Permission::getPermissionsGroups();

		return view('admin.role.create', compact('page', 'permissions_groups'));
	}



	public function store(Request $request)
	{
		$data = $request->all();
		$validation = Role::validate($data);

		if($request->ajax()) :
			$status = true;
			$errors = null;			

			if($validation->fails()) :
				$status = false;
				$errors = $validation->getMessageBag()->toArray();
			endif;

			return response()->json(['status' => $status, 'errors' => $errors]);
		endif;

		if($validation->fails()) :
			return redirect()->back()->withErrors($validation);
		endif;

		$role = new Role;
		$role->name = trim_lower_snake($request->name);
		$role->display_name = $request->name;
		$role->description = $request->description;
		$role->save();

		if(count($request->permissions)) :
			$role->permissions()->attach($request->permissions);
		endif;	

		$success_message = 'Role has been created.';

		if(isset($request->add_new) && $request->add_new == 1) :
			return redirect(route('admin.role.create', [], false))->withSuccess_message($success_message);
		endif;

		return redirect(route('admin.role.show', $role->id, false))->withSuccess_message($success_message);
	}



	public function show(Role $role)
	{
		$page = ['title' => 'Role: ' . $role->display_name, 'item_title' => breadcrumbs_render('admin.role.index:Roles|' . $role->display_name)];
		$preserve = true;
		if($role->name == 'administrator') :
			$preserve = false;
		endif;	
		$permissions_groups = Permission::getPermissionsGroups($role, $preserve);

		return view('admin.role.show', compact('page', 'role', 'permissions_groups'));
	}



	public function edit(Role $role)
	{
		if($role->fixed == true) :
			return redirect()->route('admin.role.index');
		endif;
			
		$page = ['title' => 'Edit Role: ' . $role->display_name, 'item_title' => breadcrumbs_render('admin.role.index:Roles|admin.role.show,' .$role->id . ':' . $role->display_name . '|Edit')];
		$permissions_groups = Permission::getPermissionsGroups($role);

		return view('admin.role.edit', compact('page', 'role', 'permissions_groups'));
	}



	public function usersList(Request $request, Role $role)
	{
		if($request->ajax()) :
			$status = true;
			$info = null;

			if(isset($role) && isset($request->id)) :
				if($role->id == $request->id) :
					$users_list = $role->users_list_html;
				else :
					$status = false;
				endif;
			else :
				$status = false;
			endif;	

			return response()->json(['status' => $status, 'users' => $users_list]);
		endif;
	}



	public function update(Request $request, Role $role)
	{
		$data = $request->all();
		$validation = Role::validate($data);

		if($request->ajax()) :
			$status = true;
			$errors = null;			

			if($validation->fails()) :
				$status = false;
				$errors = $validation->getMessageBag()->toArray();
			endif;

			return response()->json(['status' => $status, 'errors' => $errors]);
		endif;

		if($validation->fails()) :
			return redirect()->back()->withErrors($validation);
		endif;

		if($role->fixed == true) :
			return redirect()->route('admin.role.index');
		endif;

		if($role->id != $request->id) :
			$warning_message = 'Sorry, Something went wrong! Please try again.';
			return redirect()->back()->withWarning_message($warning_message);
		endif;
			
		$role->name = trim_lower_snake($request->name);
		$role->display_name = $request->name;
		$role->description = $request->description;
		$role->save();

		if(count($request->permissions)) :
			$role->permissions()->sync($request->permissions);
		else :
			$role->permissions()->detach();
		endif;

		$success_message = 'Role has been updated.';

		return redirect(route('admin.role.show', $role->id, false))->withSuccess_message($success_message);
	}



	public function destroy(Request $request, Role $role)
	{
		if($request->ajax()) :
			$status = true;
			$standard_role = Role::whereName('standard')->whereFixed(1)->first();

			if($role->fixed == true || $role->id != $request->id || !isset($standard_role)) :
				$status = false;
			endif;

			if($status == true) :
				if($role->users->count()) :
					foreach($role->users as $user) :
						if($user->roles->count() <= 1) :
							$user->roles()->attach($standard_role->id);
						endif;	
					endforeach;	
				endif;
					
				$role->delete();
			endif;	
			
			return response()->json(['status' => $status]);
		endif;	
	}



	public function bulkDestroy(Request $request)
	{
		if($request->ajax()) :
			$roles = $request->roles;
			$standard_role = Role::whereName('standard')->whereFixed(1)->first();
			$status = true;

			if(isset($roles) && count($roles) > 0 && isset($standard_role)) :
				$query_roles = Role::whereFixed(0)->whereIn('id', $roles);

				foreach($query_roles->get() as $query_role) :
					if($query_role->users->count()) :
						foreach($query_role->users as $user) :
							if($user->roles->count() <= 1) :
								$user->roles()->attach($standard_role->id);
							endif;	
						endforeach;	
					endif;
				endforeach;

				$query_roles->delete();
			else :
				$status = false;
			endif;

			return response()->json(['status' => $status]);
		endif;
	}
}