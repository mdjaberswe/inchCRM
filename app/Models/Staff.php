<?php

namespace App\Models;

use App\Models\BaseModel;
use App\Models\Traits\FinanceTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;
use Carbon\Carbon;

class Staff extends BaseModel
{
	use SoftDeletes;
	use RevisionableTrait;
	use FinanceTrait;
	
	protected $table = 'staffs';
	protected $fillable = ['first_name', 'last_name', 'image', 'title', 'employee_type', 'phone', 'birthdate', 'date_of_hire', 'fax', 'website', 'street', 'city', 'state', 'zip', 'timezone','country_code', 'settings'];
	protected $appends = ['name', 'email', 'status'];
	protected $dates = ['deleted_at'];
	protected $revisionCreationsEnabled = true;
	protected $dontKeepRevisionOf = ['image'];

	public static function validate($data)
	{	
		$unique_email = "unique:users,email";
		$role = "required|exists:roles,id,deleted_at,NULL";
		$password = "required|min:6|max:60";

		if(isset($data['id']) && isset($data['user_id'])) :
			$id = $data['id'];
			$user_id = $data['user_id'];
			$unique_email = "unique:users,email,$user_id";
			$password = "min:6|max:60";

			if(auth_staff()->id == $id) :
				$role = "exists:roles,id,deleted_at,NULL";
			endif;
		endif;	

		$rules = ["first_name"	=> "max:200",
				  "last_name"	=> "required|max:200",
				  "email"		=> "required|email|$unique_email",
				  "title"		=> "required|max:200",
				  "phone"		=> "max:200",
				  "role"		=> "$role",
				  "password"	=> "$password"];

		return \Validator::make($data, $rules);
	}

	public function setRoute()
	{
		return 'user';
	}

	public static function getAdminList($select_item = [])
	{
		$outcome = $select_item + self::orderBy('id')->get(['id', 'first_name', 'last_name'])->where('status', 1)->pluck('name', 'id')->toArray();
		return $outcome;
	}

	public static function tableHeading()
	{
		$table= ['checkbox' => auth_staff()->admin, 'action' => permit('user.edit'), 'custom_filter' => true, 'thead' => [['NAME', 'style' => 'min-width: 280px'], 'EMAIL', 'PHONE', 'LAST LOGIN', ['STATUS', 'orderable' => 'false', 'data_class' => 'center']]];
		return $table;
	}

	public static function tableJsonColumns()
	{
		$columns = ['name', 'email', 'phone', 'last_login', 'status'];

		if(auth_staff()->admin) :
			$columns = array_prepend($columns, 'checkbox');
		endif;

		if(permit('user.edit')) :
			array_push($columns, 'action');
		endif;

		return $columns;
	}

	public static function informationTypes()
	{
		$information_types = ['basic-information'	=> 'Basic Info', 
							  'address-information'	=> 'Address Information', 
							  'social-profiles'		=> 'Social Profiles', 
							  'job-information'		=> 'Job Information', 
							  'account-settings'	=> 'Account Settings', 
							  'projects'			=> 'Projects', 
							  'tasks'				=> 'Tasks', 
							  'time-cards'			=> 'Time Cards'];

		return $information_types;
	}

	public static function defaultInfoType($type = null)
	{
		if(!is_null($type) && array_key_exists($type, self::informationTypes())) :
			return $type;
		endif;	

		return 'basic-information';
	}

