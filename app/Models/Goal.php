<?php

namespace App\Models;

use App\Models\BaseModel;
use App\Models\Traits\FinanceTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class Goal extends BaseModel
{
	use SoftDeletes;
	use RevisionableTrait;
	use FinanceTrait;

	protected $table = 'goals';
	protected $fillable = ['name', 'description', 'goal_owner', 'start_date', 'end_date', 'leads_count', 'accounts_count', 'deals_count', 'sales_amount', 'currency_id'];
	protected $appends = ['overall_achievement'];
	protected $dates = ['deleted_at'];
	protected $revisionCreationsEnabled = true;

	public static function validate($data)
	{	
		$start_date = $data['start_date'];
		$start_date_minus = date('Y-m-d', strtotime($start_date . ' -1 day'));

		$rules = ["goal_owner"		=> "exists:users,linked_id,linked_type,staff,status,1,deleted_at,NULL",
				  "name"			=> "required|max:200",
				  "start_date"		=> "required|date",
				  "end_date"		=> "required|date|after:$start_date_minus",
				  "leads_count"		=> "integer|min:1",
				  "accounts_count"	=> "integer|min:1",
				  "deals_count"		=> "integer|min:1",
				  "sales_amount"	=> "numeric|min:1",
				  "currency_id"		=> "required|exists:currencies,id,deleted_at,NULL",
				  "description"		=> "max:65535"];

		return \Validator::make($data, $rules);
	}

	public function setRoute()
	{
		return 'advanced-goal';
	}

	public function setPermission()
	{
		return 'advanced.goal';
	}

	/*
	|--------------------------------------------------------------------------
	| ACCESSOR
	|--------------------------------------------------------------------------
	*/
	public function getNameHtmlAttribute()
	{
		$name_html = "<a class='edit plain' editid='" . $this->id . "'>" . $this->name . "</a>";
		return $name_html;
	}

	public function getDateHtmlAttribute()
	{
		$date = "<span class='c-danger' data-toggle='tooltip' data-placement='right' title='End Date'>" . $this->end_date . "</span>";
		$date .= '<br>';
		$date .= "<span class='shadow' data-toggle='tooltip' data-placement='right' title='Start Date'>" . $this->start_date . "</span>";

		return $date;
	}

	public function getLeadsCompareAttribute()
	{
		if(isset($this->leads_count)) :
			$leads_compare = $this->leads_achieve . "<span class='shadow'> / " . $this->leads_count . "</span>";
			return $leads_compare;
		endif;
		
		return null;	
	}

	public function getLeadsAchieveAttribute()
	{
		if(isset($this->leads_count)) :
			$leads_achieve = Lead::where('created_at', '>=', $this->start_date)->where('created_at', '<=', $this->end_date);
			
			if(!is_null($this->goal_owner)) :
				$leads_achieve = $leads_achieve->whereLead_owner($this->goal_owner);
			endif;	

			$leads_achieve = $leads_achieve->count();

			return $leads_achieve;
		endif;
		
		return null;	
	}

	public function getLeadsRemainingAttribute()
	{
		if(isset($this->leads_count)) :
			$leads_remaining = min_zero($this->leads_count - $this->leads_achieve);
			return $leads_remaining;
		endif;	

		return null;	
	}

	public function getLeadsAchievePercentageAttribute()
	{
		if(isset($this->leads_count)) :
			$achieve_percentage = ($this->leads_achieve * 100) / $this->leads_count;
			$achieve_percentage = max_value_fixer($achieve_percentage, 100);
			return $achieve_percentage;
		endif;	

		return null;
	}

	public function getAccountsCompareAttribute()
	{
		if(isset($this->accounts_count)) :
			$accounts_compare = $this->accounts_achieve . "<span class='shadow'> / " . $this->accounts_count . "</span>";
			return $accounts_compare;
		endif;
		
		return null;	
	}

	public function getAccountsAchieveAttribute()
	{
		if(isset($this->accounts_count)) :
			$accounts_achieve = Account::where('created_at', '>=', $this->start_date)->where('created_at', '<=', $this->end_date);
			
			if(!is_null($this->goal_owner)) :
				$accounts_achieve = $accounts_achieve->whereAccount_owner($this->goal_owner);
			endif;	

			$accounts_achieve = $accounts_achieve->count();

			return $accounts_achieve;
		endif;
		
		return null;	
	}

	public function getAccountsRemainingAttribute()
	{
		if(isset($this->accounts_count)) :
			$accounts_remaining = min_zero($this->accounts_count - $this->accounts_achieve);
			return $accounts_remaining;
		endif;	

		return null;	
	}

	public function getAccountsAchievePercentageAttribute()
	{
		if(isset($this->accounts_count)) :
			$achieve_percentage = ($this->accounts_achieve * 100) / $this->accounts_count;
			$achieve_percentage = max_value_fixer($achieve_percentage, 100);
			return $achieve_percentage;
		endif;	

		return null;
	}

	public function getDealsCompareAttribute()
	{
		if(isset($this->deals_count)) :
			$deals_compare = $this->deals_achieve . "<span class='shadow'> / " . $this->deals_count . "</span>";
			return $deals_compare;
		endif;
		
		return null;	
	}

	public function getDealsAchieveAttribute()
	{
		if(isset($this->deals_count)) :
			$deals_achieve = Deal::where('created_at', '>=', $this->start_date)->where('created_at', '<=', $this->end_date);
			
			if(!is_null($this->goal_owner)) :
				$deals_achieve = $deals_achieve->whereDeal_owner($this->goal_owner);
			endif;	

			$deals_achieve = $deals_achieve->count();

			return $deals_achieve;
		endif;
		
		return null;	
	}

	public function getDealsRemainingAttribute()
	{
		if(isset($this->deals_count)) :
			$deals_remaining = min_zero($this->deals_count - $this->deals_achieve);
			return $deals_remaining;
		endif;	

		return null;	
	}

	public function getDealsAchievePercentageAttribute()
	{
		if(isset($this->deals_count)) :
			$achieve_percentage = ($this->deals_achieve * 100) / $this->deals_count;
			$achieve_percentage = max_value_fixer($achieve_percentage, 100);
			return $achieve_percentage;
		endif;	

		return null;
	}

	public function getSalesCompareAttribute()
	{
		if(isset($this->sales_amount)) :
			$sales_compare = $this->currency->symbol_html . $this->amountFormat('sales_achieve') . "<span class='shadow'> / " . $this->amountFormat('sales_amount') . "</span>";
			return $sales_compare;
		endif;
		
		return null;
	}

	public function getSalesAchieveAttribute()
	{
		if(isset($this->sales_amount)) :
			$sales_achieve = $this->amountTotal('sales_records', 'amount');
			return $sales_achieve;
		endif;	

		return null;
	}

	public function getSalesRecordsAttribute()
	{
		if(isset($this->sales_amount)) :
			$sales_records = Deal::join('deal_stages', 'deal_stages.id', '=', 'deals.deal_stage_id')
								  ->select('deal_stages.category', 'deals.*')
								  ->whereCategory('closed_won')
								  ->where('deals.created_at', '>=', $this->start_date)
								  ->where('deals.created_at', '<=', $this->end_date);
			
			if(!is_null($this->goal_owner)) :
				$sales_records = $sales_records->whereDeal_owner($this->goal_owner);
			endif;

			$sales_records = $sales_records->get();

			return $sales_records;
		endif;

		return collect();
	}		

	public function getSalesRemaingAttribute()
	{
		if(isset($this->sales_amount)) :
			$sales_remaining = min_zero($this->sales_amount - $this->sales_achieve);
			return $sales_remaining;
		endif;	

		return null;
	}

	public function getSalesAchievePercentageAttribute()
	{
		if(isset($this->sales_amount)) :
			$achieve_percentage = ($this->sales_achieve * 100) / $this->sales_amount;
			$achieve_percentage = max_value_fixer($achieve_percentage, 100);
			return $achieve_percentage;
		endif;	

		return null;
	}

	public function getParametersAttribute()
	{
		$goal_parameters = [];

		if(isset($this->leads_count)) :
			array_push($goal_parameters, 'leads');
		endif;

		if(isset($this->accounts_count)) :
			array_push($goal_parameters, 'accounts');
		endif;

		if(isset($this->deals_count)) :
			array_push($goal_parameters, 'deals');
		endif;

		if(isset($this->sales_amount)) :
			array_push($goal_parameters, 'sales');
		endif;

		return $goal_parameters;
	}

	public function getOverallAchievementAttribute()
	{
		$parameter_count = count($this->parameters);

		if($parameter_count > 0) :
			$overall_achievement = 0;

			foreach($this->parameters as $parameter) :
				$parameter_percentage = $parameter . '_achieve_percentage';
				$overall_achievement += $this->$parameter_percentage;	
			endforeach;

			$overall_achievement = $overall_achievement / $parameter_count;
			$overall_achievement = number_format($overall_achievement, 2, '.', '');

			return $overall_achievement;
		endif;
		
		return null;	
	}

	public function getOverallProgressHtmlAttribute()
	{
		if(isset($this->overall_achievement)) :
			$progress_html = "<a class='completion-show'>								  	
							  	<div class='progress'>
						            <div class='progress-bar progress-bar-info' role='progressbar' aria-valuenow='" . $this->overall_achievement . "' aria-valuemin='0' aria-valuemax='100' style='width: " . $this->overall_achievement . "%'>
						                <span class='sr-only'>" . $this->overall_achievement . "% Complete</span>
						            </div>
						            <span class='shadow'>" . $this->overall_achievement . "%</span>
					       		</div>
					          </a>";

			return $progress_html;
		endif;
		
		return null;	
	}

	/*
	|--------------------------------------------------------------------------
	| RELATIONS
	|--------------------------------------------------------------------------
	*/
	// relation: belongsTo
	public function owner()
	{
		return $this->belongsTo(Staff::class, 'goal_owner')->withTrashed();
	}

	public function currency()
	{
		return $this->belongsTo(Currency::class, 'currency_id');
	}

	// relation: morphMany
	public function notificationInfos()
	{
		return $this->morphMany(NotificationInfo::class, 'linked');
	}
}