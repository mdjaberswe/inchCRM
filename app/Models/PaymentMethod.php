<?php

namespace App\Models;

use App\Models\BaseModel;
use App\Models\Traits\PosionableMaskedTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class PaymentMethod extends BaseModel
{
	use SoftDeletes;
	use RevisionableTrait;
	use PosionableMaskedTrait;

	protected $table = 'payment_methods';
	protected $fillable = ['name', 'description', 'status', 'position', 'masked'];
	protected $dates = ['deleted_at'];
	protected $revisionCreationsEnabled = true;

	public static function validate($data)
	{	
		$unique_name = "unique:payment_methods,name";

		if(isset($data['id'])) :
			$id = $data['id'];
			$unique_name = "unique:payment_methods,name,$id";
		endif;	

		$rules = ["name"		=> "required|max:200|$unique_name", 
				  "description"	=> "max:65535", 
				  "status"		=> "boolean"];

		return \Validator::make($data, $rules);
	}
	
	public function setPermission()
	{
		return 'settings.payment_method';
	}

	/*
	|--------------------------------------------------------------------------
	| ACCESSOR
	|--------------------------------------------------------------------------
	*/
	public function getStatusHtmlAttribute()
	{
		$disabled = '';

		if(!permit('settings.payment_method.edit') || $this->masked) :
			$disabled = ' disabled';
		endif;

		$status = "<label class='switch switch-paymethod" . $disabled . "' data-toggle='tooltip' data-placement='top' title='Inactive'><input type='checkbox' value='" . $this->id . "'" . $disabled . "><span class='slider round'></span></label>";
		if($this->status) :
			$status = "<label class='switch switch-paymethod" . $disabled . "' data-toggle='tooltip' data-placement='top' title='Active'><input type='checkbox' value='" . $this->id . "' checked" . $disabled . "><span class='slider round'></span></label>";
		endif;

		return $status;
	}

	public function getCanDeleteAttribute()
	{
		if($this->payments->count()) : return false; endif;
		if($this->expenses->count()) : return false; endif;

		return true;
	}

	/*
	|--------------------------------------------------------------------------
	| RELATIONS
	|--------------------------------------------------------------------------
	*/
	// relation: hasMany
	public function payments()
	{
		return $this->hasMany(Payment::class, 'payment_method_id');
	}

	public function expenses()
	{
		return $this->hasMany(Expense::class, 'payment_method_id');
	}
}