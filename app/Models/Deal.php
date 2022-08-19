<?php

namespace App\Models;

use App\Models\BaseModel;
use App\Models\Traits\OwnerTrait;
use App\Models\Traits\ModuleTrait;
use App\Models\Traits\FinanceTrait;
use App\Models\Traits\PosionableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class Deal extends BaseModel
{
	use SoftDeletes;
	use OwnerTrait;	
	use ModuleTrait;
	use FinanceTrait;
	use PosionableTrait;
	use RevisionableTrait;

	protected $table = 'deals';
	protected $fillable = ['account_id', 'contact_id', 'deal_owner', 'name', 'description', 'currency_id', 'amount', 'probability', 'closing_date', 'deal_pipeline_id', 'deal_stage_id', 'deal_type_id', 'source_id', 'campaign_id', 'access', 'recurring', 'position'];
	protected $appends = ['won', 'forecast', 'forecast_value', 'base_forecast_value'];
	protected $dates = ['deleted_at'];
	protected $revisionCreationsEnabled = true;
	protected static $fieldlist = ['access' => 'Access', 'account_id' => 'Account', 'amount' => 'Amount', 'campaign_id' => 'Campaign', 'closing_date' => 'Closing Date', 'contact_id' => 'Contact', 'currency_id' => 'Currency', 'name' => 'Deal Name', 'deal_owner' => 'Deal Owner', 'deal_pipeline_id' => 'Deal Pipeline', 'deal_stage_id' => 'Deal Stage', 'deal_type_id' => 'Deal Type', 'description' => 'Description', 'probability' => 'Probability', 'source_id' => 'Source'];
	protected static $mass_fieldlist = ['access', 'account_id', 'amount', 'campaign_id', 'closing_date', 'contact_id', 'name', 'deal_owner', 'deal_pipeline_id', 'deal_stage_id', 'deal_type_id', 'description', 'probability', 'source_id'];

	public static function validate($data)
	{
		$owner_required = "required";
		$account_id = array_key_exists('account_id', $data) ? $data['account_id'] : 0;
		$deal_pipeline_id = array_key_exists('deal_pipeline_id', $data) ? $data['deal_pipeline_id'] : 0;

		if(isset($data['id'])) :
			$owner_required = $data['change_owner'] ? "required" : '';
		endif;	

		$rules = ["name"			=> "required|max:200",
				  "amount"			=> "min:0|numeric",
				  "currency_id"		=> "required|exists:currencies,id,deleted_at,NULL",
				  "account_id"		=> "required|exists:accounts,id,deleted_at,NULL",
				  "contact_id"		=> "exists:contacts,id,account_id,$account_id,deleted_at,NULL",
				  "deal_owner"		=> "required|exists:users,linked_id,linked_type,staff,status,1,deleted_at,NULL",
				  "closing_date"	=> "required|date",
				  "deal_pipeline_id"=> "required|exists:deal_pipelines,id,deleted_at,NULL",
				  "deal_stage_id"	=> "required|exists:deal_stages,id,deleted_at,NULL|exists:pipeline_stages,deal_stage_id,deal_pipeline_id,$deal_pipeline_id",
				  "probability"		=> "numeric|min:0|max:100",
				  "deal_type_id"	=> "exists:deal_types,id,deleted_at,NULL",
				  "source_id"		=> "exists:sources,id,deleted_at,NULL",
				  "campaign_id"		=> "exists:campaigns,id,deleted_at,NULL",
				  "description"		=> "max:65535",
				  "access"			=> "required|in:private,public,public_rwd"];

		return \Validator::make($data, $rules);
	}

	public static function singleValidate($data, $deal = null)
	{
		$name_required = array_key_exists('name', $data) ? "required" : '';
		$currency_required = array_key_exists('currency_id', $data) ? "required" : '';
		$account_required = array_key_exists('account_id', $data) ? "required" : '';
		$owner_required = $data['change_owner'] ? "required" : '';
		$date_required = array_key_exists('closing_date', $data) ? "required" : '';
		$pipeline_required = array_key_exists('deal_pipeline_id', $data) ? "required" : '';
		$stage_required = array_key_exists('deal_stage_id', $data) ? "required" : '';
		$access_required = array_key_exists('access', $data) ? "required" : '';

		$account_id = !is_null($deal) ? $deal->account_id : null_if_not_key('account_id', $data);
		$deal_pipeline_id = !is_null($deal) ? $deal->deal_pipeline_id : null_if_not_key('deal_pipeline_id', $data);

		$rules = ["name"			=> "$name_required|max:200",
				  "amount"			=> "min:0|numeric",
				  "currency_id"		=> "$currency_required|exists:currencies,id,deleted_at,NULL",
				  "account_id"		=> "$account_required|exists:accounts,id,deleted_at,NULL",
				  "contact_id"		=> "exists:contacts,id,account_id,$account_id,deleted_at,NULL",
				  "deal_owner"		=> "$owner_required|exists:users,linked_id,linked_type,staff,status,1,deleted_at,NULL",
				  "closing_date"	=> "$date_required|date",
				  "deal_pipeline_id"=> "$pipeline_required|exists:deal_pipelines,id,deleted_at,NULL",
				  "deal_stage_id"	=> "$stage_required|exists:deal_stages,id,deleted_at,NULL|exists:pipeline_stages,deal_stage_id,deal_pipeline_id,$deal_pipeline_id",
				  "probability"		=> "numeric|min:0|max:100",
				  "deal_type_id"	=> "exists:deal_types,id,deleted_at,NULL",
				  "source_id"		=> "exists:sources,id,deleted_at,NULL",
				  "campaign_id"		=> "exists:campaigns,id,deleted_at,NULL",
				  "description"		=> "max:65535",
				  "access"			=> "$access_required|in:private,public,public_rwd"];

		return \Validator::make($data, $rules);
	}

	public static function massValidate($data)
	{
		$valid_field = implode(',', self::massfieldlist());
		$access_required = $data['related'] == 'access' ? 'required' : '';
		$account_required = $data['related'] == 'account_id' ? 'required' : '';
		$owner_required = $data['related'] == 'deal_owner' ? 'required' : '';
 		$currency_required = $data['related'] == 'amount' ? 'required' : ''; 		
 		$name_required = $data['related'] == 'name' ? 'required' : '';
 		$date_required = $data['related'] == 'closing_date' ? 'required' : '';
 		$pipeline_required = $data['related'] == 'deal_pipeline_id' ? 'required' : '';
 		$stage_required = $data['related'] == 'deal_stage_id' ? 'required' : '';
 		$probability_required = $data['related'] == 'probability' ? 'required' : '';

		$rules = ["related"			=> "required|in:$valid_field",
				  "name"			=> "$name_required|max:200",
				  "amount"			=> "min:0|numeric",
				  "currency_id"		=> "$currency_required|exists:currencies,id,deleted_at,NULL",
				  "account_id"		=> "$account_required|exists:accounts,id,deleted_at,NULL",
				  "contact_id"		=> "exists:contacts,id,deleted_at,NULL",
				  "deal_owner"		=> "$owner_required|exists:users,linked_id,linked_type,staff,status,1,deleted_at,NULL",
				  "closing_date"	=> "$date_required|date",
				  "deal_pipeline_id"=> "$pipeline_required|exists:deal_pipelines,id,deleted_at,NULL",
				  "deal_stage_id"	=> "$stage_required|exists:deal_stages,id,deleted_at,NULL",
				  "probability"		=> "$probability_required|numeric|min:0|max:100",
				  "deal_type_id"	=> "exists:deal_types,id,deleted_at,NULL",
				  "source_id"		=> "exists:sources,id,deleted_at,NULL",
				  "campaign_id"		=> "exists:campaigns,id,deleted_at,NULL",
				  "description"		=> "max:65535",
				  "access"			=> "$access_required|in:private,public,public_rwd"];

		return \Validator::make($data, $rules);
	}

	public static function importValidate($data)
	{
		$status = true;
		$errors = [];

		if(!in_array('name', $data)) :
			$status = false;
			$errors[] = 'The deal name field is required.';
		endif;

		if(!in_array('account_id', $data)) :
			$status = false;
			$errors[] = 'The account field is required.';
		endif;

		$outcome = ['status' => $status, 'errors' => $errors];

		return $outcome;
	}

	public static function kanbanValidate($data)
	{
		$picked_exists = '';
		if(array_key_exists('picked', $data) && $data['picked'] != 0) :
			$picked_exists = 'exists:deals,id,deleted_at,NULL';
		endif;
			
		$rules = ["source"	=> "required|in:deal",
				  "id"		=> "required|exists:deals,id,deleted_at,NULL",
				  "picked"	=> "required|different:id|$picked_exists",
				  "field"	=> "required|in:deal_stage_id",
				  "stage"	=> "required|exists:deal_stages,id,deleted_at,NULL",
				  "ordertype" => "required|in:desc"];

		return \Validator::make($data, $rules);
	}

	public static function kanbanCardValidate($data)
	{
		$pipeline_id = array_key_exists('pipelineId', $data) ? $data['pipelineId'] : 0;

		$rules = ["pipelineId"	=> "required|exists:deal_pipelines,id,deleted_at,NULL",
				  "stageId"		=> "required|exists:deal_stages,id,deleted_at,NULL|exists:pipeline_stages,deal_stage_id,deal_pipeline_id,$pipeline_id",
				  "ids"			=> "required|array|exists:deals,id,deal_pipeline_id,$pipeline_id,deleted_at,NULL"];

		return \Validator::make($data, $rules);
	}

	public static function getKanbanBreadcrumb()
	{
		$pipeline = DealPipeline::getCurrentPipeline();
		$pipelines_list = DealPipeline::orderBy('position')->get()->pluck('name', 'id')->toArray();

		$breadcrumb = "<ol class='breadcrumb'>";

		$breadcrumb .= "<li><a href='" . route('admin.deal.index') . "'>Deals</a></li>";

		$breadcrumb .= "<li>" . 
							\Form::open(['route' => 'admin.deal.pipeline.kanban', 'method' => 'post']) .
								\Form::select('deal_pipeline_id', $pipelines_list, $pipeline->id, ['class' => 'dealpipeline form-control breadcrumb-select', 'disabled' => 'disabled', 'data-kanban-select' => 'true']) . 
							\Form::close() .
						"</li>";

		$breadcrumb .= "<li class='active'>" . 
							\Form::open(['route' => 'admin.deal.pipeline.kanban', 'method' => 'post']) .
								"<select name='view' class='form-control breadcrumb-select'>
									<optgroup label='SYSTEM'>
										<option value='my_open'>My Open Deals</option>
										<option value='my_overdue'>My Overdue Deals</option>
										<option value='all'>All Deals</option>
										<option value='open'>All Open Deals</option>
										<option value='won'>Won Deals</option>
										<option value='lost'>Lost Deals</option>					
									</optgroup>	

									<optgroup label='MY VIEWS'>
										<option value='1'>Important Deals</option>		
									</optgroup>	
								</select>" .
							\Form::close() .	
						"</li></ol>";

		return $breadcrumb;
	}

	public static function getKanbanData()
	{
		$outcome = [];

		$pipeline = DealPipeline::getCurrentPipeline();
		$stages = DealStage::getCurrentStages();

		foreach($stages as $stage) :
			$key = 'dealstage-' . $pipeline->id . '-' . $stage->id;
			$outcome[$key]['data'] = self::getAuthViewData()->where('deal_pipeline_id', $pipeline->id)->where('deal_stage_id', $stage->id)->latest('position')->get();			
			$outcome[$key]['quick_data'] = $outcome[$key]['data']->take(5);			
			$outcome[$key]['stage'] = $stage->toArray();
			$outcome[$key]['stage']['pipeline_id'] = $pipeline->id;
			$total_amount = Currency::totalConvertToBaseCurrency('deal', $outcome[$key]['data']->pluck('id')->toArray(), 'amount');
			$revenue_forecast = $stage->forecast ? ($total_amount['total'] * $stage->probability) / 100 : 0;
			$revenue_forecast_html = Currency::amountValueBaseCurrencyHtml($revenue_forecast);
			$outcome[$key]['stage']['total_amount'] = $total_amount['total'];
			$outcome[$key]['stage']['total_amount_html'] = $total_amount['html'];
			$outcome[$key]['stage']['forecast'] = $revenue_forecast;
			$forecast_html_ext = $stage->forecast ? '(~' . $stage->probability . '% of total ' . $total_amount['html'] . ')' : '(Forecast is disabled in the stage)';
			$outcome[$key]['stage']['forecast_html'] = no_space('Revenue forecast = ' . $revenue_forecast_html . '<br>'. $forecast_html_ext);
			$outcome[$key]['stage']['load_status'] = $outcome[$key]['data']->count() > 5 ? 'true' : 'false';
			$outcome[$key]['stage']['load_url'] = route('admin.deal.kanban.card', [$pipeline->id, $stage->id]);
		endforeach;

		return $outcome;
	}

	public static function getKanbanHtml()
	{
		$deals_kanban = self::getKanbanData();

		$html = "<div class='full funnel-container scroll-box-x only-thumb' data-source='deal' data-stage='deal_stage_id' data-order='desc'>";
			
		foreach($deals_kanban as $key => $deal_kanban) :				
			$html .= "<div id='" . $key . "' class='funnel-stage' data-stage='" . $deal_kanban['stage']['id'] . "' data-pipeline='" . $deal_kanban['stage']['pipeline_id'] . "' data-count='" . count($deal_kanban['data']) . "' data-load='" . $deal_kanban['stage']['load_status'] . "' data-url='" . $deal_kanban['stage']['load_url'] . "'>";
			$html .= "<div class='funnel-stage-header'>";
			$html .= "<h3 class='title double-line'>";
			$html .= $deal_kanban['stage']['name'] . " <span class='shadow count'>(" . count($deal_kanban['data']) . ")</span><br>";
			$html .= "<p class='sub-info'>" . $deal_kanban['stage']['total_amount_html'] . "</p>";
			$html .= '<p class="stat" data-toggle="tooltip" data-placement="left" data-html="true" title="' . $deal_kanban['stage']['forecast_html'] . '">' . $deal_kanban['stage']['probability'] . '<i>%</i></p>';
			$html .= "</h3><div class='funnel-arrow bullet'><span class='bullet'></span></div></div>";
			$html .= "<div class='funnel-card-container scroll-box only-thumb' data-card-type='deal'>";								
			$html .= "<ul class='kanban-list'>";
			$html .= "<div id='" . $key . '-cards' . "' class='full li-container'>";

			foreach($deal_kanban['quick_data'] as $deal) :
				$html .= $deal->kanban_card_html;
			endforeach;
						
			$html .= "</div>";	
			$html .= "<span class='content-loader bottom'></span>";	
			$html .= "</ul></div></div>";
		endforeach;

		$html .= "<span class='content-loader all'></span>";
		$html .= "</div>";
		$html .= "<a class='funnel-container-arrow left'><i class='fa fa-chevron-left'></i></a>";
		$html .= "<a class='funnel-container-arrow right'><i class='fa fa-chevron-right'></i></a>";

		return $html;
	}

	public static function getKanbanStageCount()
	{
		return self::getKanbanStageHeaderInfo()['count'];
	}

	public static function getKanbanStageHeaderInfo()
	{
		$count_array = [];
		$total_amount_array = [];
		$revenue_forecast_array = []; 

		$pipeline = DealPipeline::getCurrentPipeline();
		$stages = DealStage::getCurrentStages();

		foreach($stages as $stage) :
			$key = 'dealstage-' . $pipeline->id . '-' . $stage->id;
			$data = self::getAuthViewData()->where('deal_pipeline_id', $pipeline->id)->where('deal_stage_id', $stage->id)->latest('position')->get();			
			$total_amount = Currency::totalConvertToBaseCurrency('deal', $data->pluck('id')->toArray(), 'amount');
			$revenue_forecast = $stage->forecast ? ($total_amount['total'] * $stage->probability) / 100 : 0;
			$revenue_forecast_html = Currency::amountValueBaseCurrencyHtml($revenue_forecast);
			$count = $data->count();
			
			$count_array[$key] = '(' . $count . ')';
			$total_amount_array[$key] = $total_amount['html'];
			$forecast_html_ext = $stage->forecast ? '(~' . $stage->probability . '% of total ' . $total_amount['html'] . ')' : '(Forecast is disabled in the stage)';
			$revenue_forecast_array[$key] = no_space('Revenue forecast = ' . $revenue_forecast_html . '<br>'. $forecast_html_ext);
		endforeach;	

		$outcome = ['count' => $count_array, 'subinfo' => $total_amount_array, 'tooltip' => $revenue_forecast_array];

		return $outcome;
	}

	public static function getTotalInfo()
	{
		$pipeline = DealPipeline::getCurrentPipeline();		
		$all_deal = self::getAuthViewData()->where('deal_pipeline_id', $pipeline->id)->get();
		$get_total_amount = Currency::totalConvertToBaseCurrency('deal', $all_deal->pluck('id')->toArray(), 'amount');
		$total_forecast = $all_deal->sum('base_forecast_value');
		$total_forecast_html = Currency::amountValueBaseCurrencyHtml($total_forecast);

		$outcome = ['total_deal' 		=> $all_deal->count(),
					'total_amount'		=> $get_total_amount['total'],
					'total_amount_html'	=> $get_total_amount['html'],
					'total_forecast'	=> $total_forecast,
					'total_forecast_html' => $total_forecast_html];

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
							  'projects'	=> 'Projects',
							  'estimates'	=> 'Estimates',
							  'invoices'	=> 'Invoices',	  	
							  'items'		=> 'Items', 
							  'files'		=> 'Files',
							  'timeline'	=> 'Timeline',
							  'statistics'	=> 'Statistics'];

		return $information_types;
	}

	public static function getTableFormat()
	{
		$table = ['thead' => ['name', 'amount', 'closing&nbsp;date', 'pipeline', 'stage', 'owner'], 'checkbox' => self::allowMassAction(), 'action' => self::allowAction()];
		$table['json_columns'] = table_json_columns(['checkbox', 'name', 'amount' => ['className' => 'align-r'], 'closing_date', 'pipeline', 'deal_stage', 'deal_owner', 'action'], self::hideColumns());
		
		return $table;
	}

	public static function getTableData($request)
	{
		$deals = self::latest('id')->get();

		return \Datatables::of($deals)
				->addColumn('checkbox', function($deal)
				{
					return $deal->checkbox_html;
				})
				->editColumn('name', function($deal)
				{
					return $deal->name_html;
				})
				->editColumn('amount', function($deal)
				{
					return $deal->amountHtml('amount');
				})
				->editColumn('closing_date', function($deal)
				{
					return $deal->readableDateHtml('closing_date');
				})
				->addColumn('pipeline', function($deal)
				{
					return $deal->pipeline->name;
				})
				->addColumn('deal_stage', function($deal)
				{
					return $deal->stage_and_probability;
				})
				->editColumn('deal_owner', function($deal)
				{
					return $deal->owner->profile_html;
				})
				->addColumn('action', function($deal)
				{
					$action_permission = ['edit' => permit('deal.edit'), 'delete' => permit('deal.delete')];							
					return $deal->getCompactActionHtml('Deal', null, 'admin.deal.destroy', $action_permission);
				})
				->make(true);
	}

	public static function getStageHistoryData($request, $deal)
	{
		return \Datatables::of($deal->stage_history)
				->addColumn('stage_name', function($history)
				{
					return $history->stage_name;
				})
				->addColumn('amount', function($history) use ($deal)
				{
					return $deal->amountValueHtml($history->amount);
				})
				->addColumn('probability', function($history)
				{
					return $history->probability;
				})
				->addColumn('expected_revenue', function($history) use ($deal)
				{
					$revenue = ($history->amount * $history->probability) / 100;
					return $deal->amountValueHtml($revenue);					
				})	
				->addColumn('closing_date', function($history)
				{
					return readable_date($history->closing_date);
				})
				->addColumn('duration', function($history)
				{
					return !is_null($history->duration) ? $history->duration . " <span class='c-shadow'>d</span>" : null;
				})
				->addColumn('modified_at', function($history)
				{
					return readable_date_html($history->modified_at, true);
				})
				->addColumn('modified_by', function($history)
				{
					return $history->modified_by->name;
				})
				->make(true);
	}

	/*
	|--------------------------------------------------------------------------
	| SCOPES
	|--------------------------------------------------------------------------
	*/
	public function scopeReadableIdentifier($query, $name)
	{
		return $query->where('name', $name);
	}

	/*
	|--------------------------------------------------------------------------
	| MUTATOR
	|--------------------------------------------------------------------------
	*/
	public function setProbabilityAttribute($value)
    {
    	$value = (int)$value == (float)$value ? (int)$value : number_format($value, 2, '.', '');
        $this->attributes['probability'] = $value;
    }

    public function setContactIdAttribute($value)
    {
    	if(isset($this->id) && not_null_empty($value) && !$this->participants()->where('contact_id', $value)->count()) :
    		$this->participants()->attach($value);
    	endif;	

    	$this->attributes['contact_id'] = $value;
    }

	/*
	|--------------------------------------------------------------------------
	| ACCESSOR
	|--------------------------------------------------------------------------
	*/
	public function getNameHtmlAttribute($show_account = true, $show_contact = true)
	{
		$name = "<a href='$this->show_route'>" . $this->name . "</a>";
		
		if(!isset($show_account) || (isset($show_account) && $show_account == true)) :
			$name.= "<br><a class='sm-txt' href='" . route('admin.account.show', $this->account_id) . "'>" . $this->account->account_name . "</a>";
		endif;

		if(!isset($show_contact) || (isset($show_contact) && $show_contact == true)) :
			$name.= is_null($this->contact_id) ? "" : "<br>";
			$name.= "<span class='sm-txt'>" . non_property_checker($this->contact, 'name') . "</span><br>";
		endif;

		return $name;
	}

	public function getStageAndProbabilityAttribute()
	{
		return "<span class='shadow' data-toggle='tooltip' data-placement='top' title='Probability'>" . $this->probability . "%</span> " . $this->stage->name;
	}

	public function getAmountFormatAttribute()
	{
		return number_format($this->attributes['amount'], 2, $this->currency->decimal_separator, $this->currency->thousand_separator);
	}

	public function getWonAttribute()
	{
		$won = $this->stage->probability == 100 ? 1 : 0;
		return $won;
	}

	public function getClientDefaultAttribute()
	{
		$client_default = is_null($this->contact_id) ? "" : "|client_type:contact|client_id:$this->contact_id";
		return $client_default;
	}

	public function getProbabilityAmountAttribute()
	{
		$html = $this->probability . '%';
		
		if($this->forecast_value > 0) :
			$html .= "<span class='c-shadow m-left-5'>(" . $this->amountHtml('forecast_value') . ")</span>";
		endif;	

		return $html;
	}

	public function getClassifiedProbabilityAttribute()
	{
		if($this->probability >= 0 && $this->probability <= 30) :
			$css = 'cold';
		elseif($this->probability > 31 && $this->probability <= 70) :
			$css = 'warm';
		elseif($this->probability > 70 && $this->probability <= 100) :
			$css = 'hot';
		else :
			$css = 'cold';
		endif;
		$html = "<span class='" . $css . "'>" . $this->probability . "<i>%</i></span>";
		return $html;
	}

	public function getForecastValueAttribute()
	{
		if($this->forecast) :
			$value = ($this->amount * $this->probability) / 100;
			return $value;
		endif;
		
		return 0;	
	}

	public function getBaseForecastValueAttribute()
	{
		$outcome = Currency::exchangeToBase($this->currency_id, $this->forecast_value);
		return $outcome;
	}

	public function getForecastAttribute()
	{
		$pipeline_stage = \DB::table('pipeline_stages')->where('deal_pipeline_id', $this->deal_pipeline_id)->where('deal_stage_id', $this->deal_stage_id)->get();
		$forecast = count($pipeline_stage) ? $pipeline_stage[0]->forecast : 1;
		
		return $forecast;
	}

	public function getItemTotalAttribute()
	{
		return number_format($this->items->sum('total'), 2, $this->currency->decimal_separator, $this->currency->thousand_separator);
	}

	public function getStagelineHtmlAttribute()
	{
		$stages = $this->pipeline->stages()->orderByRaw("FIELD(category, 'open', 'closed_won', 'closed_lost')")->orderBy("pipeline_stages.position")->get();
		$stage_ids = $stages->pluck('id')->toArray();
		$deal_stage_category = $this->stage->category;
		$deal_stage_key = array_search($this->deal_stage_id, $stage_ids);
		$pg_width_css = "style='width: " . (100 / $stages->count()) . "%;'";
		$pg_line_css = "style='min-width: " . (25 * $stages->count()) . "px;'";
		$pg_container_css = "style='min-width: " . (25 * ($stages->count() + 1)) . "px;'";

		$stageline_html = "<div id='deal-stage-progress' data-id='" . $this->id . "' class='progress-line-container' $pg_container_css>";
		$stageline_html .= "<div class='pg-label start'>
								<div class='pg-label-info'>
									<span class='info'><strong>Created:</strong> <span  data-toggle='tooltip' data-placement='top' title='" . no_space($this->readableDateAmPm('created_at')) . "'>" . time_short_form($this->created_at->diffForHumans()) . "</span></span>
								</div>	
							</div>";

		$stageline_html .= "<div class='progress-line' $pg_line_css>";
		foreach($stages as $stage_key => $stage) :
			$open_pg_css = 'checked';
			$closed_pg_css = '';
			$current_stage = false;

			if($deal_stage_category == 'open' && $stage_key >= $deal_stage_key) :
				$open_pg_css = $stage_key == $deal_stage_key ? 'dot' : 'circle';
				$current_stage = ($open_pg_css == 'dot'); 
			elseif($deal_stage_category == 'closed_won' && $deal_stage_key == $stage_key) :
				$closed_pg_css = 'won';
				$current_stage = true;
			elseif($deal_stage_category == 'closed_lost' && $deal_stage_key == $stage_key) :
				$closed_pg_css = 'lost';
				$current_stage = true;
			endif;

			$current_html = '';
			if($current_stage) :
				$current_html = "<span class='current'>" . no_space($stage->name) . "</span>";
			endif;	

			switch($stage->category) :
				case 'open' :
					$html = "<div class='pg $open_pg_css' $pg_width_css>
								<span class='icon' data-toggle='tooltip' data-placement='top' title='" . no_space($stage->name . ' - ' . $stage->probability . '%') . "' data-stage='" . $stage->name . "'></span>
								<input type='hidden' value='" . $stage->id . "'>
								<span class='line'></span> $current_html
							</div>";
				break;

				case 'closed_won' :
					$html = "<div class='pg ring thumbs-up $closed_pg_css' $pg_width_css>
								<span class='icon' data-toggle='tooltip' data-placement='top' title='" . no_space($stage->name . ' - ' . $stage->probability . '%') . "' data-stage='" . $stage->name . "'></span>
								<input type='hidden' value='" . $stage->id . "'>
								<span class='line'></span>  $current_html
						    </div>";
				break;

				case 'closed_lost' :
					$html = "<div class='pg ring thumbs-down $closed_pg_css' $pg_width_css>
								<span class='icon' data-toggle='tooltip' data-placement='top' title='" . no_space($stage->name . ' - ' . $stage->probability . '%') . "' data-stage='" . $stage->name . "'></span>
								<input type='hidden' value='" . $stage->id . "'>
								<span class='line'></span>  $current_html
							</div>";
				break;

				default : $html = '';
			endswitch;	

			$stageline_html .= $html;
		endforeach;
		$stageline_html .= "</div>";

		$stageline_html .= "<div class='pg-label end'>
								<div class='pg-label-info'>
									<i class='icon fa fa-calendar-times-o'></i>
									<span class='info'><strong>Closing date:</strong> <span data-toggle='tooltip' data-placement='top' title='" . no_space($this->readableDateAmPm('closing_date')) . "'>" . time_short_form($this->carbonDate('closing_date')->diffForHumans()) . "</span></span>
								</div>	
							</div></div>";

		return $stageline_html;					
	}

	public function getStageHistoryAttribute()
	{
		$stage_histories = $this->revisionHistory->where('key', 'deal_stage_id');
		$stage_histories = array_values($stage_histories->all());
		$outcome = [];

		if(!count($stage_histories)) :
			$outcome[] = (object)['stage_name' => $this->stage->name, 'amount' => $this->amount, 'probability' => $this->probability, 'closing_date' => $this->closing_date, 'duration' => null, 'modified_at' => $this->created_at, 'modified_by' => $this->createdBy()->linked];
			return collect($outcome);
		endif;

		foreach($stage_histories as $key => $stage_history) :
			$duration = null;
			$prev_amount = null;
			$prev_closing_date = null;
			$probability = $this->probability;		
			$amount = $this->amount;	
			$closing_date = $this->closing_date;

			if($key + 1 < count($stage_histories)) :
				$next_history_id = $stage_histories[$key + 1]->id;
				$duration = $stage_histories[$key + 1]->created_at->diffInDays($stage_history->created_at);
				$prev_probability = Revision::getHistoryRow('deal', $this->id, '<', $next_history_id, 'probability', false);
				$probability = isset($prev_probability) ? $prev_probability->old_value : $probability;
				$prev_amount = Revision::getHistoryRow('deal', $this->id, '<', $next_history_id, 'amount', false);					   
				$next_amount = Revision::getHistoryRow('deal', $this->id, '>', $next_history_id, 'amount');					   
				$prev_closing_date = Revision::getHistoryRow('deal', $this->id, '<', $next_history_id, 'closing_date', false);
				$next_closing_date = Revision::getHistoryRow('deal', $this->id, '>', $next_history_id, 'closing_date');							 		   

				if(isset($prev_amount)) :
					$amount = $prev_amount->new_value;
				elseif(isset($next_amount)) :
					$amount = $next_amount->old_value;
				endif;

				if(isset($prev_closing_date)) :
					$closing_date = $prev_closing_date->new_value;
				elseif(isset($next_closing_date)) :
					$closing_date = $next_closing_date->old_value;
				endif;  
			endif;	

			if($key == 0) :
				$first_amount = isset($prev_amount) ? $prev_amount->old_value : $amount;
				$first_closing_date = isset($prev_closing_date) ? $prev_closing_date->old_value : $closing_date;
				$first_deal_stage = DealStage::withTrashed()->find($stage_history->old_value);

				if(isset($first_deal_stage)) :
					$close_probability = Revision::getHistoryRow('deal', $this->id, '<', $stage_history->id, 'probability', false);						     
					$first_probability = isset($close_probability) ? $close_probability->old_value : $probability;					     
					$created = Revision::where('revisionable_type', 'deal')
									   ->where('revisionable_id', $this->id)
									   ->where('key', 'created_at')
									   ->first();		

					$modified_by = is_null($created) ? $this->createdBy() : $created->userResponsible();
					$created = is_null($created) ? $this : $created;
					$first_duration = $stage_history->created_at->diffInDays($created->created_at);
					$outcome[] = (object)['stage_name' => $first_deal_stage->name, 'amount' => $first_amount, 'probability' => string_number_convert($first_probability), 'closing_date' => $first_closing_date, 'duration' => $first_duration, 'modified_at' => $created->updated_at, 'modified_by' => $modified_by->linked];
				endif;	
			endif;

			$deal_stage = DealStage::withTrashed()->find($stage_history->new_value);
			if(isset($deal_stage)) :
				$outcome[] = (object)['stage_name' => $deal_stage->name, 'amount' => (float)$amount, 'probability' => string_number_convert($probability), 'closing_date' => $closing_date, 'duration' => $duration, 'modified_at' => $stage_history->updated_at, 'modified_by' => $stage_history->userResponsible()->linked];
			endif;	
		endforeach;	

		$outcome = array_reverse($outcome);
		return collect($outcome);
	}

	public function getKanbanStageKeyAttribute()
	{
		return 'dealstage-' . $this->deal_pipeline_id . '-' . $this->deal_stage_id;
	}

	public function getKanbanCardKeyAttribute()
	{
		return 'deal-' . $this->id;
	}

	public function getKanbanCardAttribute()
	{
		$action = '';
		$action_html = '';
		$card_btn = '';

		if($this->auth_can_delete) :
			$action .= "<div class='funnel-btn'>" .							
							\Form::open(['route' => ['admin.deal.destroy', $this->id], 'method' => 'delete']) .
								\Form::hidden('id', $this->id) .
								"<button type='submit' class='delete'><i class='mdi mdi-delete'></i></button>" .
				  			\Form::close() . 
					   "</div>";
		endif;	

		$action .= "<div class='funnel-btn dropdown dark'>
						<a class='dropdown-toggle' animation='fadeIn|fadeOut' data-toggle='dropdown' aria-expanded='false'><i class='mdi mdi-plus-circle-multiple-outline'></i></a>

						<div class='dropdown-menu up-caret'>		
							<span><a class='add-multiple' data-item='call' data-action='" . route('admin.call.store') . "' data-content='call.partials.form' data-default='related_type:deal|related_id:$this->id" . $this->client_default . "' save-new='false'><i class='lg mdi mdi-phone-plus'></i> Add Call Log</a></span>   
							<span><a class='add-multiple' data-item='task' data-action='" . route('admin.task.store') . "' data-content='task.partials.form' data-default='related_type:deal|related_id:" . $this->id . "' data-show='deal_id' save-new='false'><i class='fa fa-check-square'></i> Add Task</a></span>    			
							<span><a class='add-multiple' data-item='event' data-action='" . route('admin.event.store') . "' data-content='event.partials.form' data-default='related:deal|deal_id:" . $this->id . "' data-show='deal_id' save-new='false'><i class='fa fa-calendar'></i> Add Event</a></span>							
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

		if($this->auth_can_edit) :
			$action .= "<div class='funnel-btn'>
							<a class='edit' editid='" . $this->id . "' data-url='" . route('admin.deal.index') . "'><i class='fa fa-pencil'></i></a>
						</div>";
		endif;	

		if($action != '') :
			$card_btn = "<a class='funnel-bottom-btn'><i class='fa fa-ellipsis-v md'></i></a>";
			$action_html = "<div class='full funnel-btn-group'>" . $action . "</div>";
		endif;	

		$card = "<div class='funnel-card has-currency-info' data-init-stage='" . $this->deal_stage_id . "'>
					" . $this->hidden_currency_info . "

					<div class='funnel-top-btn'>" .
						\Form::hidden('positions[]', $this->id, ['data-stage' => $this->deal_stage_id]) .
						$card_btn . "
					</div>	

					<div class='full'><a href='" . route('admin.deal.show', $this->id) . "' class='title-link'>" . str_limit($this->name, 30, '.') . "</a></div>

					<div class='full'>
						<div class='funnel-card-info'>
							<i class='mdi mdi-trophy-award warning'></i>
							<span data-toggle='tooltip' data-placement='bottom' title='" . no_space('Deal Owner') . "'>" . str_limit($this->owner->name, 17, '.') . "</span>
						</div>

						<div class='funnel-card-info'>
							<i class='fa fa-circle'></i>
							<span data-toggle='tooltip' data-placement='bottom' title='" . no_space('Deal Amount') . "'>" . $this->amountTooltipHtml('amount', 13) . "</span>
						</div>
					</div>

					<div class='full'>
						<div class='funnel-card-info'>
							<i class='mdi mdi-domain'></i>
							<span data-toggle='tooltip' data-placement='bottom' title='Account'>" . str_limit($this->account->name, 17, '.') . "</span>
						</div>
						
						<div class='funnel-card-info'>
							<i class='fa fa-circle'></i>
							<span data-toggle='tooltip' data-placement='bottom' title='Closing&nbsp;Date'>" . $this->readableDate('closing_date') . "</span>
						</div>
					</div>" .
					$action_html . "					
				</div>";

		return $card;		
	}

	public function getKanbanCardHtmlAttribute()
	{
		$disable_css = !$this->auth_can_edit ? 'disable' : '';

		$card_html = "<li id='deal-" . $this->id . "' class='" . $disable_css . "'>" . 
						$this->kanban_card .
					 "</li>";

		return $card_html;
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

		$extend_action .= "<li><a class='add-multiple' data-item='call' modal-title='Add Call Log' data-modalsize='medium' data-action='" . route('admin.call.store') . "' data-content='call.partials.form' data-default='related_type:deal|related_id:$this->id" . $this->client_default . "' save-new='false'><i class='lg mdi mdi-phone-plus'></i> Add Call Log</a></li>";
		$extend_action .= "<li><a class='add-multiple' data-item='task' data-action='" . route('admin.task.store') . "' data-content='task.partials.form' data-default='related_type:deal|related_id:" . $this->id . "' data-show='deal_id' save-new='false'><i class='fa fa-check-square'></i> Add Task</a></li>";
		$extend_action .= "<li><a class='add-multiple' data-item='event' data-action='" . route('admin.event.store') . "' data-content='event.partials.form' data-default='related:deal|deal_id:" . $this->id . "' data-show='deal_id' save-new='false'><i class='fa fa-calendar'></i> Add Event</a></li>";

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
	public function owner()
	{
		return $this->belongsTo(Staff::class, 'deal_owner')->withTrashed();
	}

	public function account()
	{
		return $this->belongsTo(Account::class);
	}

	public function contact()
	{
		return $this->belongsTo(Contact::class);
	}

	public function pipeline()
	{
		return $this->belongsTo(DealPipeline::class, 'deal_pipeline_id');
	}

	public function stage()
	{
		return $this->belongsTo(DealStage::class, 'deal_stage_id');
	}

	public function type()
	{
		return $this->belongsTo(DealType::class, 'deal_type_id');
	}

	public function source()
	{
		return $this->belongsTo(Source::class);
	}

	public function campaign()
	{
		return $this->belongsTo(Campaign::class);
	}

	public function currency()
	{
		return $this->belongsTo(Currency::class, 'currency_id');
	}

	// relation: hasMany
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

	// relation: morphMany
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

	public function participants()
	{
		return $this->morphToMany(Contact::class, 'linked', 'participant_contacts')->withPivot('linked_type');
	}
}