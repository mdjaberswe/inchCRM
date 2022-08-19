<?php

namespace App\Http\Controllers\Admin;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use App\Library\DatatablesManager;
use App\Http\Controllers\Admin\AdminBaseController;

class AdminPaymentMethodController extends AdminBaseController
{
	public function __construct()
	{
		parent::__construct();

		$this->middleware('admin:settings.payment_method.view', ['only' => ['index', 'paymentMethodData']]);
		$this->middleware('admin:settings.payment_method.create', ['only' => ['store']]);
		$this->middleware('admin:settings.payment_method.edit', ['only' => ['edit', 'update', 'updateStatus']]);
		$this->middleware('admin:settings.payment_method.delete', ['only' => ['destroy']]);
	}



	public function index()
	{
		$page = ['title' => 'Payment Methods', 'item' => 'Payment Method', 'list_title' => "Payment Methods <span class='para-hint-sm'>(offline)</span>", 'field' => 'payment_methods', 'view' => 'admin.paymentmethod', 'route' => 'admin.administration-setting-offline-payment', 'plain_route' => 'admin.paymentmethod', 'permission' => 'settings.payment_method', 'subnav' => 'setting', 'modal_bulk_delete' => false, 'modal_size' => 'medium', 'save_and_new' => false];
		$table = ['thead' => [['NAME', 'style' => 'min-width: 200px'], 'DESCRIPTION', ['STATUS', 'data_class' => 'center', 'style' => 'min-width: 110px; max-width: 120px']], 'action' => PaymentMethod::allowAction()];
		$table['json_columns'] = table_json_columns(['sequence' => ['className' => 'reorder'], 'name', 'description', 'status', 'action'], PaymentMethod::hideColumns());
		$reset_position = PaymentMethod::resetPosition();

		return view('admin.paymentmethod.index', compact('page', 'table', 'reset_position'));
	}



	public function paymentMethodData(Request $request)
	{
		if($request->ajax()) :
			$payment_methods = PaymentMethod::whereMasked(0)->orderBy('position')->get(['id', 'position', 'name', 'description', 'masked', 'status']);
			return DatatablesManager::paymentMethodData($payment_methods, $request);
		endif;
	}



	public function store(Request $request)
	{
		if($request->ajax()) :
			$status = true;
			$errors = null;
			$data = $request->all();
			$validation = PaymentMethod::validate($data);
			$bottom_position = PaymentMethod::getBottomPosition();

			if($validation->passes()) :				
				$payment_method = new PaymentMethod;
				$payment_method->name = $request->name;
				$payment_method->description = null_if_empty($request->description);				
				$payment_method->status = isset($request->status) ? $request->status : 0;
				$payment_method->position = $bottom_position;	
				$payment_method->save();				
			else :
				$status = false;
				$errors = $validation->getMessageBag()->toArray();
			endif;

			return response()->json(['status' => $status, 'errors' => $errors]);
		endif;
	}



	public function edit(Request $request, PaymentMethod $payment_method)
	{
		if($request->ajax()) :
			$status = true;
			$info = null;

			if(isset($payment_method) && isset($request->id)) :
				if($payment_method->id == $request->id && !$payment_method->masked) :
					$info = $payment_method;
				else :
					$status = false;
				endif;
			else :
				$status = false;
			endif;

			return response()->json(['status' => $status, 'info' => $info]);
		endif;

		return redirect()->route('admin.administration-setting-offline-payment.index');
	}



	public function update(Request $request, PaymentMethod $payment_method)
	{
		if($request->ajax()) :
			$status = true;
			$errors = null;
			$data = $request->all();

			if(isset($payment_method) && isset($request->id) && $payment_method->id == $request->id && !$payment_method->masked) :
				$validation = PaymentMethod::validate($data);

				if($validation->passes()) :					
					$payment_method->name = $request->name;
					$payment_method->description = null_if_empty($request->description);
					$payment_method->status = isset($request->status) ? $request->status : 0;
					$payment_method->save();
				else :
					$status = false;
					$errors = $validation->getMessageBag()->toArray();
				endif;
			else :
				$status = false;
			endif;

			return response()->json(['status' => $status, 'errors' => $errors, 'saveId' => $request->id]);
		endif;
	}	



	public function destroy(Request $request, PaymentMethod $payment_method)
	{
		if($request->ajax()) :
			$status = true;

			if($payment_method->id != $request->id || $payment_method->masked || !$payment_method->can_delete) :
				$status = false;
			endif;

			if($status == true) :
				$payment_method->delete();
			endif;	
			
			return response()->json(['status' => $status]);
		endif;	
	}



	public function updateStatus(Request $request)
	{
		if($request->ajax()) :
			$status = false;
			$checked = null;

			if(isset($request->id) && isset($request->checked)) :
				$payment_method = PaymentMethod::whereId($request->id)->first();
				if(isset($payment_method)) :
					$checked = $request->checked ? 1 : 0;
					$payment_method->update(['status' => $checked]);
					$status = true;
				endif;
			endif;

			return response()->json(['status' => $status, 'checked' => $checked]);
		endif;
	}
}