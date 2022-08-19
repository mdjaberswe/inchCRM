<?php

namespace App\Models;

use App\Models\BaseModel;
use App\Models\Traits\OwnerTrait;
use App\Models\Traits\ModuleTrait;
use App\Models\Traits\ClientTrait;
use App\Models\Traits\FinanceTrait;
use App\Models\Traits\HierarchyTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class Account extends BaseModel
{
	use SoftDeletes;
	use OwnerTrait;
	use ModuleTrait;
	use ClientTrait;	
	use FinanceTrait;
	use HierarchyTrait;
	use RevisionableTrait;
	
	protected $table = 'accounts';
	protected $fillable = ['account_name', 'parent_id', 'account_owner', 'account_email', 'account_phone', 'account_type_id', 'industry_type_id', 'fax', 'website', 'no_of_employees', 'currency_id', 'annual_revenue', 'street', 'city', 'state', 'zip', 'country_code', 'timezone', 'description', 'access'];
	protected $appends = ['name'];
	protected $dates = ['deleted_at'];
	protected $hierarchy_child = 'sub';
	protected $revisionCreationsEnabled = true;
	protected $dontKeepRevisionOf = ['image'];
	protected static $fieldlist = ['access' => 'Access', 'account_name' => 'Account Name', 'account_owner' => 'Account Owner', 'account_type_id' => 'Account Type', 'annual_revenue' => 'Annual Revenue', 'city' => 'City', 'country_code' => 'Country', 'currency_id' => 'Currency', 'description' => 'Description', 'account_email' => 'Email', 'facebook' => 'Facebook', 'fax' => 'Fax', 'industry_type_id' => 'Industry', 'no_of_employees' => 'Number of Employees', 'parent_id' => 'Parent Account', 'account_phone' => 'Phone', 'skype' => 'Skype', 'state' => 'State', 'street' => 'Street', 'twitter' => 'Twitter', 'website' => 'Website', 'zip' => 'Zip Code'];
	protected static $mass_fieldlist = ['account_owner', 'parent_id', 'account_phone', 'account_email', 'fax', 'website', 'facebook', 'twitter', 'skype', 'street', 'city', 'state', 'zip', 'country_code', 'industry_type_id', 'annual_revenue', 'no_of_employees', 'account_type_id', 'description', 'access'];

	public static function validate($data)
	{
		$owner_required = 'required';
		$parent_not_in = '';

		if(isset($data['id'])) :
			$owner_required = array_key_exists('change_owner', $data) ? 'required' : '';
			$parent_not_in = 'not_in:' . $data['id'];
		endif;	

		$rules=["account_name"	=> "required|max:200",
				"parent_account"=> "$parent_not_in|exists:accounts,id,deleted_at,NULL",				
				"hierarchy_id"	=> "exists:accounts,id,deleted_at,NULL",
				"account_owner"	=> "$owner_required|exists:users,linked_id,linked_type,staff,status,1,deleted_at,NULL",
				"account_email"	=> "max:200|email",
				"account_phone"	=> "max:200",
				"fax"			=> "max:200",
				"website"		=> "max:200",
				"currency_id"	=> "required|exists:currencies,id,deleted_at,NULL",
				"annual_revenue"=> "numeric",
				"street"		=> "max:200",
				"city"			=> "max:200",
				"state"			=> "max:200",
				"zip"			=> "max:200",
				"country_code"	=> "exists:countries,code",
				"description"	=> "max:65535",
				"access"		=> "required|in:private,public,public_rwd",
				"no_of_employees"	=> "integer",
				"account_type_id"	=> "exists:account_types,id,deleted_at,NULL",
				"industry_type_id"	=> "exists:industry_types,id,deleted_at,NULL"];

		return \Validator::make($data, $rules);
	}

	public static function singleValidate($data)
	{
		$id = '';
		$parent_not_in = '';
		
		if(array_key_exists('id', $data)) :
			$id = $data['id'];
			$parent_not_in = 'not_in:' . $data['id'];
		endif;

		$owner_required = $data['change_owner'] ? "required" : '';
		$name_required = array_key_exists('account_name', $data) ? "required" : '';
		$lead_stage_required = array_key_exists('lead_stage_id', $data) ? "required" : '';
		$currency_required = array_key_exists('currency_id', $data) ? "required" : '';
		$access_required = array_key_exists('access', $data) ? "required" : '';

		$rules=["account_owner"	=> "$owner_required|exists:users,linked_id,linked_type,staff,status,1,deleted_at,NULL",
				"parent_id"		=> "$parent_not_in|exists:accounts,id,deleted_at,NULL",
				"account_name"	=> "$name_required|max:200",
				"account_email"	=> "max:200|email",					
				"account_phone"	=> "max:200",
				"fax"			=> "max:200",
				"website"		=> "max:200",
				"facebook"		=> "max:200",
				"twitter"		=> "max:200",
				"skype"			=> "max:200",
				"currency_id"	=> "$currency_required|exists:currencies,id,deleted_at,NULL",
				"annual_revenue"=> "numeric",
				"street"		=> "max:200",
				"city"			=> "max:200",
				"state"			=> "max:200",
				"zip"			=> "max:200",
				"country_code"	=> "exists:countries,code",
				"description"	=> "max:65535",
				"access"		=> "$access_required|in:private,public,public_rwd",
				"no_of_employees"=> "integer",
				"account_type_id"=>"exists:account_types,id,deleted_at,NULL",
				"industry_type_id"=>"exists:industry_types,id,deleted_at,NULL"];

		return \Validator::make($data, $rules);
	}

	public static function massValidate($data)
	{
		$valid_field = implode(',', self::massfieldlist());
		$access_required = $data['related'] == 'access' ? 'required' : '';
		$owner_required = $data['related'] == 'account_owner' ? 'required' : '';
		$status_required = $data['related'] == 'status' ? 'required' : '';
 		$currency_required = $data['related'] == 'annual_revenue' ? 'required' : '';

		$rules=["related"		=> "required|in:$valid_field",
				"access"		=> "$access_required|in:private,public,public_rwd",	
				"parent_id"		=> "exists:accounts,id,deleted_at,NULL",
				"account_owner"	=> "$owner_required|exists:users,linked_id,linked_type,staff,status,1,deleted_at,NULL",		
				"fax"			=> "max:200",
				"account_email"	=> "max:200|email",
				"account_phone"	=> "max:200",
				"website"		=> "max:200",
				"facebook"		=> "max:200",
				"twitter"		=> "max:200",
				"skype"			=> "max:200",				
				"currency_id"	=> "$currency_required|exists:currencies,id,deleted_at,NULL",
				"annual_revenue"=> "numeric",
				"street"		=> "max:200",
				"city"			=> "max:200",
				"state"			=> "max:200",
				"zip"			=> "max:200",
				"country_code"	=> "exists:countries,code",
				"description"	=> "max:65535",
				"no_of_employees"	=> "integer",
				"account_type_id"	=> "exists:account_types,id,deleted_at,NULL",
				"industry_type_id"	=> "exists:industry_types,id,deleted_at,NULL"];

		return \Validator::make($data, $rules);
	}

	public static function importValidate($data)
	{
		$status = true;
		$errors = [];

		if(!in_array('account_name', $data)) :
			$status = false;
			$errors[] = 'The account name field is required.';
		endif;

		$outcome = ['status' => $status, 'errors' => $errors];

		return $outcome;
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
							  'payments'	=> 'Payments',				  	
							  'items'		=> 'Items', 
							  'files'		=> 'Files',
							  'timeline'	=> 'Timeline',
							  'orgchart'	=> 'Org Chart',
							  'statistics'	=> 'Statistics'];

		return $information_types;
	}

	/*
	|--------------------------------------------------------------------------
	| SCOPES
	|--------------------------------------------------------------------------
	*/
	public function scopeReadableIdentifier($query, $name)
	{
		return $query->where('account_name', $name);
	}

	/*
	|--------------------------------------------------------------------------
	| ACCESSOR
	|--------------------------------------------------------------------------
	*/
	public function getNameAttribute()
	{
		return $this->attributes['account_name'];
	}

	public function getNameHtmlAttribute($show_hierarchy = true)
	{
		$html = "<div class='relative'>";

		if($this->has_hierarchy && (!isset($show_hierarchy) || (isset($show_hierarchy) && $show_hierarchy == true))) :
			$html .= "<a href='" . route('admin.account.show', [$this->id, 'orgchart']) . "' class='td-left-icon' data-toggle='tooltip' data-placement='top' title='Account&nbsp;Hierarchy'><i class='fa fa-sitemap'></i></a>";
		endif;
			
		$html .= "<a href='" . $this->show_route . "'>" . $this->name . "</a>";

		if(!is_null($this->account_type_id)) :
			$html .= "<br><span class='sm-txt'>" . $this->type->name . "</span>";
		endif;	

		if(!empty($this->address)) :
			$html .= "<br>" . $this->compress_address;
		endif;

		$html .= "</div>";

		return $html;
	}

	public function getPhoneAttribute()
	{
		return $this->account_phone;
	}

	public function getEmailAttribute()
	{
		return $this->account_email;
	}

	public function getGroupsListAttribute()
	{
		return $this->groups->pluck('id')->toArray();
	}

	public function getPaymentsAttribute()
	{
		$payments = Payment::join('invoices', 'invoices.id', '=', 'payments.invoice_id')
							->join('accounts', 'accounts.id', '=', 'invoices.account_id')
							->where('accounts.id', $this->id)
							->latest('payments.payment_date')						  
							->select('payments.*')
							->get();

		return $payments;		
	}

	public function getHierarchyFormDefaultAttribute()
	{
		return "parent_account:$this->id";
	}

	public function getContactsListAttribute()
	{
		$contacts_list = ['' => '-None-'] + $this->contacts()->orderBy('id')->get()->pluck('name', 'id')->toArray();
		return $contacts_list;
	}

	public function setAction()
	{
		return ['edit', 'delete', 'send_email', 'send_SMS'];
	}

	public function setMassAction()
	{
		return ['mass_update', 'mass_delete', 'mass_convert', 'mass_email', 'mass_SMS'];
	}

	public function extendActionHtml($edit_permission = true)
	{
		$extend_action = '';

		$extend_action .= "<li><a class='add-multiple' data-item='call' modal-title='Add Call Log' data-modalsize='medium' data-action='" . route('admin.call.store') . "' data-content='call.partials.form' data-default='related_type:account|related_id:" . $this->id . "' save-new='false'><i class='lg mdi mdi-phone-plus'></i> Add Call Log</a></li>";
		$extend_action .= "<li><a class='add-multiple' data-item='task' data-action='" . route('admin.task.store') . "' data-content='task.partials.form' data-default='related_type:account|related_id:" . $this->id . "' data-show='account_id' save-new='false'><i class='fa fa-check-square'></i> Add Task</a></li>";
		$extend_action .= "<li><a class='add-multiple' data-item='event' data-action='" . route('admin.event.store') . "' data-content='event.partials.form' data-default='related:account|account_id:" . $this->id . "' data-show='account_id' save-new='false'><i class='fa fa-calendar'></i> Add Event</a></li>";

		if($this->auth_can_send_email) :
			$extend_action .= "<li><a editid='" . $this->id . "'><i class='fa fa-send-o sm'></i> Send Email</a></li>";
		endif;

		if($this->auth_can_send_sms) :
			$extend_action .= "<li><a editid='" . $this->id . "'><i class='mdi mdi-message sm'></i> Send SMS</a></li>";
		endif;

		return $extend_action;	
	}

	/*
	|--------------------------------------------------------------------------
	| RELATIONS
	|--------------------------------------------------------------------------
	*/
	// relation: belongsTo
	public function parentAccount()
	{
		return $this->belongsTo(self::class, 'parent_id');
	}

	public function owner()
	{
		return $this->belongsTo(Staff::class, 'account_owner')->withTrashed();
	}

	public function type()
	{
		return $this->belongsTo(AccountType::class, 'account_type_id');
	}

	public function industry()
	{
		return $this->belongsTo(IndustryType::class, 'industry_type_id');
	}

	public function country()
	{
		return $this->belongsTo(Country::class, 'country_code', 'code');
	}

	public function currency()
	{
		return $this->belongsTo(Currency::class, 'currency_id');
	}

	// relation: hasMany
	public function subAccounts()
	{
		return $this->hasMany(self::class, 'parent_id');
	}

	public function leads()
	{
		return $this->hasMany(Lead::class, 'converted_account_id');
	}

	public function contacts()
	{
		return $this->hasMany(Contact::class);
	}

	public function deals()
	{
		return $this->hasMany(Deal::class);
	}

	public function projects()
	{
		return $this->hasMany(Project::class);
	}

	public function estimates()
	{
		return $this->hasMany(Estimate::class);
	}

	public function invoices()
	{
		return $this->hasMany(Invoice::class);
	}

	public function expenses()
	{
		return $this->hasMany(Expense::class);
	}

	// relationL morphMany
	public function tasks()
	{
		return $this->morphMany(Task::class, 'linked');
	}

	public function calls()
	{
		return $this->morphMany(Call::class, 'related');
	}

	public function events()
	{
		return $this->morphMany(Event::class, 'linked');
	}

	public function socialmedia()
	{
	    return $this->morphMany(SocialMedia::class, 'linked');
	}

	public function notificationInfos()
	{
		return $this->morphMany(NotificationInfo::class, 'linked');
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
}