	public static function infoValidate($data, $infotype)
	{
		if(array_key_exists($infotype, self::informationTypes())) :
			$rules = [];

			switch($infotype) :
				case 'basic-information' :
					$rules = ['first_name'	=> 'max:200',
							  'last_name'	=> 'required|max:200',
							  'birthdate'	=> 'date',
							  'phone'		=> 'max:200',
							  'fax'			=> 'max:200',
							  'website'		=> 'max:200'];
				break;

				case 'address-information' :
					$rules = ['street'		=> 'max:200',
							  'city'		=> 'max:200',
							  'state'		=> 'max:200',
							  'zip'			=> 'max:200',
							  'country_code'=> 'exists:countries,code'];
				break;

				case 'job-information' :
					$rules = ['title'		=> 'required|max:200',
							  'date_of_hire'=> 'date',
							  'employee_type' => 'in:full_time,part_time,casual,fixed_term,probation'];	
				break;

				case 'account-settings' :
					$user_id = $data['user_id'];

					$role = "required|exists:roles,id,deleted_at,NULL";
					if(auth_staff()->id == $data['id']) :
						$role = "exists:roles,id,deleted_at,NULL";
					endif;

					$rules = ["email"		=> "required|email|unique:users,email,$user_id",
							  "role"		=> "$role",
							  "password"	=> "min:6|max:60|confirmed", 
							  "password_confirmation" => "min:6|max:60"];
				break;

				case 'social-profiles' :
					$social_media = ['facebook', 'twitter', 'googleplus', 'instagram', 'youtube', 'pinterest', 'tumblr', 'linkedin', 'skype', 'github', 'snapchat'];
					$rules = array_fill_keys($social_media, 'max:200');
				break;

				default : $rules = [];
			endswitch;

			return \Validator::make($data, $rules);
		endif;
		
		return null;	
	}	

	public static function atWhoData()
	{
		return implode(',', self::orderBy('id')->get(['id', 'first_name', 'last_name'])->where('status', 1)->pluck('name')->toArray());
	}

