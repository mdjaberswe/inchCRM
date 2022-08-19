<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\BaseModel;
use App\Models\Traits\OwnerTrait;
use App\Models\Traits\ModuleTrait;
use App\Models\Traits\ClientTrait;
use App\Models\Traits\FinanceTrait;
use App\Models\Traits\HierarchyTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;
use Avatar;

class Contact extends BaseModel
{
	use SoftDeletes;
	use OwnerTrait;	
	use ModuleTrait;
	use ClientTrait;	
	use FinanceTrait;
	use HierarchyTrait;
	use RevisionableTrait;
	
	protected $table = 'contacts';
	protected $fillable = ['parent_id', 'contact_owner', 'account_id', 'first_name', 'last_name', 'title', 'phone', 'fax', 'website', 'image', 'date_of_birth', 'street', 'city', 'state', 'zip', 'country_code', 'timezone', 'source_id', 'currency_id', 'annual_revenue', 'contact_type_id', 'access', 'description'];
	protected $appends = ['status', 'account_name', 'deal_role', 'deal_access', 'project_role', 'project_access', 'estimate_role', 'estimate_access', 'invoice_role', 'invoice_access'];
	protected $dates = ['deleted_at'];
	protected $hierarchy_child = 'child';
	protected $revisionCreationsEnabled = true;
	protected $dontKeepRevisionOf = ['image'];
	protected static $fieldlist = ['access' => 'Access', 'account_id' => 'Account', 'annual_revenue' => 'Annual Revenue', 'city' => 'City', 'contact_owner' => 'Contact Owner', 'contact_type_id' => 'Contact Type', 'country_code' => 'Country', 'currency_id' => 'Currency', 'date_of_birth' => 'Date of Birth', 'description' => 'Description', 'email' => 'Email', 'facebook' => 'Facebook', 'fax' => 'Fax', 'first_name' => 'First Name', 'title' => 'Job Title', 'last_name' => 'Last Name', 'password' => 'Password', 'phone' => 'Phone', 'parent_id' => 'Reporting To', 'skype' => 'Skype', 'source_id' => 'Source', 'state' => 'State', 'status' => 'Status', 'street' => 'Street', 'twitter' => 'Twitter', 'website' => 'Website', 'zip' => 'Zip Code'];
	protected static $mass_fieldlist = ['contact_owner', 'account_id', 'parent_id', 'title', 'status', 'phone', 'fax', 'website', 'facebook', 'twitter', 'skype', 'street', 'city', 'state', 'zip', 'country_code', 'source_id', 'annual_revenue', 'contact_type_id', 'description', 'access'];

	public static function validate($data)
	{	
		$unique_email = "unique:users,email";
		$password = "required|min:6|max:60";
		$owner_required = "required";
		$client_roles = Role::getClientRoleIdMap();
		$account_id = array_key_exists('account_id', $data) ? $data['account_id'] : 0;
		$not_parent_id = '';

		if(isset($data['id'])) :
			$user_id = $data['user_id'];
			$unique_email = "unique:users,email,$user_id";
			$password = "min:6|max:60";
			$owner_required = $data['change_owner'] ? "required" : '';
			$not_parent_id = 'not_in:' . $data['id'];
		endif;	

		$rules=["contact_owner" => "$owner_required|exists:users,linked_id,linked_type,staff,status,1,deleted_at,NULL",
				"account_id"	=> "required|exists:accounts,id,deleted_at,NULL",	
				"supervisor"	=> "$not_parent_id|exists:contacts,id,account_id,$account_id,deleted_at,NULL",	
				"first_name"	=> "max:200",
				"last_name"		=> "required|max:200",
				"title"			=> "max:200",
				"email"			=> "required|email|$unique_email",
				"password"		=> "$password",
				"phone"			=> "max:200",	
				"fax"			=> "max:200",			
				"street"		=> "max:200",
				"city"			=> "max:200",
				"state"			=> "max:200",
				"zip"			=> "max:200",
				"country_code"	=> "exists:countries,code",
				"description"	=> "max:65535",
				"access"		=> "required|in:private,public,public_rwd",
				"currency_id"	=> "required|exists:currencies,id,deleted_at,NULL",
				"annual_revenue"=> "numeric",
				"source_id"		=> "exists:sources,id,deleted_at,NULL",
				"contact_type_id" => "exists:contact_types,id,deleted_at,NULL"];

		foreach($client_roles as $module => $role_ids_array) :
			$field = $module . '_role';
			$valid_role_ids = implode(',', $role_ids_array);
			$rules[$field] = "in:$valid_role_ids";
		endforeach;		

		return \Validator::make($data, $rules);
	}

