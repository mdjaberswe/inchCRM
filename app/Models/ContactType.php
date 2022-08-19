<?php

namespace App\Models;

use App\Models\BaseModel;
use App\Models\Traits\PosionableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class ContactType extends BaseModel
{
	use SoftDeletes;
	use RevisionableTrait;
	use PosionableTrait;

	protected $table = 'contact_types';
	protected $fillable = ['name', 'description', 'position'];
	protected $dates = ['deleted_at'];
	protected $revisionCreationsEnabled = true;

	public static function validate($data)
	{	
		$unique_name = "unique:contact_types,name";

		if(isset($data['id'])) :
			$id = $data['id'];
			$unique_name = "unique:contact_types,name,$id";
		endif;

		$position_ids = self::commaSeparatedIds([0,-1]);

		$rules = ["name"		=> "required|max:200|$unique_name", 
				  "description"	=> "max:65535", 
				  "position"	=> "required|integer|in:$position_ids"];

		return \Validator::make($data, $rules);
	}

	public function setPermission()
	{
		return 'custom_dropdowns.contact_type';
	}

	/*
	|--------------------------------------------------------------------------
	| RELATIONS
	|--------------------------------------------------------------------------
	*/
	// relation: hasMany
	public function contacts()
	{
		return $this->hasMany(Contact::class, 'contact_type_id');
	}
}