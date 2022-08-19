<?php

namespace App\Models;

use App\Models\BaseModel;
use App\Models\Traits\PosionableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class Source extends BaseModel
{
	use SoftDeletes;
	use RevisionableTrait;
	use PosionableTrait;
	
	protected $table = 'sources';
	protected $fillable = ['name', 'description', 'position'];
	protected $dates = ['deleted_at'];
	protected $revisionCreationsEnabled = true;

	public static function validate($data)
	{	
		$unique_name = "unique:sources,name";

		if(isset($data['id'])) :
			$id = $data['id'];
			$unique_name = "unique:sources,name,$id";
		endif;	

		$position_ids = self::commaSeparatedIds([0,-1]);

		$rules = ["name"		=> "required|max:200|$unique_name", 
				  "description"	=> "max:65535", 
				  "position"	=> "required|integer|in:$position_ids"];

		return \Validator::make($data, $rules);
	}

	public function setPermission()
	{
		return 'custom_dropdowns.source';
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
		return $this->hasMany(Lead::class);
	}

	public function contacts()
	{
		return $this->hasMany(Contact::class);
	}

	public function deals()
	{
		return $this->hasMany(Deal::class);
	}
}