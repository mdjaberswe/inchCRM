<?php

namespace App\Http\Controllers\Admin;

use App\Models\Estimate;
use App\Models\ItemSheet;
use Illuminate\Http\Request;
use App\Library\DatatablesManager;
use App\Http\Controllers\Admin\AdminBaseController;

class AdminEstimateController extends AdminBaseController
{
	public function __construct()
	{
		parent::__construct();

		$this->middleware('admin:sale.estimate.view', ['only' => ['index', 'estimateData', 'show']]);
		$this->middleware('admin:sale.estimate.create', ['only' => ['create', 'store']]);
		$this->middleware('admin:sale.estimate.edit', ['only' => ['edit', 'update']]);
		$this->middleware('admin:sale.estimate.delete', ['only' => ['destroy', 'bulkDestroy']]);
	}



	public function index()
	{
		$page = ['title' => 'Estimates List', 'item' => 'Estimate', 'field' => 'estimates', 'view' => 'admin.sale.estimate', 'route' => 'admin.sale-estimate', 'modal_create' => false, 'modal_edit' => false, 'bulk' => 'email,sms,update,convert', 'mass_update_permit' => permit('mass_update.estimate'), 'mass_del_permit' => permit('mass_delete.estimate')];
		$table = ['thead' => ['ESTIMATE #', ['ACCOUNT', 'style' => 'min-width: 110px'], 'STATUS', 'TOTAL', ['ESTIMATE&nbsp;DATE', 'style' => 'min-width: 80px'], ['EXPIRY&nbsp;DATE', 'style' => 'min-width: 80px'], 'SALES&nbsp;AGENT'], 'checkbox' => Estimate::allowMassAction(), 'action' => Estimate::allowAction()];
		$table['json_columns'] = table_json_columns(['checkbox', 'number', 'account', 'status', 'total' => ['className' => 'align-r'], 'estimate_date', 'expiry_date', 'sale_agent', 'action'], Estimate::hideColumns());

		return view('admin.sale.estimate.index', compact('page', 'table'));
	}



	public function estimateData(Request $request)
	{
		if($request->ajax()) :
			$estimates = Estimate::latest('id')->get();
			return DatatablesManager::estimateData($estimates, $request);
		endif;
	}



	public function create(Request $request)
	{
		$page['title'] = 'Add New Estimate';
		$redirect = isset($request->module) ? route('admin.' . $request->module . '.show', [non_property_checker($request, $request->module), 'estimates']) : null;
		$default_estimate = (object)['account_id' => $request->account, 'contact_id' => $request->contact, 'deal_id' => $request->deal, 'project_id' => $request->project, 'redirect' => $redirect];
		return view('admin.sale.estimate.create', compact('page', 'default_estimate'));
	}



