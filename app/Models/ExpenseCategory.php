<?php

namespace App\Models;

use App\Models\BaseModel;
use App\Models\Traits\PosionableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class ExpenseCategory extends BaseModel
{
	use SoftDeletes;
	use RevisionableTrait;
	use PosionableTrait;

	protected $table = 'expense_categories';
	protected $fillable = ['name', 'description', 'position'];
	protected $dates = ['deleted_at'];
	protected $revisionCreationsEnabled = true;

	public static function validate($data)
	{	
		$unique_name = "unique:expense_categories,name";

		if(isset($data['id'])) :
			$id = $data['id'];
			$unique_name = "unique:expense_categories,name,$id";
		endif;	

		$position_ids = self::commaSeparatedIds([0,-1]);

		$rules = ["name"		=> "required|max:200|$unique_name", 
				  "description"	=> "max:65535", 
				  "position"	=> "required|integer|in:$position_ids"];

		return \Validator::make($data, $rules);
	}

	public function setPermission()
	{
		return 'custom_dropdowns.expense_category';
	}

	/*
	|--------------------------------------------------------------------------
	| ACCESSOR
	|--------------------------------------------------------------------------
	*/
	public function getCanDeleteAttribute()
	{
		if($this->expenses->count()) : return false; endif;

		return true;
	}

	/*
	|--------------------------------------------------------------------------
	| RELATIONS
	|--------------------------------------------------------------------------
	*/
	// relation: hasMany
	public function expenses()
	{
		return $this->hasMany(Expense::class, 'expense_category_id');
	}
}