	public static function singleValidate($data, $contact = null)
	{
		$id = '';
		$not_parent_id = '';
		
		if(array_key_exists('id', $data)) :
			$id = $data['id'];
			$not_parent_id = 'not_in:' . $data['id'];
		endif;	

		$account_id = !is_null($contact) ? $contact->account_id : null_if_not_key('account_id', $data);
		$unique_email = "unique:users,email,$id";
		$owner_required = $data['change_owner'] ? "required" : '';
		$account_required = array_key_exists('account_id', $data) ? "required" : '';
		$email_required = array_key_exists('email', $data) ? "required" : '';
		$last_name_required = array_key_exists('last_name', $data) ? "required" : '';
		$currency_required = array_key_exists('currency_id', $data) ? "required" : '';
		$access_required = array_key_exists('access', $data) ? "required" : '';
		$status_required = array_key_exists('status', $data) ? "required" : '';
		$client_roles = Role::getClientRoleIdMap();

		$rules=["contact_owner"	=> "$owner_required|exists:users,linked_id,linked_type,staff,status,1,deleted_at,NULL",
				"account_id"	=> "$account_required|exists:accounts,id,deleted_at,NULL",				
				"parent_id"		=> "$not_parent_id|exists:contacts,id,account_id,$account_id,deleted_at,NULL",	
				"first_name"	=> "max:200",
				"last_name"		=> "$last_name_required|max:200",
				"title"			=> "max:200",
				"email"			=> "$email_required|email|$unique_email",					
				"phone"			=> "max:200",
				"date_of_birth"	=> "date",
				"fax"			=> "max:200",
				"website"		=> "max:200",
				"facebook"		=> "max:200",
				"twitter"		=> "max:200",
				"skype"			=> "max:200",
				"source_id"		=> "exists:sources,id,deleted_at,NULL",
				"contact_type_id"=> "exists:contact_types,id,deleted_at,NULL",
				"currency_id"	=> "$currency_required|exists:currencies,id,deleted_at,NULL",
				"annual_revenue"=> "numeric",
				"street"		=> "max:200",
				"city"			=> "max:200",
				"state"			=> "max:200",
				"zip"			=> "max:200",
				"country_code"	=> "exists:countries,code",
				"description"	=> "max:65535",
				"access"		=> "$access_required|in:private,public,public_rwd",
				"status"		=> "$status_required|boolean",
				"password"		=> "min:6|max:60"];

		foreach($client_roles as $module => $role_ids_array) :
			$field = $module . '_role';
			$valid_role_ids = implode(',', $role_ids_array);
			$rules[$field] = "in:$valid_role_ids";
		endforeach;			

		return \Validator::make($data, $rules);
	}

	public static function massValidate($data)
	{
		$valid_field = implode(',', self::massfieldlist());
		$access_required = $data['related'] == 'access' ? 'required' : '';
		$account_required = $data['related'] == 'account_id' ? 'required' : '';
		$owner_required = $data['related'] == 'contact_owner' ? 'required' : '';
		$status_required = $data['related'] == 'status' ? 'required' : '';
 		$currency_required = $data['related'] == 'annual_revenue' ? 'required' : '';

		$rules=["related"		=> "required|in:$valid_field",
				"access"		=> "$access_required|in:private,public,public_rwd",	
				"account_id"	=> "$account_required|exists:accounts,id,deleted_at,NULL",	
				"parent_id"		=> "exists:contacts,id,deleted_at,NULL",
				"contact_owner"	=> "$owner_required|exists:users,linked_id,linked_type,staff,status,1,deleted_at,NULL",		
				"title"			=> "max:200",
				"status"		=> "$status_required|in:0,1",
				"fax"			=> "max:200",
				"phone"			=> "max:200",
				"website"		=> "max:200",
				"facebook"		=> "max:200",
				"twitter"		=> "max:200",
				"skype"			=> "max:200",
				"source_id"		=> "exists:sources,id,deleted_at,NULL",
				"contact_type_id"=>"exists:contact_types,id,deleted_at,NULL",
				"currency_id"	=> "$currency_required|exists:currencies,id,deleted_at,NULL",
				"annual_revenue"=> "numeric",
				"street"		=> "max:200",
				"city"			=> "max:200",
				"state"			=> "max:200",
				"zip"			=> "max:200",
				"country_code"	=> "exists:countries,code",
				"description"	=> "max:65535"];

		return \Validator::make($data, $rules);
	}