	public function store(Request $request)
	{
		$data = $request->all();
		$validation = Estimate::validate($data);

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

		$estimate = new Estimate;
		$estimate->account_id = $request->account_id;
		$estimate->contact_id = $request->contact_id;
		$estimate->deal_id = $request->deal_id;
		$estimate->project_id = $request->project_id;
		$estimate->sale_agent = $request->sale_agent;
		$estimate->number = $request->number;
		$estimate->reference = $request->reference;
		$estimate->subject = $request->subject;
		$estimate->status = $request->status;
		$estimate->estimate_date = $request->estimate_date;
		$estimate->expiry_date = null_if_empty($request->expiry_date);
		$estimate->currency_id = $request->currency_id;
		$estimate->discount_type = $request->discount_type;
		$estimate->sub_total = $request->sub_total;
		$estimate->total_tax = $request->total_tax;
		$estimate->total_discount = $request->total_discount;
		$estimate->adjustment = $request->adjustment;
		$estimate->grand_total = $request->grand_total;
		$estimate->term_condition = $request->term_condition;
		$estimate->note = $request->note;		
		$estimate->save();

		// Store Data into ItemSheet
		$item_sheets = [];
		$item_names = $request->item_name;
		$item_quantities = $request->quantity;
		$item_rates = $request->rate;
		$item_discounts = $request->discount;
		$item_taxes = $request->tax;

		$i = 0;
		foreach($item_names as $item_name) :
			$item_sheets[] = ['linked_id' => $estimate->id, 'linked_type' => 'estimate', 'item' => $item_name, 'quantity' => $item_quantities[$i], 'unit' => 'Unit', 'rate' => $item_rates[$i], 'tax' => $item_taxes[$i], 'discount' => $item_discounts[$i], 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')];
			$i++;
		endforeach;

		ItemSheet::insert($item_sheets);

		$success_message = 'Estimate has been created.';

		if(isset($request->redirect)) :
			if(isset($request->add_new) && $request->add_new == 1) :
				$default_estimate = ['account_id' => $request->account_id, 'contact_id' => $request->contact_id, 'deal_id' => $request->deal_id, 'project_id' => $request->project_id, 'redirect' => $request->redirect];
				return redirect(route('admin.sale-estimate.create', $default_estimate, false))->withSuccess_message($success_message);
			endif;
				
			return redirect()->to($request->redirect)->withSuccess_message($success_message);
		endif;	

		if(isset($request->add_new) && $request->add_new == 1) :
			return redirect(route('admin.sale-estimate.create', [], false))->withSuccess_message($success_message);
		endif;	

		return redirect(route('admin.sale-estimate.show', $estimate->id, false))->withSuccess_message($success_message);
	}



	public function show(Estimate $estimate)
	{
		$page['title'] = 'EST #' . $estimate->number_format . ' ' . $estimate->subject;
		return view('admin.sale.estimate.show', compact('page', 'estimate'));
	}



	public function edit(Request $request, Estimate $estimate)
	{
		$page['title'] = 'EST #' . $estimate->number_format . ' ' . $estimate->subject;
		$default_estimate = (object)['redirect' => isset($request->parent_module) ? route('admin.' . $request->parent_module . '.show', [non_property_checker($estimate, $request->parent_module . '_id'), 'estimates']) : null];
		return view('admin.sale.estimate.edit', compact('page', 'estimate', 'default_estimate'));
	}



	public function update(Request $request, Estimate $estimate)
	{
		$data = $request->all();
		$validation = Estimate::validate($data);

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

		if($estimate->id != $request->id) :
			$warning_message = 'Sorry, Something went wrong! Please try again.';
			return redirect()->back()->withWarning_message($warning_message);
		endif;

		$estimate->account_id = $request->account_id;
		$estimate->contact_id = $request->contact_id;
		$estimate->deal_id = $request->deal_id;
		$estimate->project_id = $request->project_id;
		$estimate->sale_agent = $request->sale_agent;
		$estimate->number = $request->number;
		$estimate->reference = $request->reference;
		$estimate->subject = $request->subject;
		$estimate->status = $request->status;
		$estimate->estimate_date = $request->estimate_date;
		$estimate->currency_id = $request->currency_id;
		$estimate->discount_type = $request->discount_type;
		$estimate->sub_total = $request->sub_total;
		$estimate->total_tax = $request->total_tax;
		$estimate->total_discount = $request->total_discount;
		$estimate->adjustment = $request->adjustment;
		$estimate->grand_total = $request->grand_total;
		$estimate->term_condition = $request->term_condition;
		$estimate->note = $request->note;
		$estimate->expiry_date = null_if_empty($request->expiry_date);
		$estimate->save();

		// Update Data into ItemSheet
		$estimate->itemsheets()->delete();

		$item_sheets = [];
		$item_names = $request->item_name;
		$item_quantities = $request->quantity;
		$item_rates = $request->rate;
		$item_discounts = $request->discount;
		$item_taxes = $request->tax;

		$i = 0;
		foreach($item_names as $item_name) :
			$item_sheets[] = ['linked_id' => $estimate->id, 'linked_type' => 'estimate', 'item' => $item_name, 'quantity' => $item_quantities[$i], 'unit' => 'Unit', 'rate' => $item_rates[$i], 'tax' => $item_taxes[$i], 'discount' => $item_discounts[$i], 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')];
			$i++;
		endforeach;

		ItemSheet::insert($item_sheets);

		$success_message = 'Estimate has been updated.';

		if(isset($request->redirect)) :	
			return redirect()->to($request->redirect)->withSuccess_message($success_message);
		endif;	

		return redirect(route('admin.sale-estimate.show', $estimate->id, false))->withSuccess_message($success_message);
	}



	public function destroy(Request $request, Estimate $estimate)
	{
		if($request->ajax()) :
			$status = true;

			if($estimate->id != $request->id) :
				$status = false;
			endif;

			if($status == true) :
				$estimate->delete();
			endif;
			
			return response()->json(['status' => $status]);
		endif;
	}



	public function bulkDestroy(Request $request)
	{
		if($request->ajax()) :
			$estimates = $request->estimates;

			$status = true;

			if(isset($estimates) && count($estimates) > 0) :
				Estimate::whereIn('id', $estimates)->delete();
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
				$ids = $request->estimates;
				if(isset($ids) && count($ids) > 0) :
					$account_ids = Estimate::whereIn('id', $ids)->pluck('account_id')->toArray();
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



	public function connectedEstimateData(Request $request, $module_name, $module_id)
	{
		if($request->ajax()) :
			$module = morph_to_model($module_name)::find($module_id);
			if(isset($module)) :
				$estimates = $module->estimates()->latest('id')->get()->map(function($estimate) use ($module_name) { return $estimate->setAttribute('parent_module', $module_name); });
				return DatatablesManager::connectedEstimateData($estimates, $request);
			endif;
			
			return null;	
		endif;
	}
}