	public function updateInfo($data, $infotype)
	{
		if(array_key_exists($infotype, self::informationTypes())) :

			switch($infotype) :
				case 'basic-information' :
					$this->first_name = null_if_empty($data['first_name']);
					$this->last_name = $data['last_name'];
					$this->birthdate = null_if_empty($data['birthdate']);
					$this->phone = null_if_empty($data['phone']);
					$this->fax = null_if_empty($data['fax']);
					$this->website = null_if_empty($data['website']);
					$this->update();
				break;

				case 'address-information' :
					$this->street = null_if_empty($data['street']);
					$this->city = null_if_empty($data['city']);
					$this->state = null_if_empty($data['state']);
					$this->zip = null_if_empty($data['zip']);
					$this->country_code = $data['country_code'];
					$this->update();
				break;

				case 'job-information' :
					$this->title = $data['title'];
					$this->employee_type = null_if_empty($data['employee_type']);
					$this->date_of_hire = null_if_empty($data['date_of_hire']);
					$this->update();
				break;

				case 'account-settings' :
					if(!$this->edit_email) :
						return false;
					endif;

					$user = $this->user;						
					$user->email = $data['email'];

					if(isset($data['password']) && $data['password'] != '' && (!$this->super_admin || auth_staff()->super_admin)) :
						$user->password = bcrypt($data['password']);
					endif;					

					$user->update();

					if($this->edit_role && isset($data['role'])) :
						if(count($data['role'])) :
							$user->roles()->sync($data['role']);
						else :
							$user->roles()->detach();
						endif;
					endif;	
				break;

				case 'social-profiles' :
					$social_links = [];

					$social_media = ['facebook', 'twitter', 'googleplus', 'instagram', 'youtube', 'pinterest', 'tumblr', 'linkedin', 'skype', 'github', 'snapchat'];
					foreach($data as $key => $value) :						
						if(in_array($key, $social_media) && $value != '') :
							$social_links[] = ['linked_id' => $this->id, 'linked_type' => 'staff', 'media' => $key, 'data' => json_encode(['link' => $value]), 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')];
						endif;	
					endforeach;	

					$this->socialmedia()->forceDelete();
					SocialMedia::insert($social_links);
				break;

				default : return false;
			endswitch;

			return true;
		endif;	

		return false;	
	}

	public function getRealtimeDataAttribute()
	{
		$outcome = ['name'	=> str_limit($this->name, 40, '.'), 
					'title'	=> str_limit($this->title, 30, '.'), 
					'email'	=> str_limit($this->email, 50), 
					'phone'	=> str_limit($this->phone, 50),
					'admin_status' => $this->admin_html];

		return $outcome;			
	}

	/*
	|--------------------------------------------------------------------------
	| ACCESSOR
	|--------------------------------------------------------------------------
	*/
	public function getNameAttribute()
	{
	    return trim($this->attributes['first_name'] . ' ' . $this->attributes['last_name']);
	}

	public function getAvatarAttribute()
	{
		if(isset($this->image) && file_exists(storage_path($this->image))) :
			return (string)\Image::make(storage_path($this->image))->encode('data-url');
		else :
			return \Avatar::create($this->name)->toBase64();
		endif;
	}

	public function getEmailAttribute()
	{
		return $this->user->email;
	}

	public function getIdTypeAttribute()
	{
		return 'staff-' . $this->id;
	}

	public function getSuperAdminAttribute()
	{
        $seniority = User::withRole('administrator')->orderBy('created_at')->orderBy('id')->first();
        
        if($this->user->hasRole('administrator') && isset($seniority) && $this->user->id == $seniority->id) :
            return true;
        endif;

	    return false;
	}

	public static function superAdmin()
	{
        $super_admin = User::onlyStaff()->withRole('administrator')->orderBy('created_at')->orderBy('id')->first();
        
        if(isset($super_admin)) :
            return $super_admin;
        endif;

	    return null;
	}

	public function getAdminAttribute()
	{
        if($this->user->hasRole('administrator')) :
            return true;
        endif;

	    return false;
	}

	public function getLoggedInAttribute()
	{
		if(auth()->user()->id == $this->user->id) :
			return true;
		endif;

		return false;
	}

	public function getRolesListAttribute()
	{
		return $this->user->roles->pluck('id')->toArray();
	}

	public function getEditRoleAttribute()
	{
		if(!auth_staff()->admin || $this->logged_in || !$this->follow_command_rule) :
			return false;
		endif;	

		return true;
	}

	public function getEditEmailAttribute()
	{
		if((!auth_staff()->admin && !$this->logged_in) || !$this->follow_command_rule) :
			return false;
		endif;

		return true;
	}

	public function getEditCredentialAttribute()
	{
		if((auth_staff()->admin || $this->logged_in) && (!$this->super_admin || auth_staff()->super_admin)) :
			return true;
		endif;
		
		return false;
	}

	public function getEditStatusAttribute()
	{
		if(!auth_staff()->admin || $this->super_admin || $this->logged_in) :
			return false;
		endif;

		return true;
	}

	public function getFollowCommandRuleAttribute()
	{
		if($this->logged_in) :
			return true;
		endif;	

		if((!auth_staff()->admin && $this->admin) || $this->super_admin || !permit('user.edit')) :
			return false;
		endif;

		return true;
	}

	public function getCurrencyAttribute()
	{
		return Currency::getBase();
	}

	public function getCurrencyIdAttribute()
	{
		return $this->currency->id;
	}

	public function getWondealsAttribute()
	{
		return $this->owndeals->where('won', 1);
	}

	public function getSalesHtmlAttribute()
	{
		return $this->amountTotalHtml('wondeals', 'amount');
	}

	public function getAdminHtmlAttribute()
	{
		$admin = '';

		if($this->super_admin) :
			$admin = "<span class='btn btn-danger status m-left-10'>Super Admin</span> ";
		endif;

		if($this->admin == true && $this->super_admin == false) :
			$admin = "<span class='btn btn-warning status m-left-10'>Administrator</span> ";
		endif;

		return $admin;
	}

	public function getNameHtmlAttribute()
	{
		$admin = $this->admin_html;

		$tooltip = '';
		if(strlen($this->name) > 17) :
			$tooltip = "data-toggle='tooltip' data-placement='top' title='" . $this->name . "'";
		endif;	
			
		$name = "<a href='" . route('admin.user.show', $this->id) . "' class='link-type-a'>" . 
					"<img src='" . $this->avatar . "'>" . 
					"<p><span class='user-name' $tooltip>" . str_limit($this->name, 17, '.') . "</span>" . $admin . "<br><span class='shadow'>" . $this->title . "</span></p>" . 
				"</a>";

		return $name;
	}

	public function getProfileHtmlAttribute()
	{
		$tooltip = '';
		$name_css = '';
		if(strlen($this->name) > 20) :
			$tooltip = "data-toggle='tooltip' data-placement='top' title='" . $this->name . "'";
			$name_css = 'top-0';
		endif;	
			
		$profile_html = "<a href='" . route('admin.user.show', $this->id) . "' class='link-type-a sm'>" . 
							"<img src='" . $this->avatar . "'>" . 
							"<p class='$name_css'><span $tooltip>" . str_limit($this->name, 20, '.') . "</span></p>" . 
					  	"</a>";

		return $profile_html;
	}

	public function getProfilePlainHtmlAttribute()
	{
		$tooltip = '';
		if(strlen($this->name) > 17) :
			$tooltip = "title='" . $this->name . "'";
		endif;	

		$inactive = $this->user->status ? '' : "<span class='status'>Deactivated</span>";
			
		$name = "<a href='" . route('admin.user.show', $this->id) . "'>" . 
					"<img src='" . $this->avatar . "'>" . 
					"<p><span class='user-name' $tooltip>" . str_limit($this->name, 17, '.') . "</span><br><span class='shadow'>" . $this->title . "</span></p>" . 
				"</a>" . $inactive;

		return $name;
	}

	public function getProfileRenderAttribute()
	{
		$name_tooltip = '';
		if(strlen($this->name) > 50) :
			$name_tooltip = "data-toggle='tooltip' data-placement='top' title='" . $this->name . "'";
		endif;	

		$title_tooltip = '';
		if(strlen($this->title) > 50) :
			$title_tooltip = "data-toggle='tooltip' data-placement='top' title='" . $this->title . "'";
		endif;	

		$email_tooltip = '';
		if(strlen($this->email) > 50) :
			$email_tooltip = "data-toggle='tooltip' data-placement='top' title='" . $this->email . "'";
		endif;	

		$outcome = "<span class='profile'>
					    <img src='" . $this->avatar . "'>
					    <span class='info'>
					        <span class='focus' $name_tooltip>" . str_limit($this->name, 50, '.') . "</span>
					        <br>
					        <span class='shadow' $title_tooltip>" . str_limit($this->title, 50, '.') . "</span>
					        <br>
					        <span class='shadow' $email_tooltip>" . str_limit($this->email, 50, '.') . "</span>
					    </span>
					</span>";

		return $outcome;			
	}

	public function getLastLoginHtmlAttribute()
	{
		$outcome = 'Never';
		$last_login = $this->user->last_login;

		if($last_login) :
			$outcome = "<span data-toggle='tooltip' data-placement='top' title='" . $this->readableDateAmPm('last_login') . "'>" . time_short_form($last_login->diffForHumans()) . "</span>";
		endif;
		
		return $outcome;
	}

	public function getLastLoginAttribute()
	{
		return $this->user->last_login;
	}

	public function getCheckboxHtmlAttribute($css = null)
	{
		if(!auth_staff()->admin || $this->super_admin || $this->logged_in) :
			return null;
		endif;

		$checkbox_name = $this->table . '[]';
		$checkbox = "<div class='pretty danger smooth'><input class='single-row' type='checkbox' name='" . $checkbox_name . "' value='" . $this->id . "'><label><i class='mdi mdi-check'></i></label></div>";
		return $checkbox;		
	}

	public function getStatusAttribute()
	{
		return $this->user->status;
	}	

	public function getStatusHtmlAttribute($tooltip_position = null)
	{
		$disabled = '';

		if(!auth_staff()->admin || $this->super_admin || $this->logged_in) :
			$disabled = ' disabled';
		endif;

		$tooltip_position = isset($tooltip_position) ? $tooltip_position : 'top';

		$status = "<label class='switch" . $disabled . "' data-toggle='tooltip' data-placement='" . $tooltip_position . "' title='Inactive'><input type='checkbox' value='" . $this->id . "'" . $disabled . "><span class='slider round'></span></label>";
		if($this->user->status == true) :
			$status = "<label class='switch" . $disabled . "' data-toggle='tooltip' data-placement='" . $tooltip_position . "' title='Active'><input type='checkbox' value='" . $this->id . "' checked" . $disabled . "><span class='slider round'></span></label>";
		endif;

		return $status;
	}

	public function getCompactActionHtml($item, $edit_route = null, $delete_route, $action_permission = [], $common_modal = false)
	{
		$edit = '';
		$dropdown_menu = '';
		if(isset($action_permission['edit']) && $action_permission['edit'] == true) :
			$edit = "<div class='inline-action'>";

			$edit_btn = "<a class='edit' editid='" . $this->id . "'><i class='fa fa-pencil'></i></a>";			
			if($edit_route != null) :
				$edit_btn = "<a href='" . route($edit_route, $this->id) . "'><i class='fa fa-pencil'></i></a>";
			endif;

			$edit .= $edit_btn . "</div>";

			if(auth_staff()->admin || $this->logged_in) :
				$dropdown_menu .= "<li><a class='change-password' editid='" . $this->id . "'><i class='pe-7s-unlock pe-va'></i> Change Password</a></li>";
			endif;
		endif;				

		$complete_dropdown_menu = '';		

		if(isset($action_permission['delete']) && $action_permission['delete'] == true) :
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

	public function getHasNewNotificationAttribute()
	{
		$outcome = false;

		if(auth_staff()->unread_notifications_count || auth_staff()->unread_messages_count) :
			$outcome = true;
		endif;

		if((\Session::has('unread_notifications_id') && \Session::get('unread_notifications_id') == auth_staff()->unread_notifications_id) && 
		  (\Session::has('unread_messages_id') && \Session::get('unread_messages_id') == auth_staff()->unread_messages_id)) :
			$outcome = false; 
		endif;	

		\Session::put('unread_notifications_id', auth_staff()->unread_notifications_id);
		\Session::put('unread_messages_id', auth_staff()->unread_messages_id);	

		return $outcome;
	}

	public function getHasRecentSentMsgAttribute()
	{
		$now = Carbon::now()->format('Y-m-d H:i:s');
		$back_10_sec = Carbon::now()->subSeconds(10)->format('Y-m-d H:i:s');

		$recent_sent_msg_count = ChatSender::join('chat_room_members', 'chat_room_members.id', '=', 'chat_senders.chat_room_member_id')
											->where('chat_room_members.linked_type', 'staff')
											->where('chat_room_members.linked_id', $this->id)
											->where('chat_senders.created_at', '>', $back_10_sec)
											->where('chat_senders.created_at', '<', $now)
											->count();

		$outcome = $recent_sent_msg_count ? true : false;
		
		return $outcome;
	}

	public function getUnreadNotificationsCountAttribute()
	{
		return $this->notifications->where('read_at', null)->count();
	}

	public function getUnreadNotificationsIdAttribute()
	{
		return $this->notifications->where('read_at', null)->pluck('id')->toArray();
	}

	public function getUnreadMessagesCountAttribute()
	{
		$unread_count = ChatReceiver::join('chat_room_members', 'chat_room_members.id', '=', 'chat_receivers.chat_room_member_id')
									  ->where('chat_room_members.linked_type', 'staff')
									  ->where('chat_room_members.linked_id', $this->id)
									  ->whereNull('chat_receivers.read_at')
									  ->count();

		return $unread_count;							 
	}

	public function getUnreadMessagesIdAttribute()
	{
		$unread_msg_id = ChatReceiver::join('chat_room_members', 'chat_room_members.id', '=', 'chat_receivers.chat_room_member_id')
									   ->where('chat_room_members.linked_type', 'staff')
									   ->where('chat_room_members.linked_id', $this->id)
									   ->whereNull('chat_receivers.read_at')
									   ->pluck('chat_receivers.id')
									   ->toArray();

		return $unread_msg_id;					 
	}

	public function getReceivedMessagesAttribute($take = null)
	{
		$messages = ChatReceiver::join('chat_room_members', 'chat_room_members.id', '=', 'chat_receivers.chat_room_member_id')
							      ->where('chat_room_members.linked_type', 'staff')
							      ->where('chat_room_members.linked_id', $this->id)
							      ->groupBy('chat_sender_id')
							      ->latest('chat_receivers.id')
							      ->select('chat_receivers.*', 'chat_room_members.chat_room_id');

		$messages = is_null($take) ? $messages : $messages->take($take);
		$messages = $messages->get();

		return $messages;
	}


	public function getChatRoomsIdAttribute()
	{
	    $chat_rooms_id = ChatRoom::join('chat_room_members', 'chat_room_members.chat_room_id', '=', 'chat_rooms.id')
                                   ->whereLinked_type('staff')
                                   ->whereLinked_id($this->id)                                 
                                   ->groupBy('chat_rooms.id')
                                   ->pluck('chat_rooms.id')
                                   ->toArray();

        return $chat_rooms_id;                   
	}

	public function getDedicatedChatRoomsIdAttribute()
	{
	    $chat_rooms_id = ChatRoom::join('chat_room_members', 'chat_room_members.chat_room_id', '=', 'chat_rooms.id')
                                   ->whereType('dedicated')
                                   ->whereLinked_type('staff')
                                   ->whereLinked_id($this->id)                                 
                                   ->groupBy('chat_rooms.id')
                                   ->pluck('chat_rooms.id')
                                   ->toArray();

        return $chat_rooms_id;                   
	}

	public function getChatRoomsAttribute($take = null)
	{
		$chat_rooms = ChatRoom::join('chat_room_members', 'chat_room_members.chat_room_id', '=', 'chat_rooms.id')
							    ->join('chat_senders', 'chat_senders.chat_room_member_id', '=', 'chat_room_members.id')
							    ->whereIn('chat_rooms.id', $this->chat_rooms_id)
							    ->latest('chat_senders.id')						  
							    ->select('chat_rooms.id', 'chat_rooms.name', 'chat_rooms.type', 'chat_room_members.linked_id', 'chat_room_members.linked_type', 'chat_senders.created_at', 'chat_senders.message')
							    ->get()
							    ->unique('id');

		$chat_rooms = is_null($take) ? $chat_rooms : $chat_rooms->take($take);

		return $chat_rooms;				  
	}

	public function getLatestChatIdAttribute()
	{
	    $latest_chat_room = $this->chat_rooms->where('linked_type', 'staff')->where('linked_id', $this->id)->first();        
		$chat_id = is_null($latest_chat_room) ? $this->chat_rooms->first()->id : $latest_chat_room->id;

		return $chat_id;
	}

	public function getSocialDataAttribute($media = null)
	{
		$data = null;
		$social = is_null($media) ? $this->socialmedia->first() : $this->socialmedia->where('media', $media)->first();

		if(!is_null($social)) :
			$data = json_decode($social->data);
		endif;	

		return $data;
	}

	public function getSocialLinkAttribute($media, $media_url = null)
	{
		$outcome = is_null($media_url) ? 'https://www.' . $media . '.com/' : $media_url;

		$link = non_property_checker($this->getSocialDataAttribute($media), 'link');
		
		if(isset($link)) :
			if(filter_var($link, FILTER_VALIDATE_URL)) :
				$outcome = $link;
			else :	
				$outcome = $outcome . $link;
			endif;	
		endif;	

		return $outcome;
	}

	public function getRelateProjectsAttribute($sort_type = 'desc')
	{
		$own_projects = $this->ownprojects;
		$merge_projects = $own_projects->merge($this->projects)->unique('id')->sortBy('id', $sort_type);

		return $merge_projects;				    
	}

	public function getRelateTasksAttribute($sort_type = 'desc')
	{
		$own_tasks = $this->owntasks->unique('id')->sortBy('id', $sort_type);
		return $own_tasks;				    
	}

	public function getInitialRouteAttribute()
	{
		$init_modules_routes = ['module.dashboard'	=> ['module.dashboard' => 'admin.lead.index'],
								'module.report'		=> ['report.campaign' => 'admin.user.index', 'report.lead' => 'admin.user.index', 'report.account' => 'admin.user.index', 'report.project' => 'admin.user.index', 'report.sale' => 'admin.user.index', 'report.expense' => 'admin.user.index', 'report.expense_vs_income' => 'admin.user.index'],											
								'module.lead'		=> ['lead.view' => 'admin.lead.index'],						
								'module.account'	=> ['account.view' => 'admin.account.index'],	
								'module.project'	=> ['project.view' => 'admin.project.index'],
								'module.task' 		=> ['task.view' => 'admin.task.index'],
								'module.campaign'	=> ['campaign.view' => 'admin.campaign.index'],
								'module.deal'		=> ['deal.view' => 'admin.deal.index'],
								'module.sale'		=> ['sale.estimate.view' => 'admin.sale-estimate.index', 'sale.invoice.view' => 'admin.sale-invoice.index', 'sale.item.view' => 'admin.sale-item.index', 'sale.sales_funnel' => 'admin.sale-item.index'],
								'module.finance'	=> ['finance.payment.view' => 'admin.finance-payment.index', 'finance.expense.view' => 'admin.finance-expense.index'],
								'module.advanced'	=> ['advanced.goal.view' => 'admin.advanced-goal.index', 'advanced.activity_log.view' => 'admin.advanced-activity-log.index'],
								'module.settings'	=> ['settings.system' => 'admin.user.index', 'settings.email' => 'admin.user.index', 'settings.SMTP' => 'admin.user.index', 'settings.payment' => 'admin.user.index'],
								'module.custom_dropdowns'=> ['custom_dropdowns.lead_stage.view' => 'admin.administration-dropdown-leadstage.index', 'custom_dropdowns.source.view' => 'admin.administration-dropdown-source.index', 'custom_dropdowns.account_group.view' => 'admin.administration-dropdown-accountgroup.index', 'custom_dropdowns.campaign_type.view' => 'admin.administration-dropdown-campaigntype.index', 'custom_dropdowns.deal_stage.view' => 'admin.administration-dropdown-dealstage.index', 'custom_dropdowns.deal_type.view' => 'admin.administration-dropdown-dealtype.index', 'custom_dropdowns.expense_category.view' => 'admin.administration-dropdown-expensecategory.index'],
								'module.administration'=> ['administration.manage_media' => 'admin.user.index', 'administration.database_backup' => 'admin.user.index'],
								'module.user'		=> ['user.view' => 'admin.user.index'],
								'module.role'		=> ['role.view' => 'admin.role.index']];

		$initial_route = null;
		foreach($init_modules_routes as $module => $permissions_routes) :
			if(permit($module)) :
				foreach($permissions_routes as $permission => $route) :
					if(permit($permission)) :
						$initial_route = $route;
						break 2;
					endif;	
				endforeach;	
			endif;	
		endforeach;

		return $initial_route;
	}
	
	/*
	|--------------------------------------------------------------------------
	| RELATIONS
	|--------------------------------------------------------------------------
	*/
	// relation: belongsTo
	public function country()
	{
		return $this->belongsTo(Country::class, 'country_code', 'code');
	}

	// relation: belongsToMany
	public function projects()
	{
		return $this->belongsToMany(Project::class, 'project_member')->withTimestamps();
	}

	public function views()
	{
		return $this->belongsToMany(FilterView::class, 'staff_view')->withPivot('temp_params');
	}

	// relation: hasMany
	public function estimates()
	{
	    return $this->hasMany(Estimate::class, 'sale_agent');
	}

	public function invoices()
	{
	    return $this->hasMany(Invoice::class, 'sale_agent');
	}

	public function leads()
	{
	    return $this->hasMany(Lead::class, 'lead_owner');
	}

	public function contacts()
	{
	    return $this->hasMany(Contact::class, 'contact_owner');
	}

	public function ownaccounts()
	{
	    return $this->hasMany(Account::class, 'account_owner');
	}

	public function ownprojects()
	{
	    return $this->hasMany(Project::class, 'project_owner');
	}

	public function owntasks()
	{
		return $this->hasMany(Task::class, 'task_owner');
	}

	public function owncampaigns()
	{
	    return $this->hasMany(Campaign::class, 'campaign_owner');
	}

	public function owndeals()
	{
	    return $this->hasMany(Deal::class, 'deal_owner');
	}

	public function goals()
	{
		return $this->hasMany(Goal::class, 'goal_owner');
	}

	public function ownevents()
	{
	    return $this->hasMany(Event::class, 'event_owner');
	}

	public function reminders()
	{
	    return $this->hasMany(Reminder::class, 'reminder_to');
	}

	public function allows()
	{
		return $this->hasMany(AllowedStaff::class);
	}

	// relation: morphOne
	public function user()
	{
		return $this->morphOne(User::class, 'linked')->withTrashed();
	}

	// relation: morphMany
	public function rolebook()
	{
		return $this->morphMany(RoleBook::class, 'linked');
	}

	public function socialmedia()
	{
	    return $this->morphMany(SocialMedia::class, 'linked');
	}

	public function eventattendees()
	{
		return $this->morphMany(EventAttendee::class, 'linked');
	}

	public function notifications()
	{
	    return $this->morphMany(Notification::class, 'linked');
	}

	public function chatRoomMembers()
	{
	    return $this->morphMany(ChatRoomMember::class, 'linked');
	}
}