<?php

namespace App\Http\Controllers\Admin;

use App\Models\Payment;
use Illuminate\Http\Request;
use App\Library\DatatablesManager;
use App\Http\Controllers\Admin\AdminBaseController;

class AdminPaymentController extends AdminBaseController
{
	public function __construct()
	{
		parent::__construct();

		$this->middleware('admin:finance.payment.view', ['only' => ['index', 'paymentData']]);
		$this->middleware('admin:finance.payment.edit', ['only' => ['edit', 'update']]);
		$this->middleware('admin:finance.payment.delete', ['only' => ['destroy', 'bulkDestroy']]);
	}



	public function index()
	{
		$page = ['title' => 'Payments List', 'item' => 'Payment', 'field' => 'payments', 'view' => 'admin.payment', 'route' => 'admin.finance-payment', 'modal_create' => false, 'modal_size' => 'medium', 'mass_update_permit' => permit('mass_update.payment'), 'mass_del_permit' => permit('mass_delete.payment')];
		$table = ['thead' => ['DATE', ['ACCOUNT NAME', 'style' => 'min-width: 110px'], 'INVOICE', ['PAYMENT #', 'style' => 'min-width: 100px'], ['AMOUNT', 'style' => 'min-width: 100px'], 'METHOD', 'TRANSACTION ID'], 'checkbox' => Payment::allowMassAction(), 'action' => Payment::allowAction()];
		$table['json_columns'] = table_json_columns(['checkbox', 'payment_date', 'account', 'invoice', 'payment_id', 'amount' => ['className' => 'align-r'], 'payment_method', 'transaction_id', 'action'], Payment::hideColumns());

		return view('admin.payment.index', compact('page', 'table'));
	}



	public function paymentData(Request $request)
	{
		if($request->ajax()) :
			$payments = Payment::latest('id')->get();
			return DatatablesManager::paymentData($payments, $request);
		endif;
	}



	public function edit(Request $request, Payment $payment)
	{
		if($request->ajax()) :
			$status = true;
			$info = null;

			if(isset($payment) && isset($request->id)) :
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

		return redirect()->route('admin.finance-payment.index');
	}



	public function update(Request $request, Payment $payment)
	{
		if($request->ajax()) :
			$status = true;
			$errors = null;
			$data = $request->all();

			if(isset($payment) && isset($request->id) && $payment->id == $request->id) :
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

			return response()->json(['status' => $status, 'errors' => $errors, 'saveId' => $request->id]);
		endif;
	}	



	public function destroy(Request $request, Payment $payment)
	{
		if($request->ajax()) :
			$status = true;

			if($payment->id != $request->id) :
				$status = false;
			endif;

			if($status == true) :
				$payment->delete();
			endif;	
			
			return response()->json(['status' => $status]);
		endif;	
	}



	public function bulkDestroy(Request $request)
	{
		if($request->ajax()) :
			$payments = $request->payments;

			$status = true;

			if(isset($payments) && count($payments) > 0) :
				Payment::whereIn('id', $payments)->delete();
			else :
				$status = false;
			endif;

			return response()->json(['status' => $status]);
		endif;
	}
}