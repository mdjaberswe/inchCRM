<?php

namespace App\Http\Controllers\Admin;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\ItemSheet;
use Illuminate\Http\Request;
use App\Library\DatatablesManager;
use App\Http\Controllers\Admin\AdminBaseController;

class AdminInvoiceController extends AdminBaseController
{
	public function __construct()
	{
		parent::__construct();

		$this->middleware('admin:sale.invoice.view', ['only' => ['index', 'invoiceData', 'show']]);
		$this->middleware('admin:sale.invoice.create', ['only' => ['create', 'store']]);
		$this->middleware('admin:sale.invoice.edit', ['only' => ['edit', 'update']]);
		$this->middleware('admin:sale.invoice.delete', ['only' => ['destroy', 'bulkDestroy']]);

		$this->middleware('admin:finance.payment.create', ['only' => ['paymentStore']]);
		$this->middleware('admin:finance.payment.edit', ['only' => ['paymentEdit', 'paymentUpdate']]);
	}



	public function index()
	{
		$page = ['title' => 'Invoices List', 'item' => 'Invoice', 'field' => 'invoices', 'view' => 'admin.sale.invoice', 'route' => 'admin.sale-invoice', 'modal_create' => false, 'modal_edit' => false, 'bulk' => 'email,sms,update', 'mass_update_permit' => permit('mass_update.invoice'), 'mass_del_permit' => permit('mass_delete.invoice')];
		$table = ['thead' => ['INVOICE #', ['ACCOUNT', 'style' => 'min-width: 110px'], 'STATUS', 'TOTAL', ['INVOICE&nbsp;DATE', 'style' => 'min-width: 80px'], ['DUE&nbsp;DATE', 'style' => 'min-width: 80px'], 'SALES AGENT'], 'checkbox' => Invoice::allowMassAction(), 'action' => Invoice::allowAction()];
		$table['json_columns'] = table_json_columns(['checkbox', 'number', 'account', 'status', 'total' => ['className' => 'align-r'], 'invoice_date', 'date_pay_before', 'sale_agent', 'action'], Invoice::hideColumns());

		return view('admin.sale.invoice.index', compact('page', 'table'));
	}



	public function invoiceData(Request $request)
	{
		if($request->ajax()) :
			$invoices = Invoice::latest('id')->get();
			return DatatablesManager::invoiceData($invoices, $request);
		endif;
	}



	public function create(Request $request)
	{
		$page['title'] = 'Add New Invoice';
		$redirect = isset($request->module) ? route('admin.' . $request->module . '.show', [non_property_checker($request, $request->module), 'invoices']) : null;
		$default_invoice = (object)['account_id' => $request->account, 'contact_id' => $request->contact, 'deal_id' => $request->deal, 'project_id' => $request->project, 'redirect' => $redirect];

		return view('admin.sale.invoice.create', compact('page', 'default_invoice'));
	}



