<?php

namespace App\Http\Controllers\Admin;

use App\Models\DealType;
use Illuminate\Http\Request;
use App\Library\DatatablesManager;
use App\Http\Controllers\Admin\AdminBaseController;

class AdminDealTypeController extends AdminBaseController
{
	public function __construct()
	{
		parent::__construct();

		$this->middleware('admin:custom_dropdowns.deal_type.view', ['only' => ['index', 'dealtypeData']]);
		$this->middleware('admin:custom_dropdowns.deal_type.create', ['only' => ['store']]);
		$this->middleware('admin:custom_dropdowns.deal_type.edit', ['only' => ['edit', 'update']]);
		$this->middleware('admin:custom_dropdowns.deal_type.delete', ['only' => ['destroy']]);
	}



	public function index()
	{
		$page = ['title' => 'Deal Type List', 'item' => 'Deal Type', 'field' => 'deal_types', 'view' => 'admin.dealtype', 'route' => 'admin.administration-dropdown-dealtype', 'plain_route' => 'admin.dealtype', 'permission' => 'custom_dropdowns.deal_type', 'subnav' => 'custom-dropdown', 'modal_bulk_delete' => false, 'modal_size' => 'medium', 'save_and_new' => false];
		$table = ['thead' => [['NAME', 'style' => 'min-width: 200px'], 'DESCRIPTION'], 'action' => DealType::allowAction()];
		$table['json_columns'] = table_json_columns(['sequence' => ['className' => 'reorder'], 'name', 'description', 'action'], DealType::hideColumns());
		$reset_position = DealType::resetPosition();

		return view('admin.dealtype.index', compact('page', 'table', 'reset_position'));
	}



	public function dealtypeData(Request $request)
	{
		if($request->ajax()) :
			$deal_types = DealType::orderBy('position')->get(['id', 'position', 'name', 'description']);
			return DatatablesManager::dealtypeData($deal_types, $request);
		endif;
	}



	public function store(Request $request)
	{
		if($request->ajax()) :
			$status = true;
			$errors = null;
			$data = $request->all();
			$validation = DealType::validate($data);
			$picked_position_id = $request->position;

			if($validation->passes()) :				
				$position_val = DealType::getTargetPositionVal($picked_position_id);

				$deal_type = new DealType;
				$deal_type->name = $request->name;
				$deal_type->description = null_if_empty($request->description);
				$deal_type->position = $position_val;
				$deal_type->save();				
			else :
				$status = false;
				$errors = $validation->getMessageBag()->toArray();
			endif;

			return response()->json(['status' => $status, 'errors' => $errors]);
		endif;
	}



	public function edit(Request $request, DealType $deal_type)
	{
		if($request->ajax()) :
			$status = true;
			$info = null;

			if(isset($deal_type) && isset($request->id)) :
				if($deal_type->id == $request->id) :
					$info = $deal_type->toArray();
					$info['position'] = $deal_type->prev_position_id;
					$info = (object)$info;
				else :
					$status = false;
				endif;
			else :
				$status = false;
			endif;

			return response()->json(['status' => $status, 'info' => $info]);
		endif;

		return redirect()->route('admin.administration-dropdown-dealtype.index');
	}



	public function update(Request $request, DealType $deal_type)
	{
		if($request->ajax()) :
			$status = true;
			$errors = null;
			$data = $request->all();

			if(isset($deal_type) && isset($request->id) && $deal_type->id == $request->id) :
				$validation = DealType::validate($data);
				$picked_position_id = $request->position;

				if($validation->passes()) :					
					$position_val = DealType::getTargetPositionVal($picked_position_id, $deal_type->id);

					$deal_type->name = $request->name;
					$deal_type->description = null_if_empty($request->description);
					$deal_type->position = $position_val;
					$deal_type->save();
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



	public function destroy(Request $request, DealType $deal_type)
	{
		if($request->ajax()) :
			$status = true;

			if($deal_type->id != $request->id) :
				$status = false;
			endif;

			if($status == true) :
				$deal_type->deals()->update(['deal_type_id' => null]);
				$deal_type->delete();
			endif;	
			
			return response()->json(['status' => $status]);
		endif;	
	}
}