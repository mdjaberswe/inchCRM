<?php

namespace App\Http\Controllers\Admin;

use App\Models\AccountType;
use Illuminate\Http\Request;
use App\Library\DatatablesManager;
use App\Http\Controllers\Admin\AdminBaseController;

class AdminAccountTypeController extends AdminBaseController
{
	public function __construct()
	{
		parent::__construct();

		$this->middleware('admin:custom_dropdowns.account_type.view', ['only' => ['index', 'accountTypeData']]);
		$this->middleware('admin:custom_dropdowns.account_type.create', ['only' => ['store']]);
		$this->middleware('admin:custom_dropdowns.account_type.edit', ['only' => ['edit', 'update']]);
		$this->middleware('admin:custom_dropdowns.account_type.delete', ['only' => ['destroy']]);
	}



	public function index()
	{
		$page = ['title' => 'Account Type List', 'item' => 'Account Type', 'field' => 'account_types', 'view' => 'admin.accounttype', 'route' => 'admin.administration-dropdown-accounttype', 'plain_route' => 'admin.accounttype', 'permission' => 'custom_dropdowns.account_type', 'subnav' => 'custom-dropdown', 'modal_bulk_delete' => false, 'modal_size' => 'medium', 'save_and_new' => false];
		$table = ['thead' => [['NAME', 'style' => 'min-width: 200px'], 'DESCRIPTION'], 'action' => AccountType::allowAction()];
		$table['json_columns'] = table_json_columns(['sequence' => ['className' => 'reorder'], 'name', 'description', 'action'], AccountType::hideColumns());
		$reset_position = AccountType::resetPosition();

		return view('admin.accounttype.index', compact('page', 'table', 'reset_position'));
	}



	public function accountTypeData(Request $request)
	{
		if($request->ajax()) :
			$account_types = AccountType::orderBy('position')->get(['id', 'position', 'name', 'description']);
			return DatatablesManager::accountTypeData($account_types, $request);
		endif;
	}



	public function store(Request $request)
	{
		if($request->ajax()) :
			$status = true;
			$errors = null;
			$data = $request->all();
			$validation = AccountType::validate($data);
			$picked_position_id = $request->position;

			if($validation->passes()) :				
				$position_val = AccountType::getTargetPositionVal($picked_position_id);

				$account_type = new AccountType;
				$account_type->name = $request->name;
				$account_type->description = null_if_empty($request->description);
				$account_type->position = $position_val;
				$account_type->save();				
			else :
				$status = false;
				$errors = $validation->getMessageBag()->toArray();
			endif;

			return response()->json(['status' => $status, 'errors' => $errors]);
		endif;
	}



	public function edit(Request $request, AccountType $account_type)
	{
		if($request->ajax()) :
			$status = true;
			$info = null;

			if(isset($account_type) && isset($request->id)) :
				if($account_type->id == $request->id) :
					$info = $account_type->toArray();
					$info['position'] = $account_type->prev_position_id;
					$info = (object)$info;
				else :
					$status = false;
				endif;
			else :
				$status = false;
			endif;

			return response()->json(['status' => $status, 'info' => $info]);
		endif;

		return redirect()->route('admin.administration-dropdown-accounttype.index');
	}



	public function update(Request $request, AccountType $account_type)
	{
		if($request->ajax()) :
			$status = true;
			$errors = null;
			$data = $request->all();

			if(isset($account_type) && isset($request->id) && $account_type->id == $request->id) :
				$validation = AccountType::validate($data);
				$picked_position_id = $request->position;

				if($validation->passes()) :					
					$position_val = AccountType::getTargetPositionVal($picked_position_id, $account_type->id);

					$account_type->name = $request->name;
					$account_type->description = null_if_empty($request->description);
					$account_type->position = $position_val;
					$account_type->save();
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



	public function destroy(Request $request, AccountType $account_type)
	{
		if($request->ajax()) :
			$status = true;

			if($account_type->id != $request->id) :
				$status = false;
			endif;

			if($status == true) :
				$account_type->accounts()->update(['account_type_id' => null]);
				$account_type->delete();
			endif;	
			
			return response()->json(['status' => $status]);
		endif;	
	}
}