<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Role;
use App\Models\Staff;
use App\Models\Country;
use App\Models\ChatRoom;
use App\Models\ChatSender;
use App\Models\ChatReceiver;
use App\Models\AllowedStaff;
use App\Models\ChatRoomMember;
use App\Events\UserCreated;
use Illuminate\Http\Request;
use App\Library\DatatablesManager;
use App\Http\Controllers\Admin\AdminBaseController;

class AdminUserController extends AdminBaseController
{
	public function __construct()
	{
		parent::__construct();

		$this->middleware('admin:user.view', ['only' => ['index', 'userData', 'show']]);
		$this->middleware('admin:user.create', ['only' => ['store']]);
		$this->middleware(['admin:user.edit', 'command.chain:edit'], ['only' => ['edit', 'update', 'updatePassword', 'updateStatus']]);
		$this->middleware('command.chain:edit', ['only' => ['updateImage', 'updateUserInfo']]);
		$this->middleware(['admin:user.delete', 'command.chain:delete'], ['only' => ['destroy']]);
	}



	public function index()
	{
		$page = ['title' => 'Users List', 'item' => 'User', 'field' => 'staffs', 'view' => 'admin.user', 'route' => 'admin.user', 'permission' => 'user', 'script' => true, 'add_icon' => 'fa fa-user-plus', 'bulk' => 'status:active|inactive', 'mass_del_permit' => permit('mass_delete.user')];
		$table= Staff::tableHeading();
		$table['json_columns'] = table_json_columns(Staff::tableJsonColumns());
		$table['filter_input'] = ['status' => ['type' => 'dropdown', 'options' => [1 => 'Active Users', 0 => 'Inactive Users']]];

		return view('admin.user.index', compact('page', 'table'));
	}



	public function userData(Request $request)
	{
		if($request->ajax()) :
			$staffs = Staff::orderBy('id')->get()->sortByDesc('super_admin')->sortByDesc('admin');
			return DatatablesManager::userData($staffs, $request);
		endif;
	}



	public function indexProfile(Request $request)
	{
		$page = ['title' => 'User Profiles', 'item' => 'User', 'modal_edit' => false, 'modal_delete' => false, 'modal_bulk_update' => false, 'modal_bulk_delete' => false];
		$staffs = Staff::orderBy('id')->get()->sortByDesc('super_admin')->sortByDesc('admin');
		$staffs = collection_paginator($staffs, 'admin.user.profilecard', 50, $request->has('page') ? $request->get('page') : null);

		return view('admin.user.index-profile', compact('page', 'staffs'));
	}



	public function store(Request $request)
	{
		if($request->ajax()) :
			$response = ['status' => true, 'errors' => null];
			$data = $request->all();
			$data['staffs_id'] = Staff::pluck('id')->toArray();
			$validation = Staff::validate($data);

			if($validation->passes()) :
				$staff = new Staff;
				$staff->first_name = $request->first_name;
				$staff->last_name  = $request->last_name;
				$staff->title = $request->title;
				$staff->phone = $request->phone;				
				$staff->save();

				$user = new User;				
				$user->email = $request->email;				
				$user->password = bcrypt($request->password);
				$user->linked_id = $staff->id;
				$user->linked_type = 'staff';
				$user->save();
				$user->roles()->attach($request->role);	

				flush_response($response);
				event(new UserCreated($staff, $data));
			else :
				$response['status'] = false;
				$response['errors'] = $validation->getMessageBag()->toArray();
			endif;

			return response()->json($response);
		endif;
	}



	public function show(Request $request, Staff $staff, $infotype = null)
	{
		$page = ['title' => 'User: ' . $staff->first_name . ' ' . $staff->last_name, 'item' => 'User', 'view' => 'admin.user', 'tabs' => ['list' => Staff::informationTypes(), 'default' => Staff::defaultInfoType($infotype), 'item_id' => $staff->id, 'url' => 'user-info']];		
		$input = ['class' => !$staff->follow_command_rule ? 'only-view' : null, 'readonly' => !$staff->follow_command_rule, 'disabled_role' => !$staff->edit_role, 'role_class' => !$staff->edit_role ? 'only-view' : null, 'readonly_email' => !$staff->edit_email, 'email_class' => !$staff->edit_email ? 'only-view' : null];
		return view('admin.user.show', compact('page', 'staff', 'input'));
	}



