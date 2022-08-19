<?php

namespace App\Models;

use DB;
use Session;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\BaseModel;
use App\Models\Traits\OwnerTrait;
use App\Models\Traits\ModuleTrait;
use App\Models\Traits\ClientTrait;
use App\Models\Traits\FinanceTrait;
use App\Models\Traits\PosionableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class Lead extends BaseModel
{		
	use SoftDeletes;
	use OwnerTrait;	
	use ModuleTrait;
	use ClientTrait;	
	use FinanceTrait;
	use PosionableTrait;
	use RevisionableTrait;
	
	protected $table = 'leads';
	protected $fillable = ['lead_owner', 'source_id', 'lead_stage_id', 'first_name', 'last_name', 'image', 'date_of_birth', 'title', 'email', 'phone', 'company', 'fax', 'website', 'no_of_employees', 'currency_id', 'annual_revenue', 'street', 'city', 'state', 'zip', 'country_code', 'timezone', 'access', 'description', 'converted_account_id', 'converted_contact_id', 'position'];
	protected $appends = ['converted'];
	protected $dates = ['deleted_at'];
	protected $revisionCreationsEnabled = true;
	protected $dontKeepRevisionOf = ['image'];
	protected static $fieldlist = ['access' => 'Access', 'annual_revenue' => 'Annual Revenue', 'city' => 'City', 'company' => 'Company', 'country_code' => 'Country', 'currency_id' => 'Currency', 'date_of_birth' => 'Date of Birth', 'description' => 'Description', 'email' => 'Email', 'facebook' => 'Facebook', 'fax' => 'Fax', 'first_name' => 'First Name', 'title' => 'Job Title', 'last_name' => 'Last Name', 'lead_owner' => 'Lead Owner', 'source_id' => 'Lead Source', 'lead_stage_id' => 'Lead Stage', 'no_of_employees' => 'Number of Employees', 'phone' => 'Phone', 'skype' => 'Skype', 'state' => 'State', 'street' => 'Street', 'twitter' => 'Twitter', 'website' => 'Website', 'zip' => 'Zip Code'];
	protected static $score_attrubutes = ['age' => 'Age', 'created_by' => 'Created By', 'created_at' => 'Created At', 'modified_by' => 'Modified By', 'modified_at' => 'Modified At', 'deal_value' => 'Items of Interest (Deal Value)', 'campaign_id' => 'Campaign', 'event_id' => 'Event', 'last_contacted' => 'Last Contacted', 'last_activity_type' => 'Last Activity Type', 'last_activity_date' => 'Last Activity Date', 'owner_assigned_at' => 'Lead Owner Assigned At'];
	protected static $non_score_attributes = ['date_of_birth'];
	protected static $report_types = ['lead_funnel', 'lead_pie_source', 'lead_stat', 'lead_conversion', 'lost_lead_rate', 'lead_conversion_timeline', 'lead_converted_leaderboard'];

	public static function validate($data)
	{	
		$unique_email = "unique:leads,email";
		$owner_required = "required";

		if(isset($data['id'])) :
			$id = $data['id'];
			$unique_email = "unique:leads,email,$id";
			$owner_required = $data['change_owner'] ? "required" : '';
		endif;	

		$rules=["lead_owner"	=> "$owner_required|exists:users,linked_id,linked_type,staff,status,1,deleted_at,NULL",
				"company"		=> "max:200",				
				"first_name"	=> "max:200",
				"last_name"		=> "required|max:200",
				"title"			=> "max:200",
				"email"			=> "email|$unique_email",					
				"phone"			=> "max:200",
				"source_id"		=> "exists:sources,id,deleted_at,NULL",
				"lead_stage_id"	=> "required|exists:lead_stages,id,deleted_at,NULL",
				"currency_id"	=> "required|exists:currencies,id,deleted_at,NULL",
				"annual_revenue"=> "numeric",
				"street"		=> "max:200",
				"city"			=> "max:200",
				"state"			=> "max:200",
				"zip"			=> "max:200",
				"country_code"	=> "exists:countries,code",
				"description"	=> "max:65535",
				"access"		=> "required|in:private,public,public_rwd",
				"no_of_employees"=> "integer"];

		return \Validator::make($data, $rules);
	}

	public static function singleValidate($data)
	{
		$id = array_key_exists('id', $data) ? $data['id'] : '';
		$unique_email = "unique:leads,email,$id";
		$owner_required = $data['change_owner'] ? "required" : '';
		$last_name_required = array_key_exists('last_name', $data) ? "required" : '';
		$lead_stage_required = array_key_exists('lead_stage_id', $data) ? "required" : '';
		$currency_required = array_key_exists('currency_id', $data) ? "required" : '';
		$access_required = array_key_exists('access', $data) ? "required" : '';

		$rules = ["lead_owner"	=> "$owner_required|exists:users,linked_id,linked_type,staff,status,1,deleted_at,NULL",
				"company"		=> "max:200",				
				"first_name"	=> "max:200",
				"last_name"		=> "$last_name_required|max:200",
				"title"			=> "max:200",
				"email"			=> "email|$unique_email",					
				"phone"			=> "max:200",
				"date_of_birth"	=> "date",
				"fax"			=> "max:200",
				"website"		=> "max:200",
				"facebook"		=> "max:200",
				"twitter"		=> "max:200",
				"skype"			=> "max:200",
				"source_id"		=> "exists:sources,id,deleted_at,NULL",
				"lead_stage_id"	=> "$lead_stage_required|exists:lead_stages,id,deleted_at,NULL",
				"currency_id"	=> "$currency_required|exists:currencies,id,deleted_at,NULL",
				"annual_revenue"=> "numeric",
				"street"		=> "max:200",
				"city"			=> "max:200",
				"state"			=> "max:200",
				"zip"			=> "max:200",
				"country_code"	=> "exists:countries,code",
				"description"	=> "max:65535",
				"access"		=> "$access_required|in:private,public,public_rwd",
				"no_of_employees"=> "integer"];

		return \Validator::make($data, $rules);
	}

	public static function massValidate($data)
	{
		$lead_stage_required = $data['related'] == 'lead_stage_id' ? 'required' : '';
		$currency_required = $data['related'] == 'annual_revenue' ? 'required' : '';

		$rules =["related"		=> "required|in:annual_revenue,city,company,country_code,facebook,fax,source_id,lead_stage_id,no_of_employees,phone,skype,state,street,title,twitter,website,zip",
				"company"		=> "max:200",				
				"title"			=> "max:200",
				"fax"			=> "max:200",
				"phone"			=> "max:200",
				"website"		=> "max:200",
				"facebook"		=> "max:200",
				"twitter"		=> "max:200",
				"skype"			=> "max:200",
				"source_id"		=> "exists:sources,id,deleted_at,NULL",
				"lead_stage_id"	=> "$lead_stage_required|exists:lead_stages,id,deleted_at,NULL",
				"currency_id"	=> "$currency_required|exists:currencies,id,deleted_at,NULL",
				"annual_revenue"=> "numeric",
				"street"		=> "max:200",
				"city"			=> "max:200",
				"state"			=> "max:200",
				"zip"			=> "max:200",
				"country_code"	=> "exists:countries,code",
				"no_of_employees"=> "integer"];

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
		
		if(!in_array('lead_stage_id', $data)) :
			$status = false;
			$errors[] = 'The lead stage field is required.';
		endif;	

		$outcome = ['status' => $status, 'errors' => $errors];

		return $outcome;
	}

	public static function convertValidate($data)
	{	
		$rules=["owner"		=> "required|exists:users,linked_id,linked_type,staff,status,1,deleted_at,NULL",
				"lead_stage_id"	=> "required|exists:lead_stages,id,category,converted,deleted_at,NULL",
				"account_type"	=> "required|in:new,add",
				"email"			=> "required|email|unique:users,email",
				"password"		=> "required|min:6|max:60"];

		if($data['account_type'] == 'new') :
			$rules['account_name'] = "required|max:200";
		else :
			$rules['account_id'] = "required|exists:accounts,id,deleted_at,NULL";
		endif;		

		if(isset($data['new_deal'])) :
			$deal_pipeline_id = array_key_exists('deal_pipeline_id', $data) ? $data['deal_pipeline_id'] : 0;
			
			$rules['deal_name'] = "required|max:200";
			$rules['amount'] = "min:0|numeric";
			$rules['currency_id'] = "required|exists:currencies,id,deleted_at,NULL";
		  	$rules['closing_date'] = "required|date";
			$rules['deal_stage_id']	= "required|exists:deal_stages,id,deleted_at,NULL";
			$rules['deal_pipeline_id'] = "required|exists:deal_pipelines,id,deleted_at,NULL";
			$rules['deal_stage_id']	= "required|exists:deal_stages,id,deleted_at,NULL|exists:pipeline_stages,deal_stage_id,deal_pipeline_id,$deal_pipeline_id";
		endif;	

		return \Validator::make($data, $rules);
	}

	public static function reportFilterValidate($data)
	{
		$valid_timeperiods = array_keys(time_period_list());
		$valid_timeperiods = implode(',', $valid_timeperiods);
			
		$rules = ["timeperiod"	=> "required|in:$valid_timeperiods"];

		if(array_key_exists('timeperiod', $data) && $data['timeperiod'] == 'between') :
			$start_date = $data['start_date'];
			$start_date_minus = date('Y-m-d', strtotime($start_date . ' -1 day'));

			$rules["start_date"] = "required|date";
			$rules["end_date"] = "required|after:$start_date_minus";
		endif;	

		if($data['type'] == 'lead_funnel') :
			$rules["lead_stage_condition"] = "in:equal,not_equal";
			if(array_key_exists('lead_stage_condition', $data) && $data['lead_stage_condition'] != '') :
				$rules['lead_stage_id'] = "required|exists:lead_stages,id,deleted_at,NULL";
			endif;	 
		elseif($data['type'] == 'lead_pie_source') :
			$rules["lead_source_condition"] = "in:equal,not_equal";
			if(array_key_exists('lead_source_condition', $data) && $data['lead_source_condition'] != '') :
				$rules['source_id'] = "required|exists:sources,id,deleted_at,NULL";
			endif;	
		endif;	

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
							  'items'		=> 'Items', 
							  'campaigns'	=> 'Campaigns',		
							  'files'		=> 'Files',				  
							  'timeline'	=> 'Timeline'];

		return $information_types;
	}

	public static function scorePropertyList()
	{
		$list = self::$fieldlist + self::$score_attrubutes;
		$list = array_except($list, self::$non_score_attributes);
		return $list;
	}

	public static function reportTypes($type = null)
	{
		$outcome['list'] = self::$report_types;

		if($type != null) :
			$timeperiod = $type . '_timeperiod';
			$start_date = $type . '_start_date';
			$end_date = $type . '_end_date';

			$outcome['session'] = [$timeperiod, $start_date, $end_date];
		endif;	

		return $outcome;
	}

	public static function scoreDividedByCondition()
	{
		$all_conditions = array_keys(self::scorePropertyList());
		$condition['dropdown'] = ['source_id', 'lead_stage_id', 'lead_owner', 'no_of_employees', 'currency_id', 'created_by', 'modified_by', 'access', 'country_code', 'campaign_id', 'event_id', 'last_activity_type'];
		$condition['date'] = ['created_at', 'modified_at', 'last_contacted', 'last_activity_date', 'owner_assigned_at'];
		$condition['numeric'] = ['age', 'annual_revenue', 'deal_value'];
		$except_string = array_flatten($condition);
		$condition['string'] = array_diff($all_conditions, $except_string);
		
		return $condition;		
	}

	public static function scoreConditionCssClass()
	{
		$separated = self::scoreDividedByCondition();
		$condition['string_css'] = implode('-list ', $separated['string']) . '-list';
		$condition['dropdown_css'] = implode('-list ', $separated['dropdown']) . '-list';
		$condition['date_css'] = implode('-list ', $separated['date']) . '-list';
		$condition['numeric_css'] = implode('-list ', $separated['numeric']) . '-list';

		return $condition;
	}

	public static function kanbanValidate($data)
	{
		$picked_exists = '';
		if(array_key_exists('picked', $data) && $data['picked'] != 0) :
			$picked_exists = 'exists:leads,id,deleted_at,NULL';
		endif;
			
		$rules = ["source"	=> "required|in:lead",
				  "id"		=> "required|exists:leads,id,deleted_at,NULL",
				  "picked"	=> "required|different:id|$picked_exists",
				  "field"	=> "required|in:lead_stage_id",
				  "stage"	=> "required|exists:lead_stages,id,deleted_at,NULL",
				  "ordertype" => "required|in:desc"];

		return \Validator::make($data, $rules);
	}

	public static function kanbanCardValidate($data)
	{
		$rules = ['stageId'	=> 'required|exists:lead_stages,id,deleted_at,NULL',
				  'ids'		=> 'required|array|exists:leads,id,deleted_at,NULL'];

		return \Validator::make($data, $rules);
	}

	public static function getKanbanData()
	{
		$outcome = [];

		$stages = LeadStage::orderBy('position')->get();

		foreach($stages as $stage) :
			$key = 'leadstage-' . $stage->id;
			$outcome[$key]['data'] = self::getAuthViewData()->where('lead_stage_id', $stage->id)->latest('position')->get();			
			$outcome[$key]['quick_data'] = $outcome[$key]['data']->take(5);	
			$outcome[$key]['stage'] = $stage->toArray();
			$outcome[$key]['stage']['load_status'] = $outcome[$key]['data']->count() > 5 ? 'true' : 'false';
			$outcome[$key]['stage']['load_url'] = route('admin.lead.kanban.card', $stage->id);
		endforeach;	

		return $outcome;
	}

	public static function getKanbanStageCount()
	{
		$outcome = [];

		$stages = LeadStage::orderBy('position')->get();

		foreach($stages as $stage) :
			$key = 'leadstage-' . $stage->id;
			$count = self::getAuthViewData()->where('lead_stage_id', $stage->id)->get()->count();
			$outcome[$key] = '(' . $count . ')'; 
		endforeach;	

		return $outcome;
	}

	public static function updateReportParameters($request, $type)
	{
		list($session_timeperiod, $session_start_date, $session_end_date) = self::reportTypes($type)['session'];

		Session::put($session_timeperiod, $request->timeperiod);

		if($request->timeperiod == 'between') :
			Session::put($session_start_date, date('Y-m-d H:i:s', strtotime($request->start_date)));
			Session::put($session_end_date, date('Y-m-d H:i:s', strtotime($request->end_date)));
		elseif($request->timeperiod == 'any') :
			Session::put($session_start_date, null);
			Session::put($session_end_date, null);	
		else :
			$dates = time_period_dates($request->timeperiod);
			Session::put($session_start_date, $dates['start_date']);
			Session::put($session_end_date, $dates['end_date']);
		endif;

		if($type == 'lead_funnel') :
			Session::put('lead_funnel_stage_condition', $request->lead_stage_condition);
			if($request->lead_stage_condition != '') :
				Session::put('lead_funnel_stages', $request->lead_stage_id);
			else :
				Session::put('lead_funnel_stages', null);
			endif;
		elseif($type == 'lead_pie_source') :
			Session::put('lead_source_condition', $request->lead_source_condition);

			if($request->lead_source_condition != '') :
				Session::put('lead_pie_source', $request->source_id);
			else :
				Session::put('lead_pie_source', null);
			endif;	
		endif;

		return true;
	}

	public static function reportFilterParameters($type)
	{
		list($session_timeperiod, $session_start_date, $session_end_date) = self::reportTypes($type)['session'];

		if(Session::has($session_timeperiod)) :
			$param['timeperiod'] = Session::get($session_timeperiod);
			$param['start_date'] = Session::get($session_start_date);
			$param['end_date'] = Session::get($session_end_date);

			if($type == 'lead_funnel') :
				$param['lead_stage_condition'] = Session::get('lead_funnel_stage_condition');
				$param['lead_stage_id[]'] = Session::get('lead_funnel_stages');
			elseif($type == 'lead_pie_source') :
				$param['lead_source_condition'] = Session::get('lead_source_condition');
				$param['source_id[]'] = Session::get('lead_pie_source');
			endif;	
		else :
			$param['timeperiod'] = default_time_period(config("filter.$type.timeperiod"));
			$dates = time_period_dates($param['timeperiod']);
			$param['start_date'] = $dates['start_date'];
			$param['end_date'] = $dates['end_date'];

			if($type == 'lead_funnel') :
				$param['lead_stage_condition'] = self::funnelStageCondition();
				$param['lead_stage_id[]'] = self::funnelStages();
			elseif($type == 'lead_pie_source') :
				$param['lead_source_condition'] = self::pieSourceCondition();
				$param['source_id[]'] = self::pieSources();	
			endif;	
		endif;		

		$param['timeperiod_display'] = $param['timeperiod'] != 'between' ? strtolower(time_period_list()[$param['timeperiod']]) : readable_full_date($param['start_date']) . ' to ' . readable_full_date($param['end_date']);

		return $param;
	}

	public static function funnelJsonData()
	{
		$json_data = '';
		$array_object = [];

		$param = self::reportFilterParameters('lead_funnel');

		$stages = LeadStage::orderByRaw("FIELD(category, 'open', 'converted', 'closed_lost')");
		
		if($param['lead_stage_condition'] == 'equal') :
			$stages = $stages->WhereIn('id', $param['lead_stage_id[]']);
		elseif($param['lead_stage_condition'] == 'not_equal') :
			$stages = $stages->WhereNotIn('id', $param['lead_stage_id[]']);
		endif;	

		$stages = $stages->orderBy('position')->get();

		$pinched = 0;

		foreach($stages as $stage) :
			$count = self::getAuthViewData()->where('lead_stage_id', $stage->id);

			if($param['timeperiod'] != 'any') :
				if($param['start_date'] == $param['end_date']) :
					$next_date = after_date($param['start_date'], 1, 'day');
					$count = $count->where('created_at', '>=', $param['start_date'])->where('created_at', '<', $next_date);
				else :
					$count = $count->where('created_at', '>=', $param['start_date'])->where('created_at', '<=', $param['end_date']);
				endif;	
			endif;	

			$count = $count->get()->count();

			if($count) :
				$stage_parameter = ['label' => $stage->name, 'value' => $count];
				$array_object[] = (object)$stage_parameter;
				$json_data .= json_encode($stage_parameter) . ',';	

				if($stage->category != 'open') :
					$pinched++;
				endif;	
			endif;	
		endforeach;	

		if($pinched == 0) :
			$pinched = 1;
		elseif($pinched > 3) :
			$pinched = 3;
		elseif(($stages->count() - $pinched) <= 1) :
			$pinched = min_zero($pinched - 1);	
		endif;	

		$outcome = ['data' => $json_data, 'arrayObject' => $array_object, 'pinched' => $pinched, 'timeperiod_display' => $param['timeperiod_display']];

		return $outcome;
	}

	public static function funnelStageCondition()
	{
		$condition = config('filter.lead_funnel.stage_condition');

		if(in_array($condition, ['', 'equal', 'not_equal'])) :
			return $condition;
		endif;	

		return 'not_equal';
	}

	public static function funnelStages()
	{
		if(config('filter.lead_funnel.stage_condition') == 'not_equal' && config('filter.lead_funnel.stages') == null) :
			$stages = LeadStage::whereCategory('closed_lost')->get()->pluck('id');
			return $stages;	
		else :
			$stage_names = explode('|', config('filter.lead_funnel.stages'));
			$stages = [];

			foreach($stage_names as $name) :
				$stage = LeadStage::whereName($name)->get();

				if($stage->count()) :
					$stages[] = $stage->first()->id;
				endif;	
			endforeach;	

			return $stages;
		endif;			

		return null;
	}

	public static function pieSources()
	{
		if(config('filter.lead_pie_source.source_condition') == '' && config('filter.lead_pie_source.sources') == null) :
			$sources = Source::orderBy('position')->get()->pluck('id');
			return $sources;	
		else :
			$source_names = explode('|', config('filter.lead_pie_source.sources'));
			$sources = [];

			foreach($source_names as $name) :
				$source = Source::whereName($name)->get();

				if($source->count()) :
					$sources[] = $source->first()->id;
				endif;	
			endforeach;	

			return $sources;
		endif;			

		return null;
	}

	public static function pieSourceCondition()
	{
		$condition = config('filter.lead_pie_source.source_condition');
		$show_all_sources = '';

		if(in_array($condition, ['', 'equal', 'not_equal'])) :
			return $condition;
		endif;	

		return $show_all_sources;
	}

	public static function pieSourceData()
	{
		$source_names = [];
		$source_leads_count = [];
		$backgrounds = [];

		$string_names = '';
		$string_leads_count = '';
		$string_background = '';

		$param = self::reportFilterParameters('lead_pie_source');
		$sources = Source::orderBy('position');
		
		if($param['lead_source_condition'] == 'equal') :
			$sources = $sources->WhereIn('id', $param['source_id[]']);
		elseif($param['lead_source_condition'] == 'not_equal') :
			$sources = $sources->WhereNotIn('id', $param['source_id[]']);
		endif;	

		$sources = $sources->get();
		$nth = 0;

		if($param['lead_source_condition'] != 'equal') :
			$null_source_count = self::getAuthViewData()->where('source_id', null);

			if($param['timeperiod'] != 'any') :
				if($param['start_date'] == $param['end_date']) :
					$next_date = after_date($param['start_date'], 1, 'day');
					$null_source_count = $null_source_count->where('created_at', '>=', $param['start_date'])->where('created_at', '<', $next_date);
				else :
					$null_source_count = $null_source_count->where('created_at', '>=', $param['start_date'])->where('created_at', '<=', $param['end_date']);
				endif;	
			endif;	

			$null_source_count = $null_source_count->get()->count();

			$source_names[] = 'None';
			$source_leads_count[] = $null_source_count;		
			$background = generate_rgba_color($nth);	
			$backgrounds[] = $background;
			$nth++;
		endif;

		foreach($sources as $source) :
			$count = self::getAuthViewData()->where('source_id', $source->id);

			if($param['timeperiod'] != 'any') :
				if($param['start_date'] == $param['end_date']) :
					$next_date = after_date($param['start_date'], 1, 'day');
					$count = $count->where('created_at', '>=', $param['start_date'])->where('created_at', '<', $next_date);
				else :
					$count = $count->where('created_at', '>=', $param['start_date'])->where('created_at', '<=', $param['end_date']);
				endif;	
			endif;	

			$count = $count->get()->count();
			$color = generate_rgba_color($nth);

			$source_names[] = $source->name;
			$source_leads_count[] = $count;			
			$backgrounds[] = $color;
			$nth++;
		endforeach;

		$string_names = implode(',', $source_names);
		$string_leads_count = implode(',', $source_leads_count);
		$string_background = implode(',', $backgrounds);

		$outcome = ['labels' => $source_names, 
					'values' => $source_leads_count,
					'backgrounds' => $backgrounds,
					'string_names' => $string_names,
					'string_leads_count' => $string_leads_count,
					'string_background' => $string_background,
					'timeperiod_display' => $param['timeperiod_display']];

		return $outcome;	
	}

	public static function numberOfLeadsReport($type)
	{
		$param = self::reportFilterParameters($type);
		$outcome = self::calculateNumberOfLeadsReport($param);

		return $outcome;
	}

	public static function conversionTimelineData()
	{
		$days = [];
		$years = [];
		$conversion_rates = [];
		$lost_rates = [];		
		$labels = ['Conversion Rate', 'Lost Rate'];
		$backgrounds = ['rgba(255, 102, 0, 1)', 'rgba(252, 210, 2, 1)'];
		$borders = ['rgba(255, 102, 0, 1)', 'rgba(252, 210, 2, 1)'];

		$string_days = '';
		$string_years = '';
		$string_conversion = '';
		$string_lost_rate = '';		

		$param = self::reportFilterParameters('lead_conversion_timeline');
		$period =  CarbonPeriod::create($param['start_date'], $param['end_date']);

		foreach($period as $date) :
			$arg['start_date'] = $arg['end_date'] = $date->format('Y-m-d 00:00:00');
			$arg['timeperiod_display'] = $date->format('M d, Y');
			$arg['timeperiod'] = 'today';
			$day_conversion_report = self::calculateNumberOfLeadsReport($arg);
			$days[] = $date->format('M d');
			$conversion_rates[] = $day_conversion_report['conversion_val'];
			$lost_rates[] = $day_conversion_report['lost_lead_rate_val'];
			$years[] = $date->format('Y');
		endforeach;	

		$alldata = [$conversion_rates, $lost_rates];
		$string_days = implode(',', $days);
		$string_years = implode(',', $years);
		$string_conversion = implode(',', $conversion_rates);
		$string_lost_rate = implode(',', $lost_rates);
		$string_labels = implode(',', $labels);	
		$string_backgrounds = implode(',', $backgrounds);
		$string_borders = implode(',', $borders);	

		$outcome = ['days' => $days, 
					'years' => $years,
					'alldata' => $alldata,
					'conversion_rates' => $conversion_rates,
					'lost_rates' => $lost_rates,
					'string_days' => $string_days,
					'string_years' => $string_years,
					'string_conversion' => $string_conversion,
					'string_lost_rate' => $string_lost_rate,
					'labels' => $labels,
					'string_labels' => $string_labels,
					'backgrounds' => $backgrounds,
					'string_backgrounds' => $string_backgrounds,
					'borders' => $borders,
					'string_borders' => $string_borders,
					'timeperiod_display' => $param['timeperiod_display']];

		return $outcome;
	}

	public static function calculateNumberOfLeadsReport($param)
	{
		$active_leads_count = 0;
		$converted_leads_count = 0;
		$lost_leads_count = 0;

		$active_leads = self::getAuthViewData()
							->leftjoin('revisions', 'leads.id', '=', 'revisions.revisionable_id')	
							->select('leads.id', 'revisions.created_at', 'revisions.revisionable_type', 'revisions.key')			
							->where('revisions.revisionable_type', 'lead')
							->where('revisions.key', 'lead_stage_id');

		if($param['timeperiod'] != 'any') :
			if($param['start_date'] == $param['end_date']) :
				$next_date = after_date($param['start_date'], 1, 'day');
				$active_leads = $active_leads->where('revisions.created_at', '>=', $param['start_date'])->where('revisions.created_at', '<', $next_date);
			else :
				$active_leads = $active_leads->where('revisions.created_at', '>=', $param['start_date'])->where('revisions.created_at', '<=', $param['end_date']);
			endif;	
		endif;	

		$ids = $active_leads->groupBy('leads.id')->pluck('leads.id');

		$active_leads = self::whereIn('id', $ids);	
		$converted_leads = self::whereIn('id', $ids);
		$lost_leads = self::whereIn('id', $ids);

		$converted_lead_stage_ids = LeadStage::whereCategory('converted')->pluck('id');
		$lost_lead_stage_ids = LeadStage::whereCategory('closed_lost')->pluck('id');

		$active_leads_count = $active_leads->count();
		$converted_leads_count = $converted_leads->whereIn('lead_stage_id', $converted_lead_stage_ids)
												 ->whereNotNull('converted_contact_id')
												 ->whereNotNull('converted_account_id')
												 ->count();
		$conversion_rate = $active_leads_count > 0 ? ($converted_leads_count / $active_leads_count) * 100 : 0;
		$conversion_rate = $conversion_rate > 0 ? number_format($conversion_rate, 2, '.', '') : $conversion_rate;
		$conversion_val = ($conversion_rate + 0);
		$conversion_rate = $conversion_val . '%';
												 
		$lost_leads_count = $lost_leads->whereIn('lead_stage_id', $lost_lead_stage_ids)->count();
		$lost_lead_rate = $active_leads_count > 0 ? ($lost_leads_count / $active_leads_count) * 100 : 0;
		$lost_lead_rate = $lost_lead_rate > 0 ? number_format($lost_lead_rate, 2, '.', '') : min_zero($lost_lead_rate);
		$lost_lead_rate_val = ($lost_lead_rate + 0);
		$lost_lead_rate = $lost_lead_rate_val . '%';

		$outcome = ['active_leads' => $active_leads_count, 
					'converted_leads' => $converted_leads_count,
					'conversion_val' => $conversion_val,
					'conversion' => $conversion_rate,
					'lost_leads' => $lost_leads_count,
					'lost_lead_rate_val' => $lost_lead_rate_val,
					'lost_lead_rate' => $lost_lead_rate, 
					'timeperiod_display' => $param['timeperiod_display']];

		return $outcome;
	}

	public static function convertedLeaderboardData()
	{
		$param = self::reportFilterParameters('lead_converted_leaderboard');
		$outcome = ['rank_html1' => null, 'rank_html2' => null, 'rank_html3' => null, 'timeperiod_display' => $param['timeperiod_display']];

		$active_leads = self::leftjoin('revisions', 'leads.id', '=', 'revisions.revisionable_id')				
							->whereRevisionable_type('lead')
							->wherekey('lead_stage_id');

		if($param['timeperiod'] != 'any') :
			if($param['start_date'] == $param['end_date']) :
				$next_date = after_date($param['start_date'], 1, 'day');
				$active_leads = $active_leads->where('revisions.created_at', '>=', $param['start_date'])->where('revisions.created_at', '<', $next_date);
			else :
				$active_leads = $active_leads->where('revisions.created_at', '>=', $param['start_date'])->where('revisions.created_at', '<=', $param['end_date']);
			endif;	
		endif;	

		$active_ids = $active_leads->select('leads.*')->groupBy('leads.id')->pluck('lead.id');
		$converted_lead_stage_ids = LeadStage::whereCategory('converted')->pluck('id');
		
		$ranks = self::whereIn('id', $active_ids)
					 ->whereIn('lead_stage_id', $converted_lead_stage_ids)
					 ->whereNotNull('converted_contact_id')
					 ->whereNotNull('converted_account_id')
					 ->select('lead_owner', DB::raw('count(*) as total'))
					 ->groupBy('lead_owner')
					 ->latest('total')
					 ->orderBy('lead_owner')					 
					 ->pluck('lead_owner')
					 ->toArray();

		$total_ranks = count($ranks);		 

		if($total_ranks == 0) :
			return $outcome;
		endif;

		$ranks_data = self::whereIn('id', $active_ids)
						  ->whereIn('lead_stage_id', $converted_lead_stage_ids)
						  ->whereNotNull('converted_contact_id')
						  ->whereNotNull('converted_account_id')
						  ->select('lead_owner', DB::raw('count(*) as total'))
						  ->groupBy('lead_owner')
						  ->latest('total')
						  ->orderBy('lead_owner')						  
						  ->pluck('total', 'lead_owner')
						  ->toArray(); 

		$auth_staff_id = auth_staff()->id;				  

		$title1 = $title2 = $title3 = null;
		$count1 = $count2 = $count3 = null;		

		if(in_array($auth_staff_id, $ranks)) :
			$auth_rank = array_search($auth_staff_id, $ranks) + 1;
			$you = "You rank ";
			$title1 = $you . "<span class='sign'></span>" . $auth_rank;
			$count1 = $ranks_data[$auth_staff_id];
		else :
			$auth_rank = 0;
			$you = '';
			$count1 = $ranks_data[$ranks[0]];
		endif;

		switch($auth_rank) :
			case 0 :
			case 1 :	
				$title1 = $you . "<i class='mdi mdi-podium-gold'></i><span class='sign near'></span>1";

				if($total_ranks > 1) :					
					$title2 = "<span class='sign'></span>2";
					$count2 = $ranks_data[$ranks[1]];			   			 
				endif;	

				if($total_ranks > 2) :					
					$title3 = "<span class='sign'></span>3";
					$count3 = $ranks_data[$ranks[2]];				   			 
				endif;	
			break;	

			case $total_ranks :
				$crown2 = ($auth_rank - 1) == 1 ? "<i class='mdi mdi-podium-gold'></i>" : '';
				$title2 = $crown2 . "<span class='sign'></span>" . ($auth_rank - 1);
				$count2 = $ranks_data[$ranks[($auth_rank - 2)]];			   			 

				if(($auth_rank - 3) >= 0) :		
					$crown3 = ($auth_rank - 2) == 1 ? "<i class='mdi mdi-podium-gold'></i>" : '';			
					$title3 = $crown3 . "<span class='sign'></span>" . ($auth_rank - 2);
					$count3 = $ranks_data[$ranks[($auth_rank - 3)]];			   			 
				endif;	
			break;
			
			default :
				$crown2 = ($auth_rank - 1) == 1 ? "<i class='mdi mdi-podium-gold'></i>" : '';
				$title2 = $crown2 . "<span class='sign'></span>" . ($auth_rank - 1);
				$count2 = $ranks_data[$ranks[($auth_rank - 2)]];				   			 

				$title3 = "<span class='sign'></span>" . ($auth_rank + 1);
				$count3 = $ranks_data[$ranks[$auth_rank]];		 		   			  
		endswitch;	

		$outcome['rank_html1'] = "<div class='rank'>
									<h2>". $title1. "</h2>
									<div class='info'><i class='mdi mdi-account-convert'></i> count " . $count1 . "</div>
					   			  </div>";

		if(!is_null($count2)) :					
			$outcome['rank_html2'] = "<div class='rank'>
										<h2>" . $title2 . "</h2>
										<div class='info white'><i class='mdi mdi-account-convert'></i> count " . $count2 . "</div>
						   			 </div>";
		endif;	

		if(!is_null($count3)) :				
			$outcome['rank_html3'] = "<div class='rank'>
										<h2>". $title3 . "</h2>
										<div class='info white'><i class='mdi mdi-account-convert'></i> count " . $count3 . "</div>
						   			 </div>";
		endif;	

		return $outcome;
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
	public function getKanbanStageKeyAttribute()
	{
		return 'leadstage-' . $this->lead_stage_id;
	}

	public function getKanbanCardKeyAttribute()
	{
		return 'lead-' . $this->id;
	}

	public function getKanbanCardAttribute()
	{
		$action = '';
		$action_html = '';
		$card_btn = '';

		if($this->auth_can_delete) :
			$action .= "<div class='funnel-btn'>" .							
							\Form::open(['route' => ['admin.lead.destroy', $this->id], 'method' => 'delete']) .
								\Form::hidden('id', $this->id) .
								"<button type='submit' class='delete'><i class='mdi mdi-delete'></i></button>" .
				  			\Form::close() . 
					   "</div>";
		endif;	

		$action .= "<div class='funnel-btn dropdown dark'>
						<a class='dropdown-toggle' animation='fadeIn|fadeOut' data-toggle='dropdown' aria-expanded='false'><i class='mdi mdi-plus-circle-multiple-outline'></i></a>

						<div class='dropdown-menu up-caret'>		
							<span><a class='add-multiple' data-item='call' data-action='" . route('admin.call.store') . "' data-content='call.partials.form' data-default='client_type:lead|client_id:" . $this->id . "' save-new='false'><i class='lg mdi mdi-phone-plus'></i> Add Call Log</a></span>   
							<span><a class='add-multiple' data-item='task' data-action='" . route('admin.task.store') . "' data-content='task.partials.form' data-default='related_type:lead|related_id:" . $this->id . "' data-show='lead_id' save-new='false'><i class='fa fa-check-square'></i> Add Task</a></span>    			
							<span><a class='add-multiple' data-item='event' data-action='" . route('admin.event.store') . "' data-content='event.partials.form' data-default='related:lead|lead_id:" . $this->id . "' data-show='lead_id' save-new='false'><i class='fa fa-calendar'></i> Add Event</a></span>							
						</div>
					</div>";

		if($this->auth_can_send_email) :
			$action .= "<div class='funnel-btn'>
							<a><i class='mdi mdi-email'></i></a>
						</div>";
		endif;

		if($this->auth_can_send_sms) :
			$action .= "<div class='funnel-btn'>
							<a><i class='mdi mdi-message'></i></a>
						</div>";
		endif;

		if($this->auth_can_convert && !$this->is_converted) :
			$action .= "<div class='funnel-btn'>
							<a class='convert-kanban' editid='" . $this->id . "'><i class='mdi mdi-account-convert'></i></a>
						</div>";
		endif;

		if($this->auth_can_edit) :
			$action .= "<div class='funnel-btn'>
							<a class='edit' editid='" . $this->id . "' data-url='" . route('admin.lead.index') . "'><i class='fa fa-pencil'></i></a>
						</div>";
		endif;	

		if($action != '') :
			$card_btn = "<a class='funnel-bottom-btn'><i class='fa fa-ellipsis-v md'></i></a>";
			$action_html = "<div class='full funnel-btn-group'>" . $action . "</div>";
		endif;	

		$card = "<div class='funnel-card has-currency-info' data-init-stage='" . $this->lead_stage_id . "'>
					<span class='company-name none'>" . $this->company . "</span>
					<span class='lead-name none'>" . $this->name . "</span>					
					" . $this->hidden_currency_info . "

					<div class='funnel-top-btn'>" .
						\Form::hidden('positions[]', $this->id, ['data-stage' => $this->lead_stage_id]) .
						$card_btn . "
					</div>	

					<div class='full'><a href='" . route('admin.lead.show', $this->id) . "' class='title-link'>" . str_limit($this->name, 30, '.') . "</a></div>

					<div class='full'>
						<div class='funnel-card-info'>
							<i class='mdi mdi-trophy-award warning'></i>
							<span data-toggle='tooltip' data-placement='bottom' title='Lead&nbsp;Owner'>" . str_limit($this->leadowner->name, 17, '.') . "</span>
						</div>

						<div class='funnel-card-info'>
							<i class='fa fa-circle'></i>
							<span data-toggle='tooltip' data-placement='bottom' title='Lead&nbsp;Source'>" . str_limit(non_property_checker($this->source, 'name'), 17, '.') . "</span>
						</div>
					</div>

					<div class='full'>
						<div class='funnel-card-info'>
							<i class='mdi mdi-domain'></i>
							<span data-toggle='tooltip' data-placement='bottom' title='Company'>" . str_limit($this->company, 17, '.') . "</span>
						</div>
						
						<div class='funnel-card-info'>
							<i class='fa fa-circle'></i>
							<span data-toggle='tooltip' data-placement='bottom' title='Created&nbsp;Date'>" . $this->readableDate('created_at') . "</span>
						</div>
					</div>" .
					$action_html . "					
				</div>";

		return $card;		
	}

	public function getKanbanCardHtmlAttribute()
	{
		$disable_css = !$this->auth_can_edit ? 'disable' : '';

		$card_html = "<li id='lead-" . $this->id . "' class='" . $disable_css . "'>" . 
						$this->kanban_card .
					 "</li>";

		return $card_html;
	}

	public function getNameHtmlAttribute()
	{
		$name = "<a href='" . route('admin.lead.show', $this->id) . "' class='lead-name'>" . $this->name . "</a>";
		$name.= empty($this->title) ? "" : "<br>";
		$name.= "<span class='sm-txt'>" . $this->title . "</span>";
		$name.= empty($this->company) ? "" : "<br>";
		$name.= "<span class='company-name sm-txt'>" . $this->company . "</span>" . $this->hidden_currency_info;
		return $name;
	}

	public function getCompanyHtmlAttribute()
	{
		$company_name = "<span class='company-name'>" . $this->company . "</span>";
		return $company_name . $this->hidden_currency_info;
	}	

	public function getCompleteNameAttribute()
	{
		$complete_name = $this->name;

		if(!is_null($this->company) && $this->company != '') :
			$complete_name .= ' - ' . $this->company;
		endif;

		return $complete_name;
	}

	public function getFullNameAttribute()
	{
		$full_name = $this->name;

		if(!is_null($this->company) && $this->company != '') :
			$full_name .= ' (' . $this->company . ')';
		endif;

		return $full_name;
	}

	public function getDealAmountAttribute()
	{
		return $this->items->sum('total') ? number_format($this->items->sum('total'), 2, '.', '') : null;
	}

	public function getDealValueAttribute()
	{
		return (float)$this->deal_amount;
	}

	public function getLeadScoreAttribute()
	{
		$lead_score = 0;
		$all_scores = LeadScore::all();

		foreach($all_scores as $single_score) :
			$is_rule_satisfied = true;

			foreach($single_score->rules as $rule) :
				if($rule->related_to == 'lead_property') :
					$is_rule_satisfied = $this->isRuleSatisfied($rule->attribute, $rule->condition, $rule->value);
					
					if(!$is_rule_satisfied) :
						break;
					endif;	
				endif;	
			endforeach;
			
			if($is_rule_satisfied) :
				$lead_score = $lead_score + ($single_score->score);
			endif;	
		endforeach;	

		$lead_score = floor($lead_score);

		if($lead_score < 0) :
			return 0;
		elseif($lead_score > 99) :
			return 99;
		endif;	

		return $lead_score;
	}

	public function isRuleSatisfied($attribute, $condition, $conditional_value)
	{
		$conditional_value = decode_if_json($conditional_value);

		if($condition != 'empty' && !not_null_empty($this->$attribute)) :
			return false;
		endif;	

		switch($condition) :
			case 'equal' :
				$conditional_value = (array)$conditional_value;
				return in_array($this->$attribute, $conditional_value);
			break;

			case 'not_equal' :
				$conditional_value = (array)$conditional_value;
				return !in_array($this->$attribute, $conditional_value);
			break;

			case 'contain' :
				return strpos_array($conditional_value, $this->$attribute);
			break;

			case 'not_contain' :
				return !strpos_array($conditional_value, $this->$attribute);
			break;

			case 'empty' :
				return empty($this->$attribute);
			break;

			case 'not_empty' :
				return !empty($this->$attribute);
			break;

			case 'less' :
				return ($this->$attribute < $conditional_value);
			break;

			case 'greater' :
				return ($this->$attribute > $conditional_value);
			break;

			case 'before' :
				$before_date = date("Y-m-d H:i:s", strtotime("-$conditional_value days"));
				return $this->sqlDate($attribute) < $before_date;
			break;

			case 'after' :
				$after_date = date("Y-m-d H:i:s", strtotime("+$conditional_value days"));
				return $this->sqlDate($attribute) > $after_date;
			break;

			case 'last' :
				$today = date("Y-m-d H:i:s");
				$last_date = date("Y-m-d H:i:s", strtotime("-$conditional_value days"));
				$attribute_date = $this->sqlDate($attribute);
				return ($attribute_date <= $today && $attribute_date >= $last_date);
			break;

			case 'next' :
				$today = date("Y-m-d H:i:s");
				$next_date = date("Y-m-d H:i:s", strtotime("+$conditional_value days"));
				$attribute_date = $this->sqlDate($attribute);
				return ($attribute_date >= $today && $attribute_date <= $next_date);
			break;

			default : return false;
		endswitch;	
	}

	public function getLeadScoreStatusAttribute()
	{
		if($this->lead_score >= config('setting.cold_lead_low') && $this->lead_score <= config('setting.cold_lead_up')) :
			$score_status = "<span class='btn btn-cold status xs'>" . config('setting.cold_lead_label') . " Lead</span>";
		elseif($this->lead_score >= config('setting.warm_lead_low') && $this->lead_score <= config('setting.warm_lead_up')) :
			$score_status = "<span class='btn btn-warm status xs'>" . config('setting.warm_lead_label') . " Lead</span>";
		elseif($this->lead_score >= config('setting.hot_lead_low') && $this->lead_score <= config('setting.hot_lead_up')) :
			$score_status = "<span class='btn btn-hot status xs'>" . config('setting.hot_lead_label') . " Lead</span>";
		else :
			$score_status = null;
		endif;	

		return $score_status;
	}

	public function getLeadScoreHtmlAttribute()
	{
		return $this->lead_score . '&nbsp;' . $this->lead_score_status;	
	}

	public function getLeadScoreLinkAttribute()
	{
		return "<a class='bold-txt'>" . $this->lead_score . "</a>";	
	}

	public function getLeadScoreCssAttribute()
	{
		if($this->lead_score >= config('setting.cold_lead_low') && $this->lead_score <= config('setting.cold_lead_up')) :
			return 'cold';
		elseif($this->lead_score >= config('setting.warm_lead_low') && $this->lead_score <= config('setting.warm_lead_up')) :
			return 'warm';
		elseif($this->lead_score >= config('setting.hot_lead_low') && $this->lead_score <= config('setting.hot_lead_up')) :
			return 'hot';
		else :
			return null;
		endif;	
	}

	public function getConvertedAttribute()
	{
		$converted = $this->leadstage->category == 'converted' ? 1 : 0;
		return $converted;
	}

	public function getIsConvertedAttribute()
	{
		if($this->converted && !is_null($this->converted_contact_id) && !is_null($this->converted_account_id)) :
			return true;
		endif;

		return false;
	}

	public function getStageHtmlAttribute()
	{
		if($this->is_converted) :
			$convert_stage = "<span class='btn btn-warning status'>" . non_property_checker($this->leadstage, 'name') . "</span>";
			return $convert_stage;
		endif;
		
		return non_property_checker($this->leadstage, 'name');	
	}

	public function getAuthCanConvertAttribute()
	{
		if($this->authCan('edit') && permit('convert.lead')) :
			return true;
		endif;	

		return false;
	}

	public function setAction()
	{
		return ['edit', 'delete', 'convert', 'send_email', 'send_SMS'];
	}

	public function setMassAction()
	{
		return ['mass_update', 'mass_delete', 'mass_convert', 'mass_email', 'mass_SMS'];
	}

	public function extendActionHtml($edit_permission = true)
	{
		$extend_action = '';

		if($this->auth_can_convert) :
			$extend_action .= "<li><a class='convert' editid='" . $this->id . "'><i class='mdi mdi-account-convert'></i> Convert</a></li>";
		endif;

		$extend_action .= "<li><a class='add-multiple' data-item='call' modal-title='Add Call Log' data-modalsize='medium' data-action='" . route('admin.call.store') . "' data-content='call.partials.form' data-default='client_type:lead|client_id:" . $this->id . "' save-new='false'><i class='lg mdi mdi-phone-plus'></i> Add Call Log</a></li>";
		$extend_action .= "<li><a class='add-multiple' data-item='task' data-action='" . route('admin.task.store') . "' data-content='task.partials.form' data-default='related_type:lead|related_id:" . $this->id . "' data-show='lead_id' save-new='false'><i class='fa fa-check-square'></i> Add Task</a></li>";
		$extend_action .= "<li><a class='add-multiple' data-item='event' data-action='" . route('admin.event.store') . "' data-content='event.partials.form' data-default='related:lead|lead_id:" . $this->id . "' data-show='lead_id' save-new='false'><i class='fa fa-calendar'></i> Add Event</a></li>";		

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
	public function leadowner()
	{
		return $this->belongsTo(Staff::class, 'lead_owner')->withTrashed();
	}

	public function source()
	{
		return $this->belongsTo(Source::class);
	}

	public function leadstage()
	{
		return $this->belongsTo(LeadStage::class, 'lead_stage_id');
	}

	public function currency()
	{
		return $this->belongsTo(Currency::class, 'currency_id');
	}

	public function country()
	{
		return $this->belongsTo(Country::class, 'country_code', 'code');
	}

	public function convertedAccount()
	{
		return $this->belongsTo(Account::class, 'converted_account_id');
	}

	public function convertedContact()
	{
		return $this->belongsTo(Contact::class, 'converted_contact_id');
	}

	// relation: morphMany
	public function socialmedia()
	{
	    return $this->morphMany(SocialMedia::class, 'linked');
	}

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
}