	public static function importValidate($data)
	{
		$status = true;
		$errors = [];

		if(!in_array('last_name', $data)) :
			$status = false;
			$errors[] = 'The last name field is required.';
		endif;

		if(!in_array('email', $data)) :
			$status = false;
			$errors[] = 'The email field is required.';
		endif;
		
		if(!in_array('account_id', $data)) :
			$status = false;
			$errors[] = 'The account field is required.';
		endif;	

		$outcome = ['status' => $status, 'errors' => $errors];

		return $outcome;
	}

	public static function moveAccountValidate($data)
	{
		$contact = self::find($data['id']);
		$valid_assign_contact = array_keys($contact->assign_new_contact);
		$valid_assign_contact = implode(',', $valid_assign_contact);

		$rules = ["id"					=> "required|exists:contacts,id,deleted_at,NULL",
				  "confirmation"		=> "required|in:keep,new",
				  "assign_contact"		=> "in:$valid_assign_contact",
				  "deal_categories"		=> "array|in:open,closed_won,closed_lost",
				  "project_categories"	=> "array|in:open,completed,cancelled",
				  "estimate_categories"	=> "array|in:draft,sent,accepted,expired,declined",
				  "invoice_categories"	=> "array|in:draft,sent,partially_paid,paid,unpaid"];

		return \Validator::make($data, $rules);		  
	}	

	public static function participantValidate($data)
	{
		$rules = ["contacts"		=> "array|max:25|exists:contacts,id,deleted_at,NULL",
				  "module_id"		=> "required|exists:" . $data['module_name'] . "s,id,deleted_at,NULL",
				  "module_name"		=> "required|in:deal,project"];

		return \Validator::make($data, $rules);
	}

	public static function informationTypes()
	{
		$information_types = ['overview'	=> 'Overview', 
							  'notes'		=> 'Notes', 
							  'emails'		=> 'Emails',
							  'calls'		=> 'Calls',
							  'sms'			=> 'SMS',
							  'tasks'		=> 'Tasks',							  							   
							  'events'		=> 'Events',	
							  'deals'		=> 'Deals',
							  'projects'	=> 'Projects',
							  'estimates'	=> 'Estimates',
							  'invoices'	=> 'Invoices',					  	
							  'items'		=> 'Items', 
							  'campaigns'	=> 'Campaigns',		
							  'files'		=> 'Files',				  
							  'timeline'	=> 'Timeline',
							  'statistics'	=> 'Statistics'];

		return $information_types;
	}

	public static function getParticipantData($request, $contacts, $module_account, $remove = false, $checkbox = false, $search = false)
	{
		$data = \Datatables::of($contacts)
				->addColumn('name', function($contact)
				{
					return $contact->getNameHtmlAttribute(false, false);
				})
				->addColumn('account', function($contact)
				{
					return $contact->account->name_link;
				})
				->addColumn('email', function($contact)
				{
					return $contact->email;
				})
				->addColumn('type', function($contact)
				{
					return non_property_checker($contact->type, 'name');
				});				

		if($remove) :
			$data = $data->addColumn('action', function($contact)
					{
						return $contact->participant_remove_html;
					});
		endif;		

		if($checkbox) :
			$data = $data->addColumn('checkbox', function($contact)
					{
						return $contact->getCheckboxHtmlAttribute('info');
					});
		endif;		

		if($search) :
			$data = $data->filter(function($instance) use ($request, $module_account)
					{
		            	$instance->collection = $instance->collection->filter(function ($row) use ($request, $module_account)
		            	{
		            		$status = true;

		            		$search_val = $request->search['value'];
		            		if($request->has('search') && $search_val != '') :
		            			$name = str_contains($row->name, $search_val) ? true : false;
		            			$email = str_contains($row->email, $search_val) ? true : false;
		            			$phone = str_contains($row->phone, $search_val) ? true : false;
		            			$type = str_contains(non_property_checker($row->type, 'name'), $search_val) ? true : false;
		            			$account = str_contains($row->account_name, $search_val) ? true : false;

		            			if(!$name && !$type && !$email && !$phone && !$account) :
		            				$status = false;
		            			endif;
		            		endif;

		            		if($request->has('account') && $request->account == 0 && $row->account_id != $module_account) :
		            			$status = false;
		            		endif;

		            	    return $status;
		            	});	            	
		            });
		endif;	
		
		return $data->make(true);
	}

