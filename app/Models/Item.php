<?php

namespace App\Models;

use App\Models\BaseModel;
use App\Models\Traits\FinanceTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class Item extends BaseModel
{
	use SoftDeletes;
	use FinanceTrait;
	use RevisionableTrait;

	protected $table = 'items';
	protected $fillable = ['name', 'price', 'currency_id', 'tax', 'discount'];
	protected $appends = ['quantity', 'rate', 'total'];
	protected $dates = ['deleted_at'];
	protected $revisionCreationsEnabled = true;

	public static function validate($data)
	{	
		$unique_name = "unique:items,name";

		if(isset($data['id'])) :
			$id = $data['id'];
			$unique_name = "unique:items,name,$id";
		endif;	

		$rules = ["name"	=> "required|max:200|$unique_name",
				  "price"	=> "required|numeric",
				  "tax"		=> "numeric",
				  "discount"=> "numeric",
				  "currency_id"	=> "required|exists:currencies,id,deleted_at,NULL"];

		return \Validator::make($data, $rules);
	}

	public static function cartItemAddValidate($data)
	{
		$rules = ['items'		=> 'required|exists:items,id,deleted_at,NULL',
				  'linked_type'	=> 'required|in:lead,contact,account,deal',
				  'linked_id'	=> 'required|exists:' . $data['linked_type'] . 's,id,deleted_at,NULL'];

		return \Validator::make($data, $rules);
	}

	public static function cartItemUpdateValidate($data)
	{
		$rules = ['quantity'	=> 'numeric',
				  'price'		=> 'numeric',
				  'id'			=> 'required|exists:items,id,deleted_at,NULL|exists:cart_items,item_id,linked_id,' . $data['linked_id'] . ',linked_type,' . $data['linked_type'],
				  'linked_id'	=> 'required|exists:' . $data['linked_type'] . 's,id,deleted_at,NULL',
				  'linked_type'	=> 'required|in:lead,contact,account,deal'];

		return \Validator::make($data, $rules);		  
	}

	public function setRoute()
	{
		return 'sale-item';
	}

	public function setPermission()
	{
		return 'sale.item';
	}

	/*
	|--------------------------------------------------------------------------
	| ACCESSOR
	|--------------------------------------------------------------------------
	*/
	public function getPriceFormatAttribute()
	{
	    return number_format($this->attributes['price'], 2);
	}

	public function getTaxFormatAttribute()
	{
	    return number_format($this->attributes['tax'], 2);
	}

	public function getDiscountFormatAttribute()
	{
	    return number_format($this->attributes['discount'], 2);
	}

	public function getQuantityAttribute()
	{
		return is_object($this->pivot) ? $this->pivot->quantity : 0;
	}

	public function getLinkedIdAttribute()
	{
		return non_property_checker($this->pivot, 'linked_id');
	}

	public function getLinkedTypeAttribute()
	{
		return non_property_checker($this->pivot, 'linked_type');
	}

	public function getQuantityHtmlAttribute()
	{
		$html = "<span class='editable'>
					" . $this->quantity . "
					<a class='edit-btn'><i class='fa fa-pencil'></i></a>
				</span>";

		$html .= "<div class='edit-field' action='" . route('admin.cart.item.update', [$this->linked_type, $this->linked_id, $this->id]) . "'>
					<input type='text' name='quantity' class='form-control numeric' placeholder='Quantity' value='" . $this->quantity . "'>
					<input type='hidden' name='id' value='" . $this->id . "'>
					<input type='hidden' name='linked_id' value='" . $this->linked_id . "'>
					<input type='hidden' name='linked_type' value='" . $this->linked_type . "'>
					<a class='save-btn'><i class='fa fa-check-circle'></i></a>
					<a class='cancel-btn'><i class='fa fa-times-circle'></i></a>
				  </div>";			

		return $html;		
	}

	public function getRateAttribute()
	{
		return is_object($this->pivot) ? $this->pivot->rate : 0.00;
	}

	public function getRateHtmlAttribute()
	{
		$html = "<span class='editable'>
					" . $this->amountHtml('rate') . "
					<a class='edit-btn'><i class='fa fa-pencil'></i></a>
				</span>";

		$html .= "<div class='edit-field' action='" . route('admin.cart.item.update', [$this->linked_type, $this->linked_id, $this->id]) . "'>
					<input type='text' name='price' class='form-control numeric' placeholder='Price' value='" . number_format($this->rate, 2, '.', '') . "'>
					<input type='hidden' name='id' value='" . $this->id . "'>
					<input type='hidden' name='linked_id' value='" . $this->linked_id . "'>
					<input type='hidden' name='linked_type' value='" . $this->linked_type . "'>
					<a class='save-btn'><i class='fa fa-check-circle'></i></a>
					<a class='cancel-btn'><i class='fa fa-times-circle'></i></a>
				  </div>";	  	

		return $html;
	}

	public function getTotalAttribute()
	{
		return ($this->quantity * $this->rate);
	}

	public function getPivotSerialAttribute()
	{
		if(is_object($this->pivot)) :
			$count = $this->pivot['parent']->items()->where('item_id', '<', $this->id)->get()->count();
			return $count+1;
		endif;

		return null;
	}

	public function getPivotInfo()
	{
		if(!is_object($this->pivot)) :
			return null;
		endif;
			
		return $this->pivot['parent'];
	}

	public function getRemoveCartItemAttribute()
	{
		if(isset($this->linked_id) && isset($this->linked_type)) :
			return "<button action='" . route('admin.cart.item.remove', [$this->linked_type, $this->linked_id, $this->id]) . "' type='button' class='btn-remove' data-toggle='tooltip' data-placement='top' title='Remove'>
						<span aria-hidden='true'>&times;</span>
					</button>";
		endif;

		return null;
	}

	public function getCurrencyAttribute()
	{
		if(is_object($this->pivot)) :
			return $this->pivot['parent']->currency;
		endif;	

		return $this->currencyinfo;
	}

	/*
	|--------------------------------------------------------------------------
	| RELATIONS
	|--------------------------------------------------------------------------
	*/
	// relation: belongsTo
	public function currencyinfo()
	{
		return $this->belongsTo(Currency::class, 'currency_id');
	}
	
	// relation: morphedByMany
	public function leads()
	{
		return $this->morphedByMany(Lead::class, 'linked', 'cart_items')->withPivot('quantity', 'rate', 'linked_type');
	}

	public function contacts()
	{
		return $this->morphedByMany(Contact::class, 'linked', 'cart_items')->withPivot('quantity', 'rate', 'linked_type');
	}

	public function accounts()
	{
		return $this->morphedByMany(Account::class, 'linked', 'cart_items')->withPivot('quantity', 'rate', 'linked_type');
	}

	public function deals()
	{
		return $this->morphedByMany(Deal::class, 'linked', 'cart_items')->withPivot('quantity', 'rate', 'linked_type');
	}
}