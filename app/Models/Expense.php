<?php

namespace App\Models;

use App\Models\BaseModel;
use App\Models\Traits\FinanceTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class Expense extends BaseModel
{
	use SoftDeletes;
	use RevisionableTrait;
	use FinanceTrait;

	protected $table = 'expenses';
	protected $fillable = ['expense_category_id', 'name', 'amount', 'currency_id', 'payment_method', 'expense_date', 'billable', 'recurring', 'converted_invoice_id'];
	protected $dates = ['deleted_at'];
	protected $revisionCreationsEnabled = true;

	public static function validate($data)
	{
		$rules = ['expense_category'=> 'required|exists:expense_categories,id,deleted_at,NULL',
				'name'				=> 'max:200',
				'currency_id'		=> 'required|exists:currencies,id,deleted_at,NULL',
				'amount'			=> 'required|numeric',
				'payment_method_id'	=> 'exists:payment_methods,id,status,1,deleted_at,NULL',
				'expense_date'		=> 'required|date',
				'account'			=> 'exists:accounts,id,deleted_at,NULL',
				'project'			=> 'exists:projects,id,account_id,' . $data['account'] . ',deleted_at,NULL',
				'billable'			=> 'boolean'];

		return \Validator::make($data, $rules);
	}

	public function setRoute()
	{
		return 'finance-expense';
	}

	public function setPermission()
	{
		return 'finance.expense';
	}

	/*
	|--------------------------------------------------------------------------
	| ACCESSOR
	|--------------------------------------------------------------------------
	*/
	public function getNameHtmlAttribute()
	{
		$not_invoiced = '';
		if($this->not_invoiced !== null) :
			if($this->not_invoiced == true) :
				$not_invoiced = "<span class='btn btn-warning light status m-top-5'>Not Invoiced</span>";
			endif;

			if($this->not_invoiced == false) :
				$tooltip = "data-toggle='tooltip' data-placement='right' title='INV #" . sprintf('%04d', $this->invoice->number) . "'";
				$not_invoiced = "<a href='" . route('admin.sale-invoice.show', $this->invoice->id) . "'><span class='btn btn-success light status pointer m-top-5' $tooltip>Invoiced</span></a>";
			endif;			
		endif;	

		$tooltip = '';
		if(strlen($this->name) > 34) :
			$tooltip = "data-toggle='tooltip' data-placement='top' title='" . $this->name . "'";
		endif;

		$name = '';
		if($this->name !== null && $this->name !== '')
		{
			$name = "<a class='edit plain' editid='" . $this->id . "' $tooltip>" . str_limit($this->name, 34, '.') . "</a><br>";
		}	

		return $name . $not_invoiced;
	}

	public function getNotInvoicedAttribute()
	{
		if($this->billable == true) :
			if($this->converted_invoice_id == null) :
				return true;
			endif;

			return false;
		endif;		

		return null;
	}

	public function getPaymentMethodAttribute()
	{
		return non_property_checker($this->method, 'name');
	}

	public function getAmountFormatAttribute()
	{
		return number_format($this->attributes['amount'], 2, $this->currency->decimal_separator, $this->currency->thousand_separator);
	}

	public function getProjectHtmlAttribute()
	{
		if($this->project_id !== null) :
			return "<a href='" . route('admin.project.show', $this->project_id) . "'>" . $this->project->name . "</a>";
		endif;
		
		return null;	
	}

	public function getInvoiceHtmlAttribute()
	{
		if($this->converted_invoice_id !== null) :
			return "<a href='" . route('admin.sale-invoice.show', $this->invoice->id) . "'>INV #" . sprintf('%04d', $this->invoice->number) . "</a>";
		endif;

		return null;
	}

	/*
	|--------------------------------------------------------------------------
	| RELATIONS
	|--------------------------------------------------------------------------
	*/
	// relation: belongsTo
	public function account()
	{
		return $this->belongsTo(Account::class);
	}

	public function project()
	{
		return $this->belongsTo(Project::class);
	}

	public function invoice()
	{
		return $this->belongsTo(Invoice::class, 'converted_invoice_id');
	}

	public function category()
	{
		return $this->belongsTo(ExpenseCategory::class, 'expense_category_id');
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