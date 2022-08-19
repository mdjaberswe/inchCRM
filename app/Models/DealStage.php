<?php

namespace App\Models;

use App\Models\BaseModel;
use App\Models\Traits\PosionableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class DealStage extends BaseModel
{
	use SoftDeletes;
	use PosionableTrait;
	use RevisionableTrait;

	protected $table = 'deal_stages';
	protected $fillable = ['name', 'category', 'probability', 'description', 'fixed', 'position'];
	protected $dates = ['deleted_at'];
	protected $revisionCreationsEnabled = true;

	public static function validate($data, $deal_stage = null)
	{	
		$unique_name = "unique:deal_stages,name";
		$category_required = "required|";

		if(isset($data['id'])) :
			$id = $data['id'];
			$unique_name = "unique:deal_stages,name,$id";
			$category_required = (isset($deal_stage) && !$deal_stage->fixed) ? $category_required : '';
		endif;	

		$position_ids = self::commaSeparatedIds([0,-1]);

		$rules = ["name"		=> "required|max:200|$unique_name", 
				  "description"	=> "max:65535", 
				  "position"	=> "required|integer|in:$position_ids",
				  "category"	=> $category_required . "in:open,closed_won,closed_lost",
				  "probability"	=> "required|numeric|min:0|max:100"];

		return \Validator::make($data, $rules);
	}

	public function setPermission()
	{
		return 'custom_dropdowns.deal_stage';
	}

	public static function getCategoryIds($category)
	{
		return self::whereCategory($category)->pluck('id')->toArray();
	}

	public static function getCurrentStages()
	{
		return DealPipeline::getCurrentPipeline()->stages()->orderByRaw("FIELD(category, 'open', 'closed_won', 'closed_lost')")->orderBy('pipeline_stages.position')->get();
	}

	/*
	|--------------------------------------------------------------------------
	| SCOPES
	|--------------------------------------------------------------------------
	*/
	public function scopeOnlyClosed($query)
	{
	    $query->where('category', '!=', 'open');
	}

	/*
	|--------------------------------------------------------------------------
	| ACCESSOR
	|--------------------------------------------------------------------------
	*/
	public function getNameWithProbabilityAttribute()
	{
		return "<span class='shadow' data-toggle='tooltip' data-placement='top' title='Probability'>" . $this->probability . "%</span> " . $this->name;
	}

	public function getDropdownNameAttribute()
	{
		return $this->name . ' (' . $this->probability . "%)";
	}

	public function getForecastHtmlAttribute()
	{
		$checked = $this->forecast ? 'checked' : '';
		$disabled = $this->category != 'open' ? 'disabled' : '';
		$forecast_checkbox = "<div class='pretty info smooth'><input class='single-row' type='checkbox' name='forecast[]' value='" . $this->id . "' $checked $disabled><label><i class='mdi mdi-check'></i></label></div>";
		return $forecast_checkbox;
	}

	public function getForecastAttribute()
	{
		$forecast = non_property_checker($this->pivot, 'forecast');
		return is_null($forecast) ? true : $this->pivot->forecast;
	}

	/*
	|--------------------------------------------------------------------------
	| RELATIONS
	|--------------------------------------------------------------------------
	*/
	// relation: hasMany
	public function deals()
	{
		return $this->hasMany(Deal::class, 'deal_stage_id');
	}

	// relation: belongsToMany
	public function pipelines()
	{
		return $this->belongsToMany(DealPipeline::class, 'pipeline_stages')->withPivot('forecast', 'position');
	}
}