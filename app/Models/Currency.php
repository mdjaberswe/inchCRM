<?php

namespace App\Models;

use App\Models\BaseModel;
use App\Models\Traits\PosionableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class Currency extends BaseModel
{
	use SoftDeletes;
	use RevisionableTrait;
	use PosionableTrait;
	
	protected $table = 'currencies';
	protected $fillable = ['name', 'code', 'symbol', 'symbol_position', 'decimal_separator', 'thousand_separator', 'exchange_rate', 'face_value', 'base', 'position'];
	protected $dates = ['deleted_at'];
	protected $revisionCreationsEnabled = true;

	public static function validate($data)
	{	
		$unique_code = "unique:currencies,code,NULL,";

		if(isset($data['id'])) :
			$id = $data['id'];
			$unique_code = "unique:currencies,code,$id,";
		endif;

		$unique_code .= "id,deleted_at,NULL";

		$rules = ["name"			=> "required|max:100", 
				  "code"			=> "required|min:3|max:3|$unique_code", 
				  "face_value"		=> "required|numeric|integer|min:0",
				  "exchange_rate"	=> "required|numeric|min:0",
				  "symbol"			=> "required|max:50",
				  "symbol_position"	=> "required|in:before,after"];

		return \Validator::make($data, $rules);
	}

	public function setPermission()
	{
		return 'settings.currency';
	}

	/*
	|--------------------------------------------------------------------------
	| ACCESSOR
	|--------------------------------------------------------------------------
	*/
	public function getNameHtmlAttribute()
	{
		$tooltip = "";
		if(strlen($this->name) > 17) :
			$tooltip = "data-toggle='tooltip' data-placement='top' title='" . $this->name . "'";
		endif;	

		$name_html = "<span $tooltip>" . str_limit($this->name, 17, ".") . "</span>" . $this->base_html;
		return $name_html;
	}

	public function getNameInfoAttribute()
	{
		$info = $this->name . ' (' . $this->code . ') ' . $this->symbol;
		return $info;
	}

	public function getBaseHtmlAttribute()
	{
		$base_html = '';

		if($this->base) :
			$base_html = "<span class='btn btn-warning status m-left-10'><i class='fa fa-star'></i> Base</span> ";
		endif;

		return $base_html;
	}

	public function getSolidSymbolAttribute()
	{
		return str_replace(' ', '&nbsp;', trim($this->symbol));
	}

	public function getSymbolHtmlAttribute()
	{
		return "<span class='symbol' value='" . $this->id . "' code='" . $this->code . "' icon='" . currency_icon($this->code, $this->symbol) . "'>" . $this->solid_symbol . "</span>";
	}	

	public function getSymbolExHtmlAttribute()
	{
		return "<span class='symbol focus' data-toggle='tooltip' data-placement='top' title='ex. " . $this->format . "'>" . $this->symbol . "</span>";
	}	

	public function getFormatAttribute()
	{
		$amount = number_format(rand(10000, 100000), 2, $this->decimal_separator, $this->thousand_separator);
		$format = $this->symbol_position == 'after' ?  $amount . $this->symbol : $this->symbol . $amount;
		$format = str_replace("'", "&#39;", $format);
		return $format;
	}

	public function getExchangeRateFormatAttribute()
	{
		return number_format($this->exchange_rate, 4);
	}

	public function extendActionHtml($edit_permission = true)
	{
		$extend_action = '';

		if(!$this->base && $edit_permission) :
			$extend_action .= "<li><a class='make-base' editid='" . $this->id . "'><i class='fa fa-star'></i> Set as Base</a></li>";
		endif;

		return $extend_action;
	}

	public function getCanDeleteAttribute()
	{
		if($this->accounts->count()) : return false; endif;
		if($this->leads->count()) : return false; endif;
		if($this->items->count()) : return false; endif;
		if($this->estimates->count()) : return false; endif;
		if($this->invoices->count()) : return false; endif;
		if($this->payments->count()) : return false; endif;
		if($this->expenses->count()) : return false; endif;
		if($this->deals->count()) : return false; endif;
		if($this->goals->count()) : return false; endif;
		if($this->campaigns->count()) : return false; endif;

		return true;
	}

	public static function getBase()
	{
		return self::whereBase(1)->first();
	}

	public static function exchangeRateReform($setBaseCurrency)
	{
		$exchange_rate_unit = $setBaseCurrency->face_value / $setBaseCurrency->exchange_rate;

		foreach(self::all() as $reform_currency) :
			$reform_exchange_rate = $exchange_rate_unit * ($reform_currency->exchange_rate / $reform_currency->face_value);
			$reform_currency->exchange_rate = $reform_exchange_rate;
			$reform_currency->face_value = 1;
			$reform_currency->save();
		endforeach;	
	}

	public static function exchangeCurrency($from, $to_id)
	{
		$from_id = $from[0];		
		$from_currency = self::find($from_id);
		$to_currency = self::find($to_id);
		$amount = $from[1];
		$outcome = $amount * ($from_currency->exchange_rate / $from_currency->face_value) * ($to_currency->face_value / $to_currency->exchange_rate);
		return $outcome;
	}

	public static function exchangeToBase($currency_id, $amount)
	{
		$to_currency = self::getBase();

		if($to_currency->id == $currency_id) :
			return $amount;
		endif;	

		$from_currency = self::find($currency_id);		
		$outcome = $amount * ($from_currency->exchange_rate / $from_currency->face_value) * ($to_currency->face_value / $to_currency->exchange_rate);
		return $outcome;
	}

	public static function totalConvertToBaseCurrency($morph, $ids, $prop)
	{
		$total = 0;
		$model = morph_to_model($morph);
		$base_currency = self::getBase();
		$equal_currency = $model::whereIn('id', $ids)->where('currency_id', $base_currency->id);
		$total = $total + $equal_currency->sum($prop);

		$diff_ids = array_diff($ids, $equal_currency->pluck('id')->toArray());
		$exchange_currency = $model::whereIn('id', $diff_ids)->get()
								  ->map(function($item, $key) use ($base_currency, $prop)
								  	{
								  		$from = [$item['currency_id'], $item[$prop]];
								  		return self::exchangeCurrency($from, $base_currency->id);
								  	})->sum();

		$total = $total + $exchange_currency;
		$total_format = number_format($total, 2, $base_currency->decimal_separator, $base_currency->thousand_separator);
		$total_html = place_currency_symbol($total_format, $base_currency->symbol_html, $base_currency->symbol_position);
		$outcome = ['total' => $total, 'html' => $total_html];

		return $outcome;
	}

	public static function amountValueBaseCurrencyHtml($value)
	{
		$base_currency = self::getBase();
		$amount = number_format($value, 2, $base_currency->decimal_separator, $base_currency->thousand_separator);
		return place_currency_symbol($amount, $base_currency->symbol_html, $base_currency->symbol_position);
	}

	public static function dropdownList()
	{
		$dropdown_list = '';
		$currencies = self::orderBy('position')->get();

		foreach($currencies as $currency) :
			$selected = $currency->base ? 'selected' : null;
			$icon = currency_icon($currency->code, $currency->symbol);
			$text = is_null($icon) ? "<span>" . $currency->symbol . "</span> " . $currency->code : "<span class='". $icon . "'></span> " . $currency->code;
			$dropdown_list .= "<li class='" . $selected . "' value='" . $currency->id . "' code='" . $currency->code . "' icon='" . $icon . "' symbol='" . $currency->symbol . "'>" . $text . "</li>"; 
		endforeach;	

		return $dropdown_list;	
	}

	/*
	|--------------------------------------------------------------------------
	| RELATIONS
	|--------------------------------------------------------------------------
	*/
	// relation: hasMany
	public function leads()
	{
		return $this->hasMany(Lead::class, 'currency_id');
	}

	public function contacts()
	{
		return $this->hasMany(Contact::class, 'currency_id');
	}

	public function accounts()
	{
		return $this->hasMany(Account::class, 'currency_id');
	}

	public function deals()
	{
		return $this->hasMany(Deal::class, 'currency_id');
	}

	public function estimates()
	{
		return $this->hasMany(Estimate::class, 'currency_id');
	}

	public function invoices()
	{
		return $this->hasMany(Invoice::class, 'currency_id');
	}

	public function items()
	{
		return $this->hasMany(Item::class, 'currency_id');
	}

	public function payments()
	{
		return $this->hasMany(Payment::class, 'currency_id');
	}

	public function expenses()
	{
		return $this->hasMany(Expense::class, 'currency_id');
	}

	public function campaigns()
	{
		return $this->hasMany(Campaign::class, 'currency_id');
	}

	public function goals()
	{
		return $this->hasMany(Goal::class, 'currency_id');
	}
}