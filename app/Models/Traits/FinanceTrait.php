<?php

namespace App\Models\Traits;

use App\Models\Currency;

trait FinanceTrait
{
	public function amountFormat($property)
	{
		return number_format((float)$this->$property, 2, $this->currency->decimal_separator, $this->currency->thousand_separator);
	}

	public function amountHtml($property)
	{
		return place_currency_symbol($this->amountFormat($property), $this->currency->symbol_html, $this->currency->symbol_position);
	}

	public function amountValueHtml($value)
	{
		$amount = number_format($value, 2, $this->currency->decimal_separator, $this->currency->thousand_separator);
		return place_currency_symbol($amount, $this->currency->symbol_html, $this->currency->symbol_position);
	}

	public function amountTooltipHtml($property, $range)
	{
		$amount = $this->amountFormat($property);

		if(strlen($amount) > $range) :
			$amount = "<span data-toggle='tooltip' data-placement='top' title='$amount'>" . str_limit($amount, $range) . "</span>";
		endif;

		return place_currency_symbol($amount, $this->currency->symbol_html, $this->currency->symbol_position);
	}

	public function amountTotal($source, $amount, $collection = true)
	{
		$total = 0;

		if($collection) :
			$equal_currency = $this->$source->where('currency_id', $this->currency_id);
			$total = $total + $equal_currency->sum($amount);

			$diff_ids = array_diff($this->$source->pluck('id')->toArray(), $equal_currency->pluck('id')->toArray());
			$exchange_currency = $this->$source->whereIn('id', $diff_ids)
									  ->map(function($item, $key) use ($amount)
									  	{
									  		$from = [$item['currency_id'], $item[$amount]];
									  		return \App\Models\Currency::exchangeCurrency($from, $this->currency_id);
									  	})->sum();

			$total = $total + $exchange_currency;
		else :
			$total = $total + $this->$source()->whereCurrency_id($this->currency_id)->sum($amount);

			$exchange_currency = $this->$source()->where('currency_id', '!=', $this->currency_id)
									  ->get(['currency_id', $amount])
									  ->map(function($item, $key) use ($amount)
									  	{
									  		$from = [$item['currency_id'], $item[$amount]];
									  		return \App\Models\Currency::exchangeCurrency($from, $this->currency_id);
									  	})->sum();

			$total = $total + $exchange_currency;
		endif;	

		return $total;
	}

	public function amountTotalHtml($source, $amount, $collection = true)
	{
		$total = $this->amountTotal($source, $amount, $collection);
		$format = number_format($total, 2, $this->currency->decimal_separator, $this->currency->thousand_separator);
		$html = place_currency_symbol($format, $this->currency->symbol_html, $this->currency->symbol_position);
		return $html;
	}

	public function getHiddenCurrencyInfoAttribute()
	{
		$currency_info ="<span class='symbol none' value='" . $this->currency->id . "' code='" . $this->currency->code . "' icon='" . currency_icon($this->currency->code, $this->currency->symbol) . "'>" . $this->currency->solid_symbol . "</span>";
		return $currency_info;
	}
}	