<?php

namespace App\Models;

use App\Models\BaseModel;
use App\Models\Traits\PosionableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class LeadStage extends BaseModel
{
	use SoftDeletes;	
	use PosionableTrait;
	use RevisionableTrait;
	
	protected $table = 'lead_stages';
	protected $fillable = ['name', 'category', 'description', 'fixed', 'position'];
	protected $dates = ['deleted_at'];
	protected $revisionCreationsEnabled = true;

	public static function validate($data, $lead_stage = null)
	{	
		$unique_name = "unique:lead_stages,name";
		$category_required = "required|";

		if(isset($data['id'])) :
			$id = $data['id'];
			$unique_name = "unique:lead_stages,name,$id";
			$category_required = (isset($lead_stage) && !$lead_stage->fixed) ? $category_required : '';
		endif;	

		$position_ids = self::commaSeparatedIds([0,-1]);

		$rules = ["name"		=> "required|max:200|$unique_name", 
				  "description"	=> "max:65535", 
				  "position"	=> "required|integer|in:$position_ids",
				  "category"	=> $category_required . "in:open,converted,closed_lost"];

		return \Validator::make($data, $rules);
	}

	public function setPermission()
	{
		return 'custom_dropdowns.lead_stage';
	}

	/*
	|--------------------------------------------------------------------------
	| ACCESSOR
	|--------------------------------------------------------------------------
	*/
	public function getViewLeadsHtmlAttribute()
	{
		$view_leads = "<a href='' class='tbl-btn' data-toggle='tooltip' data-placement='top' title='View Leads'><i class='pe-7s-user pe-va lg'></i></a>";
		return $view_leads;
	}

	/*
	|--------------------------------------------------------------------------
	| RELATIONS
	|--------------------------------------------------------------------------
	*/
	// relation: hasMany
	public function leads()
	{
		return $this->hasMany(Lead::class, 'lead_stage_id');
	}
}