	/*
	|--------------------------------------------------------------------------
	| SCOPES
	|--------------------------------------------------------------------------
	*/
	public function scopeReadableIdentifier($query, $name)
	{
		$name_words = explode(' ', $name);
		$fword = $name_words[0];
		$lword = end($name_words);

		return $query->where('first_name', 'LIKE', $fword . '%')
					 ->where('last_name', 'LIKE', '%' . $lword);
	}

	/*
	|--------------------------------------------------------------------------
	| ACCESSOR
	|--------------------------------------------------------------------------
	*/
	public function getNameHtmlAttribute($show_account = true, $show_title = true)
	{
		$name = "<a href='" . route('admin.contact.show', $this->id) . "'>" . $this->name . "</a>" . $this->hidden_currency_info;
		
		if(!isset($show_title) || (isset($show_title) && $show_title == true)) :
			$name.= empty($this->title) ? "" : "<br>";
			$name.= "<span class='sm-txt'>" . $this->title . "</span>";
		endif;
			
		if(!isset($show_account) || (isset($show_account) && $show_account == true)) :
			$name.= "<br><a class='sm-txt' href='" . route('admin.account.show', $this->account_id) . "'>" . $this->account->account_name . "</a>";
		endif;
		
		return $name;
	}

	public function getAccountNameAttribute()
	{
		return non_property_checker($this->account, 'account_name');
	}

	public function getFullNameAttribute()
	{
		$full_name = $this->name . ' (' . $this->account_name . ')';
		return $full_name;
	}

	public function getCompleteNameAttribute()
	{
		$complete_name = $this->name . ' - ' . $this->account_name;
		return $complete_name;
	}

	public function getEmailAttribute()
	{
		return non_property_checker($this->user, 'email');
	}

	public function getStatusAttribute()
	{
		return non_property_checker($this->user, 'status');
	}

	public function getAssignNewContactAttribute($select_item = [])
	{
		$select_item = is_null($select_item) ? [] : $select_item;
		$list = $select_item + self::where('account_id', $this->account_id)->where('id', '!=', $this->id)->orderBy('id')->get(['id', 'first_name', 'last_name'])->pluck('name', 'id')->toArray();
		return $list;
	}

	public function moduleRole($module)
	{
		$module_role = $module . '_role';
		return $this->$module_role;
	}

	public function moduleAccess($module)
	{
		$module_access = $module . '_access';
		return $this->$module_access;
	}

	public function getDealRoleAttribute()
	{
		if($this->user->hasRole('client.deal.view')) :
			return Role::whereName('client.deal.view')->first()->id;
		endif;	

		if($this->user->hasRole('client.deal.view_all')) :
			return Role::whereName('client.deal.view_all')->first()->id;
		endif;

		return null;
	}

	public function getDealAccessAttribute()
	{
		return !is_null($this->deal_role) ? 1 : 0;
	}

	public function getProjectRoleAttribute()
	{
		if($this->user->hasRole('client.project.view')) :
			return Role::whereName('client.project.view')->first()->id;
		endif;	

		if($this->user->hasRole('client.project.view_all')) :
			return Role::whereName('client.project.view_all')->first()->id;
		endif;

		return null;
	}

	public function getProjectAccessAttribute()
	{
		return !is_null($this->project_role) ? 1 : 0;
	}

	public function getEstimateRoleAttribute()
	{
		if($this->user->hasRole('client.estimate.view')) :
			return Role::whereName('client.estimate.view')->first()->id;
		endif;	

		if($this->user->hasRole('client.estimate.view_all')) :
			return Role::whereName('client.estimate.view_all')->first()->id;
		endif;

		return null;
	}

