<?php

namespace App\Models;

use App\Models\BaseModel;
use App\Models\Traits\FinanceTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class Estimate extends BaseModel
{
	use SoftDeletes;
	use RevisionableTrait;
	use FinanceTrait;

	protected $table = 'estimates';
	protected $fillable = ['account_id', 'contact_id', 'deal_id', 'project_id', 'sale_agent', 'number', 'reference', 'subject', 'status', 'estimate_date', 'expiry_date', 'currency_id', 'discount_type', 'sub_total', 'total_tax', 'total_discount', 'adjustment', 'grand_total', 'term_condition', 'note', 'converted_invoice_id'];
	protected $appends = ['name'];
	protected $dates = ['deleted_at'];
	protected $revisionCreationsEnabled = true;

	public static function validate($data)
	{
		$estimate_date = $data['estimate_date'];
		$estimate_date_minus = date('Y-m-d', strtotime($estimate_date . ' -1 day'));

		$unique_number = "unique:estimates,number";
		if(isset($data['id'])) :
			$id = $data['id'];
			$unique_number = "unique:estimates,number,$id";
		endif;

		$rules = ["account_id"		=> "required|exists:accounts,id,deleted_at,NULL",
				  "number"			=> "required|numeric|min:1|$unique_number",
				  "refference"		=> "max:200",
				  "subject"			=> "max:200",
				  "status"			=> "required|in:draft,sent,accepted,expired,declined",
				  "sale_agent"		=> "required|exists:users,linked_id,linked_type,staff,status,1,deleted_at,NULL",
				  "estimate_date"	=> "required|date",
				  "expiry_date"		=> "date|after:$estimate_date_minus",
				  "currency_id"		=> "required|exists:currencies,id,deleted_at,NULL",
				  "discount_type"	=> "required|in:pre,post,flat",
				  "note"			=> "max:65535",
				  "term_condition"	=> "max:65535"];

		return \Validator::make($data, $rules);
	}

	public function setRoute()
	{
		return 'sale-estimate';
	}

	public function setPermission()
	{
		return 'sale.estimate';
	}

	/*
	|--------------------------------------------------------------------------
	| SCOPES
	|--------------------------------------------------------------------------
	*/
	public function scopeReadableIdentifier($query, $name)
	{
		return $query->where('subject', $name);
	}

	/*
	|--------------------------------------------------------------------------
	| ACCESSOR
	|--------------------------------------------------------------------------
	*/
	public function getNameAttribute()
	{
		return 'EST-' . $this->number_format;
	}

	public function getNumberHtmlAttribute()
	{
		$tooltip = '';
		if(isset($this->subject) && $this->subject != '') :
			$tooltip = " data-toggle='tooltip' data-placement='top' title='" . str_limit($this->subject, 25, '.') . "'";
		endif;

		$number = "<a href='" . route('admin.sale-estimate.show', $this->id) . "'$tooltip>" . $this->name . "</a>";
		
		return $number;
	}

	public function getNumberFormatAttribute()
	{
		return sprintf('%04d', $this->number);
	}

	public function getGrandTotalFormatAttribute()
	{
		return number_format($this->attributes['grand_total'], 2, $this->currency->decimal_separator, $this->currency->thousand_separator);
	}
	
	public function getTotalHtmlAttribute()
	{
		return place_currency_symbol($this->grand_total_format, $this->currency->symbol_html, $this->currency->symbol_position);
	}

	public function getGrandTotalInputAttribute()
	{
		return number_format($this->attributes['grand_total'], 2, '.', '');
	}

	public function getSubTotalFormatAttribute()
	{
		return number_format($this->attributes['sub_total'], 2, $this->currency->decimal_separator, $this->currency->thousand_separator);
	}

	public function getTotalDiscountFormatAttribute()
	{
		return number_format($this->attributes['total_discount'], 2, $this->currency->decimal_separator, $this->currency->thousand_separator);
	}

	public function getTotalTaxFormatAttribute()
	{
		return number_format($this->attributes['total_tax'], 2, $this->currency->decimal_separator, $this->currency->thousand_separator);
	}

	public function getAdjustmentFormatAttribute()
	{
		return number_format($this->attributes['adjustment'], 2, $this->currency->decimal_separator, $this->currency->thousand_separator);
	}

	public function getAdjustmentInputAttribute()
	{
		return number_format($this->attributes['adjustment'], 2, '.', '');
	}

	public function getDateHtmlAttribute()
	{
		$date = '';
		$span_class = 'shadow normal';

		if(isset($this->expiry_date)) :
			$span_class = 'shadow';
			$date .= "<span class='c-danger' data-toggle='tooltip' data-placement='right' title='Expiry Date'>" . $this->expiry_date . "</span>";
			$date .= '<br>';
		endif;

		if(isset($this->estimate_date)) :
			$date .= "<span class='" . $span_class . "' data-toggle='tooltip' data-placement='right' title='Estimate Date'>" . $this->estimate_date . "</span>";
		endif;

		return $date;
	}

	public function getStatusHtmlAttribute()
	{
		$status = $this->status;
		$status_html = '';

		switch($status) :
			case 'draft' :
				$status_html = "<span class='btn btn-primary status'>Draft</span>";
			break;

			case 'sent' :
				$status_html = "<span class='btn btn-info status'>Sent</span>";
			break;

			case 'accepted' :
				$status_html = "<span class='btn btn-success status'>Accepted</span>";
			break;

			case 'expired' :
				$status_html = "<span class='btn btn-warning status'>Expired</span>";
			break;

			case 'declined' :
				$status_html = "<span class='btn btn-danger status'>Declined</span>";
			break;

			default : $status_html = "<span class='btn btn-danger status'>Invalid</span>";
		endswitch;

		return $status_html;
	}

	public function getUniqueUnitAttribute()
	{
		$unique_unit = 'Qty';
		$units = $this->itemsheets->pluck('unit')->toArray();		
		if(count(array_unique($units)) == 1 && strtolower(end($units)) != 'unit') :
			$unique_unit = end($units);
		endif;	

		return $unique_unit;
	}

	public function getTfootColspanAttribute()
	{
		$tfoot_colspan = 5;

		if($this->total_discount == 0) :
			$tfoot_colspan = $tfoot_colspan - 1;
		endif;	

		if($this->total_tax == 0) :
			$tfoot_colspan = $tfoot_colspan - 1;
		endif;

		return $tfoot_colspan;
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

	public function contact()
	{
		return $this->belongsTo(Contact::class);
	}

	public function deal()
	{
		return $this->belongsTo(Deal::class);
	}

	public function project()
	{
		return $this->belongsTo(Project::class);
	}

	public function saleagent()
	{
		return $this->belongsTo(Staff::class, 'sale_agent')->withTrashed();
	}

	public function invoice()
	{
		return $this->belongsTo(Invoice::class, 'converted_invoice_id');
	}

	public function currency()
	{
		return $this->belongsTo(Currency::class, 'currency_id');
	}

	// relation: morphMany
	public function tasks()
	{
		return $this->morphMany(Task::class, 'linked');
	}

	public function calls()
	{
		return $this->morphMany(Call::class, 'related');
	}

	public function events()
	{
		return $this->morphMany(Event::class, 'linked');
	}

	public function itemsheets()
	{
		return $this->morphMany(ItemSheet::class, 'linked');
	}

	public function notificationInfos()
	{
		return $this->morphMany(NotificationInfo::class, 'linked');
	}

	public function linearNotes()
	{
		return $this->morphMany(NoteInfo::class, 'linked');
	}

	public function notes()
	{
		return $this->morphMany(Note::class, 'linked');
	}

	public function attachfiles()
	{
		return $this->morphMany(AttachFile::class, 'linked');
	}
}