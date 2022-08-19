<?php

namespace App\Http\Controllers\Admin;

use App\Models\Expense;
use App\Models\Project;
use App\Models\Account;
use Illuminate\Http\Request;
use App\Library\DatatablesManager;
use App\Http\Controllers\Admin\AdminBaseController;

class AdminExpenseController extends AdminBaseController
{
	public function __construct()
	{
		parent::__construct();

		$this->middleware('admin:finance.expense.view', ['only' => ['index', 'expenseData']]);
		$this->middleware('admin:finance.expense.create', ['only' => ['store']]);
		$this->middleware('admin:finance.expense.edit', ['only' => ['edit', 'update']]);
		$this->middleware('admin:finance.expense.delete', ['only' => ['destroy', 'bulkDestroy']]);
	}



	public function index()
	{
		$page = ['title' => 'Expenses List', 'item' => 'Expense', 'field' => 'expenses', 'view' => 'admin.expense', 'route' => 'admin.finance-expense', 'permission' => 'finance.expense', 'mass_update_permit' => permit('mass_update.expense'), 'mass_del_permit' => permit('mass_delete.expense')];
		$table = ['thead' => ['DATE', 'CATEGORY', ['NAME', 'style' => 'min-width: 250px'], ['AMOUNT', 'style' => 'min-width: 100px'], 'METHOD', ['ACCOUNT NAME', 'style' => 'min-width: 110px'], 'PROJECT'], 'checkbox' => Expense::allowMassAction(), 'action' => Expense::allowAction()];
		$table['json_columns'] = table_json_columns(['checkbox', 'expense_date', 'category', 'name', 'amount' => ['className' => 'align-r'], 'payment_method', 'account', 'project', 'action'], Expense::hideColumns());

		return view('admin.expense.index', compact('page', 'table'));
	}



	public function expenseData(Request $request)
	{
		if($request->ajax()) :
			$expenses = Expense::latest('id')->get();
			return DatatablesManager::expenseData($expenses, $request);
		endif;
	}



	public function store(Request $request)
	{
		if($request->ajax()) :
			$status = true;
			$errors = null;
			$data = $request->all();
			$validation = Expense::validate($data);

			if($validation->passes()) :
				$expense = new Expense;
				$expense->expense_category_id = $request->expense_category;
				$expense->name = $request->name;
				$expense->amount = $request->amount;
				$expense->currency_id = $request->currency_id;
				$expense->payment_method_id = null_if_empty($request->payment_method_id);
				$expense->expense_date = $request->expense_date;
				$expense->billable = $request->account !== '' && isset($request->billable) ? $request->billable : 0;				
				$expense->account_id = null_if_empty($request->account);
				$expense->project_id = null_if_empty($request->project);
				$expense->save();
			else :
				$status = false;
				$errors = $validation->getMessageBag()->toArray();
			endif;

			return response()->json(['status' => $status, 'errors' => $errors]);
		endif;
	}



	public function edit(Request $request, Expense $expense)
	{
		if($request->ajax()) :
			$status = true;
			$info = null;

			if(isset($expense) && isset($request->id)) :
				if($expense->id == $request->id) :
					$info = $expense->toArray();
					$info['expense_category'] = $expense->expense_category_id;								
					$info['project'] = $expense->project_id !== null ? $expense->project_id : '';
					$info['account'] = $expense->account_id !== null ? $expense->account_id : '';

					$info['hide'] = [];
					if($info['account'] == '') :
						$info['hide'][] = 'project';
						$info['hide'][] = 'billable';
					endif;	

					$info = (object)$info; 
				else :
					$status = false;
				endif;
			else :
				$status = false;
			endif;

			return response()->json(['status' => $status, 'info' => $info]);
		endif;

		return redirect()->route('admin.finance-expense.index');
	}



	public function update(Request $request, Expense $expense)
	{
		if($request->ajax()) :
			$status = true;
			$errors = null;
			$data = $request->all();

			if(isset($expense) && isset($request->id) && $expense->id == $request->id) :
				$validation = Expense::validate($data);
				if($validation->passes()) :
					$expense->expense_category_id = $request->expense_category;
					$expense->name = $request->name;
					$expense->amount = $request->amount;
					$expense->currency_id = $request->currency_id;
					$expense->payment_method_id = null_if_empty($request->payment_method_id);
					$expense->expense_date = $request->expense_date;
					$expense->billable = $request->account !== '' && isset($request->billable) ? $request->billable : 0;				
					$expense->account_id = null_if_empty($request->account);
					$expense->project_id = null_if_empty($request->project);
					$expense->update();
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



	public function destroy(Request $request, Expense $expense)
	{
		if($request->ajax()) :
			$status = true;

			if($expense->id != $request->id) :
				$status = false;
			endif;

			if($status == true) :
				$expense->delete();
			endif;	
			
			return response()->json(['status' => $status]);
		endif;	
	}



	public function bulkDestroy(Request $request)
	{
		if($request->ajax()) :
			$expenses = $request->expenses;

			$status = true;

			if(isset($expenses) && count($expenses) > 0) :
				Expense::whereIn('id', $expenses)->delete();
			else :
				$status = false;
			endif;

			return response()->json(['status' => $status]);
		endif;
	}
}