	public function getEstimateAccessAttribute()
	{
		return !is_null($this->estimate_role) ? 1 : 0;
	}

	public function getInvoiceRoleAttribute()
	{
		if($this->user->hasRole('client.invoice.view')) :
			return Role::whereName('client.invoice.view')->first()->id;
		endif;	

		if($this->user->hasRole('client.invoice.view_all')) :
			return Role::whereName('client.invoice.view_all')->first()->id;
		endif;

		return null;
	}

	public function getInvoiceAccessAttribute()
	{
		return !is_null($this->invoice_role) ? 1 : 0;
	}

	public function getLastLoginHtmlAttribute()
	{
		$outcome = 'Never';
		$last_login = $this->user->last_login;

		if($last_login) :
			$outcome = "<span data-toggle='tooltip' data-placement='top' title='" . $last_login . "'>" . time_short_form($last_login->diffForHumans()) . "</span>";
		endif;
		
		return $outcome;
	}

	public function getStatusHtmlAttribute($tooltip_position = null)
	{
		$disabled = '';

		if(!$this->auth_can_edit) :
			$disabled = ' disabled';
		endif;

		$tooltip_position = isset($tooltip_position) ? $tooltip_position : 'top';

		$status = "<label class='switch switch-contact" . $disabled . "' data-toggle='tooltip' data-placement='" . $tooltip_position . "' title='Inactive'><input type='checkbox' value='" . $this->id . "'" . $disabled . "><span class='slider round'></span></label>";
		if($this->user->status == true) :
			$status = "<label class='switch switch-contact" . $disabled . "' data-toggle='tooltip' data-placement='" . $tooltip_position . "' title='Active'><input type='checkbox' value='" . $this->id . "' checked" . $disabled . "><span class='slider round'></span></label>";
		endif;

		return $status;
	}

	public function getHierarchyFormDefaultAttribute()
	{
		return "account_id:$this->account_id|supervisor:$this->id|parent_id:$this->id|street:$this->street|city:$this->city|state:$this->state|zip:$this->zip|country_code:$this->country_code";
	}

	public function getParentContactsListAttribute()
	{
		return $this->getAssignNewContactAttribute(['' => '-None-']);
	}

	public function setAction()
	{
		return ['edit', 'delete', 'send_email', 'send_SMS'];
	}

	public function setMassAction()
	{
		return ['mass_update', 'mass_delete', 'mass_email', 'mass_SMS'];
	}

	public function extendActionHtml($edit_permission = true)
	{
		$extend_action = '';

		$related_call = '';
		if(!array_key_exists('contact_owner', $this->attributes)) :
			$related_call = '|related_type:account|related_id:' . $this->account_id;
		endif;

		$extend_action .= "<li><a class='add-multiple' data-item='call' modal-title='Add Call Log' data-modalsize='medium' data-action='" . route('admin.call.store') . "' data-content='call.partials.form' data-default='client_type:contact|client_id:" . $this->id . "$related_call' save-new='false'><i class='lg mdi mdi-phone-plus'></i> Add Call Log</a></li>";
		$extend_action .= "<li><a class='add-multiple' data-item='task' data-action='" . route('admin.task.store') . "' data-content='task.partials.form' data-default='related_type:contact|related_id:" . $this->id . "' data-show='contact_id' save-new='false'><i class='fa fa-check-square'></i> Add Task</a></li>";
		$extend_action .= "<li><a class='add-multiple' data-item='event' data-action='" . route('admin.event.store') . "' data-content='event.partials.form' data-default='related:contact|contact_id:" . $this->id . "' data-show='contact_id' save-new='false'><i class='fa fa-calendar'></i> Add Event</a></li>";		

		if($this->auth_can_send_email) :
			$extend_action .= "<li><a editid='" . $this->id . "'><i class='fa fa-send-o sm'></i> Send Email</a></li>";
		endif;

		if($this->auth_can_send_sms) :
			$extend_action .= "<li><a editid='" . $this->id . "'><i class='mdi mdi-message sm'></i> Send SMS</a></li>";
		endif;

		if($this->auth_can_edit) :
			$extend_action .= "<li><a class='change-password' editid='" . $this->id . "'><i class='mdi mdi-lock-open-outline'></i> Change Password</a></li>";
		endif;

		return $extend_action;	
	}


