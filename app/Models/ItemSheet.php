<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class ItemSheet extends BaseModel
{
	use SoftDeletes;
	use RevisionableTrait;

	protected $table = 'item_sheets';
	protected $fillable = ['linked_id', 'linked_type', 'item', 'quantity', 'unit', 'rate', 'tax', 'discount'];
	protected $dates = ['deleted_at'];
	protected $revisionCreationsEnabled = true;

	/*
	|--------------------------------------------------------------------------
	| ACCESSOR
	|--------------------------------------------------------------------------
	*/
	public function getCurrencyAttribute()
	{
		return $this->linked->currency;
	}

	public function getRateFormatAttribute()
	{
		return number_format($this->attributes['rate'], 2, $this->currency->decimal_separator, $this->currency->thousand_separator);
	}

	public function getQuantityFormatAttribute()
	{
		if(intval($this->attributes['quantity']) == $this->attributes['quantity']) :
			return $this->attributes['quantity'];
		endif;
			
		return number_format($this->attributes['quantity'], 2, $this->currency->decimal_separator, $this->currency->thousand_separator);
	}

	public function getQuantityUnitFormatAttribute()
	{
		$quantity_unit = $this->quantity_format . " <span class='shadow'>" . $this->unit . "</span>";

		$units = self::whereLinked_type($this->linked_type)->whereLinked_id($this->linked_id)->pluck('unit')->toArray();
		$is_unique_unit = count(array_unique($units));

		if($is_unique_unit == 1) :
			$quantity_unit = $this->quantity_format;
		endif;
		
		return $quantity_unit;	
	}

	public function getTaxFormatAttribute()
	{
		return number_format($this->attributes['tax'], 2, $this->currency->decimal_separator, $this->currency->thousand_separator);
	}

	public function getTaxValueAttribute()
	{
		$tax_on_amount = 0;
		$calculate_item = $this->calculateItem();
		if(isset($calculate_item['tax_on_amount'])) :
			$tax_on_amount = $calculate_item['tax_on_amount'];
		endif;

		return $tax_on_amount;
	}

	public function getTaxDetailsFormatAttribute()
	{
		$tax_details = number_format($this->tax_value, 2, $this->currency->decimal_separator, $this->currency->thousand_separator) . " <span class='shadow'>(" . $this->tax_format . '%)</span>';
		return $tax_details;
	}

	public function getDiscountFormatAttribute()
	{
		if($this->discount_type == 'flat') :
			return number_format($this->attributes['discount'], 2, $this->currency->decimal_separator, $this->currency->thousand_separator);
		endif;			
		
		return number_format($this->attributes['discount'], 2, $this->currency->decimal_separator, '');
	}

	public function getDiscountValueAttribute()
	{
		if($this->discount_type == 'flat') :
			return $this->attributes['discount'];
		endif;	

		$discount_on_amount = 0;
		$calculate_item = $this->calculateItem();
		if(isset($calculate_item['discount_on_amount'])) :
			$discount_on_amount = $calculate_item['discount_on_amount'];
		endif;

		return $discount_on_amount;
	}

	public function getDiscountDetailsFormatAttribute()
	{
		if($this->discount_type == 'flat') :
			return $this->discount_format;
		endif;

		$discount_details = number_format($this->discount_value, 2, $this->currency->decimal_separator, $this->currency->thousand_separator) . " <span class='shadow'>(" . $this->discount_format . '%)</span>';

		return $discount_details;
	}

	public function getAmountAttribute()
	{
		$amount = 0;

		$outcome = $this->calculateItem();

		if(isset($outcome['total_amount'])) :
			$amount = $outcome['total_amount'];
		endif;	

		return $amount;
	}

	public function getAmountFormatAttribute()
	{
		return number_format($this->amount, 2, $this->currency->decimal_separator, $this->currency->thousand_separator);
	}

	public function getPlainAmountAttribute()
	{
		$plain_amount = 0;

		$outcome = $this->calculateItem();

		if(isset($outcome['plain_amount'])) :
			$plain_amount = $outcome['plain_amount'];
		endif;	

		return $plain_amount;
	}

	public function getPlainAmountFormatAttribute()
	{
		return number_format($this->plain_amount, 2, $this->currency->decimal_separator, $this->currency->thousand_separator);
	}

	public function calculateItem()
	{
		$outcome = [];

		switch($this->discount_type) :
			case 'pre' :
				$outcome = $this->preDiscountAmount();
			break;

			case 'post' :	
				$outcome = $this->postDiscountAmount();
			break;

			case 'flat' :
				$outcome = $this->flatDiscountAmount();
			break;

			default : $outcome = $this->preDiscountAmount();
		endswitch;

		return $outcome;
	}

	public function preDiscountAmount()
	{
		$plain_amount = $this->quantity * $this->rate;
		$discount_on_amount = ($plain_amount * $this->discount) / 100;
		$amount_with_discount = $plain_amount - $discount_on_amount;
		$tax_on_amount = ($amount_with_discount * $this->tax) / 100;
		$amount_with_tax = $amount_with_discount + $tax_on_amount;
		$total_amount = $amount_with_tax;

		$outcome = ['total_amount' => $total_amount, 'plain_amount' => $plain_amount, 'discount_on_amount' => $discount_on_amount, 'tax_on_amount' => $tax_on_amount];

		return $outcome;
	}

	public function postDiscountAmount()
	{
		$plain_amount = $this->quantity * $this->rate;
		$tax_on_amount = ($plain_amount * $this->tax) / 100;
		$amount_with_tax = $plain_amount + $tax_on_amount;
		$discount_on_amount = ($amount_with_tax * $this->discount) / 100;
		$amount_with_discount = $amount_with_tax - $discount_on_amount;
		$total_amount = $amount_with_discount;

		$outcome = ['total_amount' => $total_amount, 'plain_amount' => $plain_amount, 'discount_on_amount' => $discount_on_amount, 'tax_on_amount' => $tax_on_amount];

		return $outcome;
	}

	public function flatDiscountAmount()
	{
		$plain_amount = $this->quantity * $this->rate;
		$tax_on_amount = ($plain_amount * $this->tax) / 100;
		$amount_with_tax = $plain_amount + $tax_on_amount;
		$amount_with_discount = $amount_with_tax - $this->discount;
		$total_amount = $amount_with_discount;

		$outcome = ['total_amount' => $total_amount, 'plain_amount' => $plain_amount, 'discount_on_amount' => $this->discount, 'tax_on_amount' => $tax_on_amount];

		return $outcome;
	}

	public function getDiscountTypeAttribute()
	{
		return $this->linked->discount_type;
	}

	public function getItemOrderAttribute()
	{
		$item_order = self::whereLinked_type($this->linked_type)->whereLinked_id($this->linked_id)->where('id', '<=', $this->id)->count();
		return $item_order;
	}

	public function getSingleRowHtmlAttribute()
	{	
		$td_discount = '';
		$td_tax = '';
		$currency_symbol = "<span class='symbol'>" . $this->linked->currency->symbol . "</span>";

		if($this->linked->total_discount > 0) :
			$td_discount = '<td>' . $currency_symbol . $this->discount_details_format . '</td>';
		endif;
				
		if($this->linked->total_tax > 0) :
			$td_tax = '<td>' . $currency_symbol . $this->tax_details_format . '</td>';
		endif;

		if($this->discount_type == 'pre') :
			$discount_tax = $td_discount . $td_tax;
		else :
			$discount_tax = $td_tax . $td_discount;
		endif;

		$row = '<tr>
					<td>' . $this->item_order . '</td>
				    <td>' . $this->item . '</td>
				    <td>' . $this->quantity_unit_format . '</td>
				    <td>' . $currency_symbol . $this->rate_format . '</td>'				    				    
				    . $discount_tax .
				    '<td class="align-r">' . $currency_symbol . $this->amount_format . '</td>
				</tr>';

		return $row;
	}

	/*
	|--------------------------------------------------------------------------
	| RELATIONS
	|--------------------------------------------------------------------------
	*/
	// relation: morphTo
	public function linked()
	{
		return $this->morphTo();
	}
}