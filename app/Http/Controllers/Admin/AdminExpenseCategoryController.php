<?php

namespace App\Http\Controllers\Admin;

use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use App\Library\DatatablesManager;
use App\Http\Controllers\Admin\AdminBaseController;

class AdminExpenseCategoryController extends AdminBaseController
{
	public function __construct()
	{
		parent::__construct();

		$this->middleware('admin:custom_dropdowns.expense_category.view', ['only' => ['index', 'expenseCategoryData']]);
		$this->middleware('admin:custom_dropdowns.expense_category.create', ['only' => ['store']]);
		$this->middleware('admin:custom_dropdowns.expense_category.edit', ['only' => ['edit', 'update']]);
		$this->middleware('admin:custom_dropdowns.expense_category.delete', ['only' => ['destroy']]);
	}



	public function index()
	{
		$page = ['title' => 'Expense Category List', 'item' => 'Expense Category', 'field' => 'expense_categories', 'view' => 'admin.expensecategory', 'route' => 'admin.administration-dropdown-expensecategory', 'plain_route' => 'admin.expensecategory', 'permission' => 'custom_dropdowns.expense_category', 'subnav' => 'custom-dropdown', 'modal_bulk_delete' => false, 'modal_size' => 'medium', 'save_and_new' => false];
		$table = ['thead' => [['NAME', 'style' => 'min-width: 200px'], 'DESCRIPTION'], 'action' => ExpenseCategory::allowAction()];
		$table['json_columns'] = table_json_columns(['sequence' => ['className' => 'reorder'], 'name', 'description', 'action'], ExpenseCategory::hideColumns());
		$reset_position = ExpenseCategory::resetPosition();

		return view('admin.expensecategory.index', compact('page', 'table', 'reset_position'));
	}



	public function expenseCategoryData(Request $request)
	{
		if($request->ajax()) :
			$expense_categories = ExpenseCategory::orderBy('position')->get(['id', 'position', 'name', 'description']);
			return DatatablesManager::expenseCategoryData($expense_categories, $request);
		endif;
	}



	public function store(Request $request)
	{
		if($request->ajax()) :
			$status = true;
			$errors = null;
			$data = $request->all();
			$validation = ExpenseCategory::validate($data);
			$picked_position_id = $request->position;

			if($validation->passes()) :				
				$position_val = ExpenseCategory::getTargetPositionVal($picked_position_id);

				$expense_category = new ExpenseCategory;
				$expense_category->name = $request->name;
				$expense_category->description = null_if_empty($request->description);
				$expense_category->position = $position_val;
				$expense_category->save();				
			else :
				$status = false;
				$errors = $validation->getMessageBag()->toArray();
			endif;

			return response()->json(['status' => $status, 'errors' => $errors]);
		endif;
	}



	public function edit(Request $request, ExpenseCategory $expense_category)
	{
		if($request->ajax()) :
			$status = true;
			$info = null;

			if(isset($expense_category) && isset($request->id)) :
				if($expense_category->id == $request->id) :
					$info = $expense_category->toArray();
					$info['position'] = $expense_category->prev_position_id;
					$info = (object)$info;
				else :
					$status = false;
				endif;
			else :
				$status = false;
			endif;

			return response()->json(['status' => $status, 'info' => $info]);
		endif;

		return redirect()->route('admin.administration-dropdown-expensecategory.index');
	}



	public function update(Request $request, ExpenseCategory $expense_category)
	{
		if($request->ajax()) :
			$status = true;
			$errors = null;
			$data = $request->all();

			if(isset($expense_category) && isset($request->id) && $expense_category->id == $request->id) :
				$validation = ExpenseCategory::validate($data);
				$picked_position_id = $request->position;

				if($validation->passes()) :					
					$position_val = ExpenseCategory::getTargetPositionVal($picked_position_id, $expense_category->id);

					$expense_category->name = $request->name;
					$expense_category->description = null_if_empty($request->description);
					$expense_category->position = $position_val;
					$expense_category->save();
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



	public function destroy(Request $request, ExpenseCategory $expense_category)
	{
		if($request->ajax()) :
			$status = true;
			$error_msg = null;

			if($expense_category->id != $request->id || !$expense_category->can_delete) :
				$status = false;
			endif;

			if($status == true) :
				$expense_category->delete();
			endif;	
			
			return response()->json(['status' => $status]);
		endif;	
	}
}