	public function getParticipantRemoveHtmlAttribute()
	{
		$html = "<div class='action-box'>
					<div class='dropdown'>
						<a class='dropdown-toggle' data-toggle='dropdown' aria-expanded='false'>
							<i class='fa fa-ellipsis-v'></i>
						</a>
						<ul class='dropdown-menu'>
							<li>" .
								\Form::open(['route' => ['admin.participant.contact.remove', $this->pivot->linked_type, $this->pivot->linked_id, $this->id], 'method' => 'delete']) .
									\Form::hidden('module_id', $this->pivot->linked_id) .
									\Form::hidden('module_name', $this->pivot->linked_type) .
									\Form::hidden('contact_id', $this->id) .
									"<button type='submit' class='delete' data-item='participant' data-parentitem='" . $this->pivot->linked_type . "'><i class='mdi mdi-delete'></i> Remove</button>" .
								\Form::close() . "
							</li>
						</ul>
					</div>
				</div>";

		return $html;		
	}

	/*
	|--------------------------------------------------------------------------
	| RELATIONS
	|--------------------------------------------------------------------------
	*/
	// relation: belongsTo
	public function parentContact()
	{
		return $this->belongsTo(self::class, 'parent_id');
	}

	public function account()
	{
		return $this->belongsTo(Account::class);
	}

	public function contactowner()
	{
		return $this->belongsTo(Staff::class, 'contact_owner')->withTrashed();
	}

	public function currency()
	{
		return $this->belongsTo(Currency::class, 'currency_id');
	}

	public function country()
	{
		return $this->belongsTo(Country::class, 'country_code', 'code');
	}

	public function type()
	{
		return $this->belongsTo(ContactType::class, 'contact_type_id');
	}

	public function source()
	{
		return $this->belongsTo(Source::class);
	}

	// relation: belongsToMany
	public function projects()
	{
		return $this->belongsToMany(Project::class, 'project_contact');
	}

	// relation: hasOne
	public function lead()
	{
		return $this->hasOne(Lead::class, 'converted_contact_id');
	}

	// relation: hasMany
	public function childContacts()
	{
		return $this->hasMany(self::class, 'parent_id');
	}

	public function deals()
	{
		return $this->hasMany(Deal::class);
	}

	public function estimates()
	{
		return $this->hasMany(Estimate::class);
	}

	public function invoices()
	{
		return $this->hasMany(Invoice::class);
	}

	// relation: morphOne
	public function user()
	{
		return $this->morphOne(User::class, 'linked')->withTrashed();
	}

	// relation: morphMany
	public function tasks()
	{
		return $this->morphMany(Task::class, 'linked');
	}

	public function calls()
	{
		return $this->morphMany(Call::class, 'client');
	}

	public function events()
	{
		return $this->morphMany(Event::class, 'linked');
	}

	public function eventattendees()
	{
		return $this->morphMany(EventAttendee::class, 'linked');
	}

	public function socialmedia()
	{
	    return $this->morphMany(SocialMedia::class, 'linked');
	}

	public function notifications()
	{
	    return $this->morphMany(Notification::class, 'linked');
	}

	public function notificationInfos()
	{
		return $this->morphMany(NotificationInfo::class, 'linked');
	}

	public function chatRoomMembers()
	{
	    return $this->morphMany(ChatRoomMember::class, 'linked');
	}

	public function allowedstaffs()
	{
		return $this->morphMany(AllowedStaff::class, 'linked');
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

	// relation: morphToMany
	public function items()
	{
		return $this->morphToMany(Item::class, 'linked', 'cart_items')->withPivot('quantity', 'rate', 'linked_type', 'unit')->orderBy('id');
	}

	public function campaigns()
	{
		return $this->morphToMany(Campaign::class, 'member', 'campaign_members')->withPivot('status', 'member_type')->latest('id');
	}

	// relation: morphedByMany
	public function participatedDeals()
	{
		return $this->morphedByMany(Deal::class, 'linked', 'participant_contacts')->withPivot('linked_type');
	}

	public function participatedProjects()
	{
		return $this->morphedByMany(Project::class, 'linked', 'participant_contacts')->withPivot('linked_type');
	}
}