	public function store(Request $request)
	{
		$data = $request->all();
		$validation = Invoice::validate($data);

		if($request->ajax()) :
			$status = true;
			$errors = null;		

			if($validation->fails()) :
				$status = false;
				$errors = $validation->getMessageBag()->toArray();
			endif;

			return response()->json(['status' => $status, 'errors' => $errors]);
		endif;

		if($validation->fails()) :
			return redirect()->back()->withErrors($validation);
		endif;

		$invoice = new Invoice;
		$invoice->account_id = $request->account_id;
		$invoice->contact_id = $request->contact_id;
		$invoice->deal_id = $request->deal_id;
		$invoice->project_id = $request->project_id;
		$invoice->sale_agent = $request->sale_agent;
		$invoice->number = $request->number;
		$invoice->reference = $request->reference;
		$invoice->subject = $request->subject;
		$invoice->status = $request->status;
		$invoice->invoice_date = $request->invoice_date;
		$invoice->date_pay_before = null_if_empty($request->date_pay_before);
		$invoice->currency_id = $request->currency_id;
		$invoice->discount_type = $request->discount_type;
		$invoice->sub_total = $request->sub_total;
		$invoice->total_tax = $request->total_tax;
		$invoice->total_discount = $request->total_discount;
		$invoice->adjustment = $request->adjustment;
		$invoice->grand_total = $request->grand_total;
		$invoice->term_condition = $request->term_condition;
		$invoice->note = $request->note;
		$invoice->save();

		// Store Data into ItemSheet
		$item_sheets = [];
		$item_names = $request->item_name;
		$item_quantities = $request->quantity;
		$item_rates = $request->rate;
		$item_discounts = $request->discount;
		$item_taxes = $request->tax;

		$i = 0;
		foreach($item_names as $item_name) :
			$item_sheets[] = ['linked_id' => $invoice->id, 'linked_type' => 'invoice', 'item' => $item_name, 'quantity' => $item_quantities[$i], 'unit' => 'Unit', 'rate' => $item_rates[$i], 'tax' => $item_taxes[$i], 'discount' => $item_discounts[$i], 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')];
			$i++;
		endforeach;

		ItemSheet::insert($item_sheets);

		$success_message = 'Invoice has been created.';

		if(isset($request->redirect)) :
			if(isset($request->add_new) && $request->add_new == 1) :
				$default_invoice = ['account_id' => $request->account_id, 'contact_id' => $request->contact_id, 'deal_id' => $request->deal_id, 'project_id' => $request->project_id, 'redirect' => $request->redirect];
				return redirect(route('admin.sale-invoice.create', $default_invoice, false))->withSuccess_message($success_message);
			endif;
				
			return redirect()->to($request->redirect)->withSuccess_message($success_message);
		endif;

		if(isset($request->add_new) && $request->add_new == 1) :
			return redirect(route('admin.sale-invoice.create', [], false))->withSuccess_message($success_message);
		endif;	

		return redirect(route('admin.sale-invoice.show', $invoice->id, false))->withSuccess_message($success_message);
	}



	public function show(Invoice $invoice)
	{
		$page = ['title' => 'INV #' . $invoice->number_format . ' ' . $invoice->subject, 'item_title' => breadcrumbs_render("admin.sale-invoice.index:Invoices|" . $invoice->name), 'item' => 'Payment', 'modal_bulk_delete' => false, 'modal_size' => 'medium'];
		return view('admin.sale.invoice.show', compact('page', 'invoice'));
	}



	public function edit(Request $request, Invoice $invoice)
	{
		$page['title'] = 'INV #' . $invoice->number_format . ' ' . $invoice->subject;
		$default_invoice = (object)['redirect' => isset($request->parent_module) ? route('admin.' . $request->parent_module . '.show', [non_property_checker($invoice, $request->parent_module . '_id'), 'invoices']) : null];
		return view('admin.sale.invoice.edit', compact('page', 'invoice', 'default_invoice'));
	}



	public function update(Request $request, Invoice $invoice)
	{
		$data = $request->all();
		$data['current_status'] = $invoice->status;
		$validation = Invoice::validate($data);

		if($request->ajax()) :
			$status = true;
			$errors = null;		

			if($validation->fails()) :
				$status = false;
				$errors = $validation->getMessageBag()->toArray();
			endif;

			return response()->json(['status' => $status, 'errors' => $errors]);
		endif;

		if($validation->fails()) :
			return redirect()->back()->withErrors($validation);
		endif;

		if($invoice->id != $request->id) :
			$warning_message = 'Sorry, Something went wrong! Please try again.';
			return redirect()->back()->withWarning_message($warning_message);
		endif;

		$invoice->account_id = $request->account_id;
		$invoice->contact_id = $request->contact_id;
		$invoice->deal_id = $request->deal_id;
		$invoice->project_id = $request->project_id;
		$invoice->sale_agent = $request->sale_agent;
		$invoice->number = $request->number;
		$invoice->reference = $request->reference;
		$invoice->subject = $request->subject;		
		$invoice->invoice_date = $request->invoice_date;
		$invoice->date_pay_before = null_if_empty($request->date_pay_before);
		$invoice->currency_id = $request->currency_id;
		$invoice->discount_type = $request->discount_type;
		$invoice->sub_total = $request->sub_total;
		$invoice->total_tax = $request->total_tax;
		$invoice->total_discount = $request->total_discount;
		$invoice->adjustment = $request->adjustment;
		$invoice->grand_total = $request->grand_total;
		$invoice->term_condition = $request->term_condition;
		$invoice->note = $request->note;

		if($invoice->status == 'draft' || $invoice->status == 'unpaid') :
			$invoice->status = $request->status;
		endif;

		$invoice->save();

		// Update Data into ItemSheet
		$invoice->itemsheets()->delete();

		$item_sheets = [];
		$item_names = $request->item_name;
		$item_quantities = $request->quantity;
		$item_rates = $request->rate;
		$item_discounts = $request->discount;
		$item_taxes = $request->tax;

		$i = 0;
		foreach($item_names as $item_name) :
			$item_sheets[] = ['linked_id' => $invoice->id, 'linked_type' => 'invoice', 'item' => $item_name, 'quantity' => $item_quantities[$i], 'unit' => 'Unit', 'rate' => $item_rates[$i], 'tax' => $item_taxes[$i], 'discount' => $item_discounts[$i], 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')];
			$i++;
		endforeach;

		ItemSheet::insert($item_sheets);

		$invoice->payments()->update(['currency_id' => $request->currency_id]);

		$success_message = 'Invoice has been updated.';

		if(isset($request->redirect)) :	
			return redirect()->to($request->redirect)->withSuccess_message($success_message);
		endif;	

		return redirect(route('admin.sale-invoice.show', $invoice->id, false))->withSuccess_message($success_message);
	}



	public function destroy(Request $request, Invoice $invoice)
	{
		if($request->ajax()) :
			$status = true;

			if($invoice->id != $request->id) :
				$status = false;
			endif;

			if($status == true) :
				$invoice->delete();
			endif;
			
			return response()->json(['status' => $status]);
		endif;
	}



	public function bulkDestroy(Request $request)
	{
		if($request->ajax()) :
			$invoices = $request->invoices;

			$status = true;

			if(isset($invoices) && count($invoices) > 0) :
				Invoice::whereIn('id', $invoices)->delete();
			else :
				$status = false;
			endif;

			return response()->json(['status' => $status]);
		endif;
	}



	public function bulkEmail(Request $request)
	{
		if($request->ajax()) :
			$status = true;
			$errors = null;
			$data = $request->all();

			$rules = ['from' => 'required|email', 'subject' => 'required'];
			$validation = \Validator::make($data, $rules);

			if($validation->passes()) :
				$ids = $request->invoices;
				if(isset($ids) && count($ids) > 0) :
					$account_ids = Invoice::whereIn('id', $ids)->pluck('account_id')->toArray();
					$emails = Account::whereIn('id', $account_ids)->pluck('account_email')->toArray();
					$mail_message = $request->message;
					$from = $request->from;
					$from_name = auth()->user()->first_name . ' ' . auth()->user()->last_name;
					$subject = $request->subject;

					\Mail::queue('emails.regular', compact('mail_message'), function($message) use($emails, $from, $from_name, $subject)
					{
						$message->from($from, $from_name)
								->to($emails)
								->subject($subject);
					});
				endif;
			else :
				$status = false;
				$errors = $validation->getMessageBag()->toArray();
			endif;

			return response()->json(['status' => $status, 'errors' => $errors]);
		endif;
	}



	public function invoicePaymentData(Request $request, Invoice $invoice)
	{
		if($request->ajax()) :
			$payments = Payment::whereInvoice_id($invoice->id)->orderBy('id')->get();
			return DatatablesManager::invoicePaymentData($payments, $request);
		endif;
	}



	public function paymentStore(Request $request, Invoice $invoice)
	{
		if($request->ajax()) :
			$status = true;
			$errors = null;
			$data = $request->all();

			if(isset($invoice) && isset($request->invoice_id) && $invoice->id == $request->invoice_id) :
				$validation = Payment::validate($data);
				if($validation->passes()) :
					$payment = new Payment;
					$payment->invoice_id = $invoice->id;
					$payment->amount = $request->amount;
					$payment->payment_method_id = $request->payment_method_id;
					$payment->payment_date = $request->payment_date;
					$payment->currency_id = $invoice->currency_id;
					$payment->transaction_id = null_if_empty($request->transaction_id);
					$payment->note = null_if_empty($request->note);
					$payment->save();
				else :
					$status = false;
					$errors = $validation->getMessageBag()->toArray();
				endif;
			else :
				$status = false;
			endif;

			return response()->json(['status' => $status, 'errors' => $errors]);
		endif;
	}



	public function paymentEdit(Request $request, Invoice $invoice, Payment $payment)
	{
		if($request->ajax()) :
			$status = true;
			$info = null;

			if(isset($invoice) && isset($payment) && isset($request->id)) :
				if($payment->id == $request->id) :
					$info = $payment;
				else :
					$status = false;
				endif;
			else :
				$status = false;
			endif;

			return response()->json(['status' => $status, 'info' => $info]);
		endif;

		return redirect()->route('admin.sale-invoice.show', $invoice->id);
	}



	public function paymentUpdate(Request $request, Invoice $invoice, Payment $payment)
	{
		if($request->ajax()) :
			$status = true;
			$errors = null;
			$data = $request->all();

			if(isset($invoice) && isset($request->invoice_id) && $invoice->id == $request->invoice_id && isset($payment) && isset($request->id) && $payment->id == $request->id) :
				$validation = Payment::validate($data);
				if($validation->passes()) :
					$payment->amount = $request->amount;
					$payment->payment_method_id = $request->payment_method_id;
					$payment->payment_date = $request->payment_date;
					$payment->transaction_id = null_if_empty($request->transaction_id);
					$payment->note = null_if_empty($request->note);
					$payment->save();
				else :
					$status = false;
					$errors = $validation->getMessageBag()->toArray();
				endif;
			else :
				$status = false;
			endif;

			return response()->json(['status' => $status, 'errors' => $errors]);
		endif;
	}



	public function connectedInvoiceData(Request $request, $module_name, $module_id)
	{
		if($request->ajax()) :
			$module = morph_to_model($module_name)::find($module_id);
			if(isset($module)) :
				$invoices = $module->invoices()->latest('id')->get()->map(function($invoice) use ($module_name) { return $invoice->setAttribute('parent_module', $module_name); });
				return DatatablesManager::connectedInvoiceData($invoices, $request);
			endif;
			
			return null;	
		endif;
	}
}