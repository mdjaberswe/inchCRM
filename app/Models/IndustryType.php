<?php

namespace App\Models;

use App\Models\BaseModel;
use App\Models\Traits\PosionableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class IndustryType extends BaseModel
{
	use SoftDeletes;
	use RevisionableTrait;
	use PosionableTrait;

	protected $table = 'industry_types';
	protected $fillable = ['name', 'description', 'position'];
	protected $dates = ['deleted_at'];
	protected $revisionCreationsEnabled = true;

	public static function validate($data)
	{	
		$unique_name = "unique:industry_types,name";

		if(isset($data['id'])) :
			$id = $data['id'];
			$unique_name = "unique:industry_types,name,$id";
		endif;

		$position_ids = self::commaSeparatedIds([0,-1]);

		$rules = ["name"		=> "required|max:200|$unique_name", 
				  "description"	=> "max:65535", 
				  "position"	=> "required|integer|in:$position_ids"];

		return \Validator::make($data, $rules);
	}

	public function setPermission()
	{
		return 'custom_dropdowns.industry_type';
	}

	/*
	|--------------------------------------------------------------------------
	| RELATIONS
	|--------------------------------------------------------------------------
	*/
	// relation: hasMany
	public function accounts()
	{
		return $this->hasMany(Account::class, 'industry_type_id');
	}
}