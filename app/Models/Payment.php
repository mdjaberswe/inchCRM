<?php

namespace App\Models;

use App\Models\BaseModel;
use App\Models\Traits\FinanceTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class Payment extends BaseModel
{
	use SoftDeletes;
	use RevisionableTrait;
	use FinanceTrait;

	protected $table = 'payments';
	protected $fillable = ['invoice_id', 'amount', 'currency_id', 'payment_method_id', 'payment_date', 'note', 'transaction_id'];
	protected $dates = ['deleted_at'];
	protected $revisionCreationsEnabled = true;

	public static function validate($data)
	{
		$rules = ['invoice_id'		=> 'required|exists:invoices,id,deleted_at,NULL',
				'amount'			=> 'required|numeric',
				'payment_method_id'	=> 'required|exists:payment_methods,id,status,1,deleted_at,NULL',
				'payment_date'		=> 'required|date',
				'transaction_id'	=> 'max:200',
				'note'				=> 'max:65535'];

		return \Validator::make($data, $rules);
	}

	public function setRoute()
	{
		return 'finance-payment';
	}

	public function setPermission()
	{
		return 'finance.payment';
	}

	/*
	|--------------------------------------------------------------------------
	| ACCESSOR
	|--------------------------------------------------------------------------
	*/
	public function getIdFormatAttribute()
	{
		return 'PAY #' . sprintf('%04d', $this->id);
	}

	public function getIdHtmlAttribute($hide_tooltip = null)
	{
		$tooltip = '';
		if($this->note !== null && $this->note !== '' && $hide_tooltip == null) :
			$tooltip = "data-toggle='tooltip' data-placement='top' title='" . str_limit($this->note, 50) . "'";
		endif;

		$id_html = "<a class='edit plain' editid='" . $this->id . "' $tooltip>" . $this->id_format . "</a>";

		return $id_html;
	}

	public function getAmountFormatAttribute()
	{
		return number_format($this->attributes['amount'], 2, $this->currency->decimal_separator, $this->currency->thousand_separator);
	}

	public function getPaymentMethodAttribute()
	{
		return $this->method->name;
	}

	public function getMethodHtmlAttribute($hide_tooltip = null)
	{		
		$tooltip = '';
		if($this->transaction_id !== null && $this->transaction_id !== '' && $hide_tooltip == null) :
			$tooltip = " data-toggle='tooltip' data-placement='top' title='Transaction Id " . $this->transaction_id . "'";
		endif;

		$method = "<span class='capitalize' $tooltip>" . $this->payment_method . "</span>";

		return $method;
	}

	public function getTransactionIdHtmlAttribute()
	{
		$transaction_id = null;		
		$tooltip = '';

		if($this->transaction_id !== null && $this->transaction_id !== '') :
			if(strlen($this->transaction_id) > 17) :
				$tooltip = " data-toggle='tooltip' data-placement='top' title='" . $this->transaction_id . "'";
			endif;

			$transaction_id = "<span $tooltip>" . str_limit($this->transaction_id, 17, '.') . "</span>";
		endif;

		return $transaction_id;
	}

	public function getAccountHtmlAttribute()
	{
		return "<a href='" . route('admin.account.show', $this->invoice->account->id) . "' class='like-txt'>" . $this->invoice->account->account_name . "</a>";
	}
	/*
	|--------------------------------------------------------------------------
	| RELATIONS
	|--------------------------------------------------------------------------
	*/
	// relation: belongsTo
	public function invoice()
	{
		return $this->belongsTo(Invoice::class);
	}

	public function currency()
	{
		return $this->belongsTo(Currency::class, 'currency_id');
	}

	public function method()
	{
		return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
	}
}