	public function edit(Request $request, Staff $staff)
	{
		if($request->ajax()) :
			$status = true;
			$info = null;

			if(isset($staff) && isset($request->id)) :
				if($staff->id == $request->id) :
					$info = $staff->toArray();
					$info['email'] = $staff->email;
					$info['role[]'] = $staff->roles_list;

					$info['freeze'] = [];

					if(auth_staff()->admin == false && isset($staff) && $staff->logged_in == false) :
						$info['freeze'][] = 'email';
					endif;

					if(auth_staff()->admin == false || isset($staff) && $staff->logged_in == true) :
						$info['freeze'][] = 'role[]';
					endif;

					$info = (object)$info;
				else :
					$status = false;
				endif;
			else :
				$status = false;
			endif;	

			return response()->json(['status' => $status, 'info' => $info]);
		endif;

		return redirect()->route('admin.user.index');
	}



	public function update(Request $request, Staff $staff)
	{
		if($request->ajax()) :
			$status = true;
			$errors = null;
			$data = $request->all();
			$data['user_id'] = $staff->user->id;

			if(isset($staff) && isset($request->id) && $staff->id == $request->id) :
				$validation = Staff::validate($data);
				if($validation->passes()) :
					$staff->first_name = $request->first_name;
					$staff->last_name  = $request->last_name;
					$staff->title = $request->title;
					$staff->phone = $request->phone;				
					$staff->save();

					if(auth_staff()->admin || $staff->logged_in) :
						$user = $staff->user;		
						$user->email = $request->email;				
						$user->save();
					endif;	

					if(auth_staff()->admin && !$staff->logged_in) :
						if(count($request->role)) :
							$user->roles()->sync($request->role);
						else :
							$user->roles()->detach();
						endif;
					endif;
				else :
					$status = false;
					$errors = $validation->getMessageBag()->toArray();
				endif;
			else :
				$status = false;
			endif;	

			return response()->json(['status' => $status, 'errors' => $errors, 'saveId' => $request->id]);
		endif;
	}



