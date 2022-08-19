<?php

namespace App\Models;

use Session;
use App\Models\BaseModel;
use App\Models\Traits\PosionableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class DealPipeline extends BaseModel
{
	use SoftDeletes;
	use PosionableTrait;
	use RevisionableTrait;

	protected $table = 'deal_pipelines';
	protected $fillable = ['name', 'default', 'period', 'position'];
	protected $dates = ['deleted_at'];
	protected $revisionCreationsEnabled = true;

	public static function validate($data)
	{	
		$unique_name = "unique:deal_pipelines,name";

		if(isset($data['id'])) :
			$id = $data['id'];
			$unique_name = "unique:deal_pipelines,name,$id";
		endif;	

		$rules = ["name" 		=> "required|max:200|$unique_name",
				  "deal_stage"	=> "required|array|exists:deal_stages,id,deleted_at,NULL",
				  "period"		=> "required|min:1|integer",
				  "default"		=> "boolean",
				  "forecast"	=> "array|exists:deal_stages,id,category,open,deleted_at,NULL"];

		return \Validator::make($data, $rules);
	}

	public function setPermission()
	{
		return 'custom_dropdowns.deal_pipeline';
	}

	public static function getCurrentPipeline()
	{
		if(Session::has('deal_pipeline')) :
			return self::find(Session::get('deal_pipeline'));
		endif;

		return self::default()->first();
	}

	/*
	|--------------------------------------------------------------------------
	| SCOPES
	|--------------------------------------------------------------------------
	*/
	public function scopeDefault($query)
	{
	    $query->whereDefault(1);
	}

	/*
	|--------------------------------------------------------------------------
	| ACCESSOR
	|--------------------------------------------------------------------------
	*/
	public function getNameHtmlAttribute()
	{
		$tooltip = "";
		if(strlen($this->name) > 30) :
			$tooltip = "data-toggle='tooltip' data-placement='top' title='" . $this->name . "'";
		endif;	

		$name_html = "<span $tooltip>" . str_limit($this->name, 30) . "</span>" . $this->default_html;
		return $name_html;
	}

	public function getDefaultHtmlAttribute()
	{
		$default_html = '';

		if($this->default) :
			$default_html = "<span class='btn btn-warning light status m-left-10'>Default</span> ";
		endif;

		return $default_html;
	}

	public function getStageOptionsHtmlAttribute()
	{
		$html = '';
		$stages = $this->stages()->orderBy('pipeline_stages.position')->get(['id', 'name', 'probability']);
	
		foreach($stages as $key => $stage) :
			$selected = $key == 0 ? 'selected' : '';
			$html .= "<option value='$stage->id' relatedval='$stage->probability' $selected>$stage->name</option>";
		endforeach;

		return $html;
	}

	public function getCanDeleteAttribute()
	{
		if($this->deals->count()) : return false; endif;
		return true;
	}

	/*
	|--------------------------------------------------------------------------
	| RELATIONS
	|--------------------------------------------------------------------------
	*/
	// relation: hasMany
	public function deals()
	{
		return $this->hasMany(Deal::class, 'deal_pipeline_id');
	}
	
	// relation: belongsToMany
	public function stages()
	{
		return $this->belongsToMany(DealStage::class, 'pipeline_stages')->withPivot('forecast', 'position');
	}
}	