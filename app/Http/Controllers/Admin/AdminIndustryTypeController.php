<?php

namespace App\Http\Controllers\Admin;

use App\Models\IndustryType;
use Illuminate\Http\Request;
use App\Library\DatatablesManager;
use App\Http\Controllers\Admin\AdminBaseController;

class AdminIndustryTypeController extends AdminBaseController
{
	public function __construct()
	{
		parent::__construct();

		$this->middleware('admin:custom_dropdowns.industry_type.view', ['only' => ['index', 'industryTypeData']]);
		$this->middleware('admin:custom_dropdowns.industry_type.create', ['only' => ['store']]);
		$this->middleware('admin:custom_dropdowns.industry_type.edit', ['only' => ['edit', 'update']]);
		$this->middleware('admin:custom_dropdowns.industry_type.delete', ['only' => ['destroy']]);
	}



	public function index()
	{
		$page = ['title' => 'Industry Type List', 'item' => 'Industry Type', 'field' => 'industry_types', 'view' => 'admin.industrytype', 'route' => 'admin.administration-dropdown-industrytype', 'plain_route' => 'admin.industrytype', 'permission' => 'custom_dropdowns.industry_type', 'subnav' => 'custom-dropdown', 'modal_bulk_delete' => false, 'modal_size' => 'medium', 'save_and_new' => false];
		$table = ['thead' => [['NAME', 'style' => 'min-width: 200px'], 'DESCRIPTION'], 'action' => IndustryType::allowAction()];
		$table['json_columns'] = table_json_columns(['sequence' => ['className' => 'reorder'], 'name', 'description', 'action'], IndustryType::hideColumns());
		$reset_position = IndustryType::resetPosition();

		return view('admin.industrytype.index', compact('page', 'table', 'reset_position'));
	}



	public function industryTypeData(Request $request)
	{
		if($request->ajax()) :
			$industry_types = IndustryType::orderBy('position')->get(['id', 'position', 'name', 'description']);
			return DatatablesManager::industryTypeData($industry_types, $request);
		endif;
	}



	public function store(Request $request)
	{
		if($request->ajax()) :
			$status = true;
			$errors = null;
			$data = $request->all();
			$validation = IndustryType::validate($data);
			$picked_position_id = $request->position;

			if($validation->passes()) :				
				$position_val = IndustryType::getTargetPositionVal($picked_position_id);

				$industry_type = new IndustryType;
				$industry_type->name = $request->name;
				$industry_type->description = null_if_empty($request->description);
				$industry_type->position = $position_val;
				$industry_type->save();				
			else :
				$status = false;
				$errors = $validation->getMessageBag()->toArray();
			endif;

			return response()->json(['status' => $status, 'errors' => $errors]);
		endif;
	}



	public function edit(Request $request, IndustryType $industry_type)
	{
		if($request->ajax()) :
			$status = true;
			$info = null;

			if(isset($industry_type) && isset($request->id)) :
				if($industry_type->id == $request->id) :
					$info = $industry_type->toArray();
					$info['position'] = $industry_type->prev_position_id;
					$info = (object)$info;
				else :
					$status = false;
				endif;
			else :
				$status = false;
			endif;

			return response()->json(['status' => $status, 'info' => $info]);
		endif;

		return redirect()->route('admin.administration-dropdown-industrytype.index');
	}



	public function update(Request $request, IndustryType $industry_type)
	{
		if($request->ajax()) :
			$status = true;
			$errors = null;
			$data = $request->all();

			if(isset($industry_type) && isset($request->id) && $industry_type->id == $request->id) :
				$validation = IndustryType::validate($data);
				$picked_position_id = $request->position;

				if($validation->passes()) :					
					$position_val = IndustryType::getTargetPositionVal($picked_position_id, $industry_type->id);

					$industry_type->name = $request->name;
					$industry_type->description = null_if_empty($request->description);
					$industry_type->position = $position_val;
					$industry_type->save();
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



	public function destroy(Request $request, IndustryType $industry_type)
	{
		if($request->ajax()) :
			$status = true;

			if($industry_type->id != $request->id) :
				$status = false;
			endif;

			if($status == true) :
				$industry_type->accounts()->update(['industry_type_id' => null]);
				$industry_type->delete();
			endif;	
			
			return response()->json(['status' => $status]);
		endif;	
	}
}