	public function updatePassword(Request $request, Staff $staff)
	{
		if($request->ajax()) :
			$status = true;
			$errors = null;
			$data = $request->all();

			if(isset($staff) && isset($request->id) && $staff->id == $request->id && (auth_staff()->admin || $staff->logged_in)) :
				$rules = ['password' => 'required|min:6|max:60|confirmed', 'password_confirmation' => 'required|min:6|max:60'];
				$validation = \Validator::make($data, $rules);
				if($validation->passes() && (!$staff->super_admin || auth_staff()->super_admin)) :
					$user = $staff->user;		
					$user->password = bcrypt($request->password);			
					$user->save();
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



	public function updateStatus(Request $request, Staff $staff)
	{
		if($request->ajax()) :
			$status = false;
			$checked = null;

			if(isset($staff) && isset($request->id) && $staff->id == $request->id && isset($request->checked) && auth_staff()->admin && !$staff->super_admin && !$staff->logged_in) :
				$checked = $request->checked ? 1 : 0;
				$staff->user->update(['status' => $checked]);
				$status = true;
			endif;

			return response()->json(['status' => $status, 'checked' => $checked]);
		endif;	
	}



	public function updateImage(Request $request, Staff $staff)
	{
		if($request->ajax()) :
			$status = false;
			$id = null;
			$avatar = null;
			$avatar_border = null;
			$errors = null;
			$data = $request->all();

			if(isset($staff) && isset($request->id) && $staff->id == $request->id && $staff->follow_command_rule) :
				$rules = ['image' => 'required|image|mimetypes:image/webp,image/jpeg,image/png,image/jpg,image/gif|max:3072'];
				$error_messages = ['mimetypes' => ' The image must be a file of type: jpeg, png, gif, webp. '];
				$validation = \Validator::make($data, $rules, $error_messages);
				if($validation->passes()) :
					$filename = time() . '_' . str_random(10) . '_staff_' . $staff->id . '.png';

					$upload_directory = 'app/users/';
					$storage_path = storage_path($upload_directory);
					if(!file_exists($storage_path)) :
						mkdir($storage_path, 0777, true);
					endif;

					$upload_path = $upload_directory . $filename;
					\Image::make($request->image)->fit(200, 200)->save(storage_path($upload_path));

					\Storage::disk('base')->delete($staff->image);

					$staff->image = $upload_path;
					$staff->update();

					$avatar = "<img src='" . $staff->avatar . "' alt='" . $staff->last_name . "' class='img-type-a'>";
					$avatar_border = "<img src='" . $staff->avatar . "' alt='" . $staff->last_name . "' class='img-type-a border'>";
					$id = $request->id;
					$status = true;
				else :
					$errors = $validation->getMessageBag()->toArray();		
				endif;	
			endif;

			return response()->json(['status' => $status, 'id' => $id, 'avatar' => $avatar, 'avatarborder' => $avatar_border, 'errors' => $errors]);
		endif;	
	}



	public function userInfo(Request $request, Staff $staff, $infotype)
	{
		if($request->ajax()) :			
			if(isset($staff) && isset($request->id) && $staff->id == $request->id && isset($request->type) && $infotype == $request->type && array_key_exists($infotype, Staff::informationTypes())) :
				$input = ['class' => !$staff->follow_command_rule ? 'only-view' : null, 'readonly' => !$staff->follow_command_rule, 'disabled_role' => !$staff->edit_role, 'role_class' => !$staff->edit_role ? 'only-view' : null, 'readonly_email' => !$staff->edit_email, 'email_class' => !$staff->edit_email ? 'only-view' : null];
				return view('admin.user.partials.tabs.tab-' . $infotype, compact('staff', 'input'));
			endif;

			return null;
		endif;	
	}



	public function updateUserInfo(Request $request, Staff $staff, $infotype)
	{
		if($request->ajax()) :
			$status = true;
			$errors = null;
			$data = $request->all();
			$data['user_id'] = $staff->user->id;
			$realtime_data = [];

			if(isset($staff) && isset($request->id) && $staff->id == $request->id && isset($request->type) && $infotype == $request->type && array_key_exists($infotype, Staff::informationTypes())) :
				$validation = Staff::infoValidate($data, $infotype);
				
				if(isset($validation) && $validation->passes()) :
					$status = $staff->updateInfo($data, $infotype);
					$realtime_data = $staff->realtime_data;
				else :
					$status = false;
					$errors = isset($validation) ? $validation->getMessageBag()->toArray() : null;
				endif;
			else :
				$status = false;
			endif;	

			return response()->json(['status' => $status, 'errors' => $errors, 'realtime' => $realtime_data]);
		endif;
	}	



	public function allowedUserData(Request $request)
	{
		if($request->ajax()) :
			$status = false;
			$html = '';
			$rules = ["staffs" => "required|exists:users,linked_id,linked_type,staff,status,1,deleted_at,NULL",
					  "serial" => "required|integer|min:0"]; 
			$validation = \Validator::make($request->all(), $rules);

			if($validation->passes()) :
				$serial = $request->serial;

				foreach($request->staffs as $id) :
					$staff = Staff::find($id);

					$html .= "
					<tr data-staff='" . $staff->id . "'>
					    <td>" . ++$serial . "</td>
					    <td>" . $staff->profile_render . "</td>
					    <td>
					    	<input type='hidden' name='allowed_staffs[]' value='" . $staff->id . "'>
					        <span class='pretty single info smooth'>
					            <input type='checkbox' name='can_read_" . $staff->id . "' value='1' checked disabled>
					            <label><i class='mdi mdi-check'></i></label>
					        </span> 
					    </td>
					    <td>
					        <span class='pretty single info smooth'>
					            <input type='checkbox' name='can_write_" . $staff->id . "' value='1'>
					            <label><i class='mdi mdi-check'></i></label>
					        </span> 
					    </td>
					    <td>
					        <span class='pretty single info smooth'>
					            <input type='checkbox' name='can_delete_" . $staff->id . "' value='1'>
					            <label><i class='mdi mdi-check'></i></label>
					        </span> 
					    </td>
					    <td>
					        <button type='button' class='close' data-toggle='tooltip' data-placement='top' title='Remove'><span aria-hidden='true'>&times;</span></button>
					    </td>
					</tr>";
				endforeach;	

				$status = true;	
			endif;

			return response()->json(['status' => $status, 'html' => $html]);
		endif;	
	}



	public function allowedTypeData(Request $request, $type, $id)
	{
		if($request->ajax()) :
			$status = false;
			$html = '';

			if(isset($type) && isset($request->type) && $type == $request->type && isset($id) && isset($request->id) && $id == $request->id) :
				$type_model = morph_to_model($type)::find($id);
				$table = $type . 's';
				$valid_types = AllowedStaff::getValidTypes();
				$rules = ["id" => "required|exists:$table,id,deleted_at,NULL",
						  "type" => "required|in:$valid_types"];
				$validation = \Validator::make($request->all(), $rules);				

				if($validation->passes() && isset($type_model)) :
					foreach($type_model->allowedstaffs as $key => $allowed) :
						$can_edit = $allowed->can_edit ? 'checked' : '';
						$can_delete = $allowed->can_delete ? 'checked' : '';

						$html .= "
						<tr data-staff='" . $allowed->staff->id . "'>
						    <td>" . ++$key . "</td>
						    <td>" . $allowed->staff->profile_render . "</td>
						    <td>
						    	<input type='hidden' name='allowed_staffs[]' value='" . $allowed->staff->id . "'>
						        <span class='pretty single info smooth'>
						            <input type='checkbox' name='can_read_" . $allowed->staff->id . "' value='1' checked disabled>
						            <label><i class='mdi mdi-check'></i></label>
						        </span> 
						    </td>
						    <td>
						        <span class='pretty single info smooth'>
						            <input type='checkbox' name='can_write_" . $allowed->staff->id . "' value='1' $can_edit>
						            <label><i class='mdi mdi-check'></i></label>
						        </span> 
						    </td>
						    <td>
						        <span class='pretty single info smooth'>
						            <input type='checkbox' name='can_delete_" . $allowed->staff->id . "' value='1' $can_delete>
						            <label><i class='mdi mdi-check'></i></label>
						        </span> 
						    </td>
						    <td>
						        <button type='button' class='close' data-toggle='tooltip' data-placement='top' title='Remove'><span aria-hidden='true'>&times;</span></button>
						    </td>
						</tr>";
					endforeach;	

					$status = true;	
				endif;
			endif;	

			return response()->json(['status' => $status, 'html' => $html]);
		endif;	
	}



	public function postAllowedUser(Request $request, $type, $id)
	{
		if($request->ajax()) :
			$status = false;
			$html = null;
			$updated_by = null;

			if(isset($id) && isset($request->id) && $id == $request->id && isset($type) && isset($request->type) && $type == $request->type) :
				$type_model = morph_to_model($type)::find($id);
				$table = $type . 's';
				$valid_types = AllowedStaff::getValidTypes();
				$rules = ["allowed_staffs" => "exists:users,linked_id,linked_type,staff,status,1,deleted_at,NULL",
						  "id" => "required|exists:$table,id,deleted_at,NULL",
						  "type" => "required|in:$valid_types"];
				$validation = \Validator::make($request->all(), $rules);				

				if($validation->passes() && isset($type_model)) :
					$type_model->allowedstaffs()->forceDelete();
					if(isset($request->allowed_staffs)) :
						foreach($request->allowed_staffs as $staff_id) :
							$can_write = 'can_write_' . $staff_id;
							$can_delete = 'can_delete_' . $staff_id;

							$allowed_staff = new AllowedStaff;
							$allowed_staff->staff_id = $staff_id;
							$allowed_staff->linked_id = $id;
							$allowed_staff->linked_type = $type;
							$allowed_staff->can_edit = isset($request->$can_write) ? 1 : 0;
							$allowed_staff->can_delete = isset($request->$can_delete) ? 1 : 0;
							$allowed_staff->save();
						endforeach;
					endif;
					$html = $type_model->access_html;
					$updated_by = "<p class='compact'>" . $type_model->updatedByName() . "<br><span class='c-shadow sm'>" . $type_model->updated_ampm . "</span></p>";
					$status = true;	
				endif;
			endif;	

			return response()->json(['status' => $status, 'html' => $html, 'updatedBy' => $updated_by]);
		endif;	
	}



	public function projectData(Request $request, Staff $staff)
	{
		if($request->ajax()) :
			$projects = $staff->relate_projects;
			return DatatablesManager::projectDisplayData($projects, $request);
		endif;
	}



	public function taskData(Request $request, Staff $staff)
	{
		if($request->ajax()) :
			$tasks = $staff->relate_tasks;
			return DatatablesManager::taskDisplayData($tasks, $request);
		endif;
	}



	public function destroy(Request $request, Staff $staff)
	{
		if($request->ajax()) :
			$status = true;

			if($staff->id != $request->id || !auth_staff()->admin || $staff->logged_in || $staff->super_admin) :
				$status = false;
			endif;

			if($status == true) :
				$staff->user->update(['status' => 0]);
				$staff->delete();
				$staff->user->delete();
			endif;
			
			return response()->json(['status' => $status]);
		endif;
	}



	public function bulkDestroy(Request $request)
	{
		if($request->ajax()) :
			$staffs = $request->staffs;

			$status = true;

			if(isset($staffs) && count($staffs) > 0) :				
				foreach($staffs as $staff_id) :
					$staff = Staff::find($staff_id);
					if(isset($staff) && auth_staff()->admin && !$staff->super_admin && !$staff->logged_in) :
						$staff->user->update(['status' => 0]);
						$staff->delete();
						$staff->user->delete();
					endif;
				endforeach;
			else :
				$status = false;
			endif;

			return response()->json(['status' => $status]);
		endif;
	}



	public function bulkStatus(Request $request)
	{
		if($request->ajax()) :
			$staffs = $request->staffs;
			$bulk_status = $request->status;

			$status = true;
			$bulk_status_array = ['active', 'inactive'];			

			if(isset($staffs) && count($staffs) > 0 && isset($bulk_status) && in_array($bulk_status, $bulk_status_array)) :	
				foreach($staffs as $staff_id) :
					$staff = Staff::find($staff_id);
					if(isset($staff) && auth_staff()->admin && !$staff->super_admin && !$staff->logged_in) :
						$staff->user->update(['status' => $bulk_status == 'active' ? 1 : 0]);
					endif;
				endforeach;	
			else :
				$status = false;
			endif;

			return response()->json(['status' => $status]);
		endif;
	}



	public function message(Request $request)
	{
		if($request->ajax()) :
			$status = true;
			$errors = null;
			$data = $request->all();
			$auth_staff_id = auth_staff()->id;

			$rules = ["receiver" => "required|exists:users,linked_id,linked_id,!$auth_staff_id,linked_type,staff,status,1,deleted_at,NULL", "message" => "required|max:65535"];
			$validation = \Validator::make($data, $rules);

			if($validation->passes()) :
				foreach($request->receiver as $receiver) :
					$sender_chat_rooms_id = auth_staff()->dedicated_chat_rooms_id;
					$receiver = (int)$receiver;
					
					if($receiver != $auth_staff_id) :
						$chat_room = ChatRoom::join('chat_room_members', 'chat_room_members.chat_room_id', '=', 'chat_rooms.id')
										   	 ->whereIn('chat_rooms.id', $sender_chat_rooms_id)
										   	 ->whereLinked_type('staff')
										   	 ->whereLinked_id($receiver)
										   	 ->select('chat_rooms.*')
										   	 ->first();

						if(isset($chat_room)) :
							$sender_member_id = $chat_room->members->where('linked_id', auth_staff()->id)->first()->id;
							$receiver_member_id = $chat_room->members->where('linked_id', $receiver)->first()->id;

							$chat_sender = new ChatSender;
							$chat_sender->chat_room_member_id = $sender_member_id;
							$chat_sender->message = $request->message;
							$chat_sender->save();

							$chat_receiver = new ChatReceiver;
							$chat_receiver->chat_sender_id = $chat_sender->id;
							$chat_receiver->chat_room_member_id = $receiver_member_id;
							$chat_receiver->save();
						endif;	
					endif;									   
				endforeach;	
			else :
				$status = false;
				$errors = $validation->getMessageBag()->toArray();
			endif;

			return response()->json(['status' => $status, 'errors' => $errors]);
		endif;
	}
}