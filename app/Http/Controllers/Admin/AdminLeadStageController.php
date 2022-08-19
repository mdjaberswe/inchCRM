<?php

namespace App\Http\Controllers\Admin;

use App\Models\LeadStage;
use Illuminate\Http\Request;
use App\Library\DatatablesManager;
use App\Http\Controllers\Admin\AdminBaseController;

class AdminLeadStageController extends AdminBaseController
{
	public function __construct()
	{
		parent::__construct();

		$this->middleware('admin:custom_dropdowns.lead_stage.view', ['only' => ['index', 'leadStageData']]);
		$this->middleware('admin:custom_dropdowns.lead_stage.create', ['only' => ['store']]);
		$this->middleware('admin:custom_dropdowns.lead_stage.edit', ['only' => ['edit', 'update']]);
		$this->middleware('admin:custom_dropdowns.lead_stage.delete', ['only' => ['destroy']]);
	}



	public function index()
	{
		$page = ['title' => 'Lead Stage List', 'item' => 'Lead Stage', 'field' => 'lead_stages', 'view' => 'admin.leadstage', 'route' => 'admin.administration-dropdown-leadstage', 'plain_route' => 'admin.leadstage', 'permission' => 'custom_dropdowns.lead_stage', 'subnav' => 'custom-dropdown', 'modal_bulk_delete' => false, 'modal_size' => 'medium', 'save_and_new' => false];
		$table = ['thead' => [['NAME', 'style' => 'min-width: 200px'], 'CATEGORY', 'DESCRIPTION'], 'action' => LeadStage::allowAction()];
		$table['json_columns'] = table_json_columns(['sequence' => ['className' => 'reorder'], 'name', 'category', 'description', 'action'], LeadStage::hideColumns());
		$reset_position = LeadStage::resetPosition();

		return view('admin.leadstage.index', compact('page', 'table', 'reset_position'));
	}



	public function leadStageData(Request $request)
	{
		if($request->ajax()) :
			$lead_stages = LeadStage::orderBy('position')->get(['id', 'position', 'name', 'category', 'fixed', 'description']);
			return DatatablesManager::leadStageData($lead_stages, $request);
		endif;
	}



	public function store(Request $request)
	{
		if($request->ajax()) :
			$status = true;
			$errors = null;
			$data = $request->all();
			$validation = LeadStage::validate($data);
			$picked_position_id = $request->position;

			if($validation->passes()) :				
				$position_val = LeadStage::getTargetPositionVal($picked_position_id);

				$lead_stage = new LeadStage;
				$lead_stage->name = $request->name;
				$lead_stage->description = null_if_empty($request->description);
				$lead_stage->position = $position_val;
				$lead_stage->category = $request->category;
				$lead_stage->save();				
			else :
				$status = false;
				$errors = $validation->getMessageBag()->toArray();
			endif;

			return response()->json(['status' => $status, 'errors' => $errors]);
		endif;

		return redirect()->route('admin.administration-dropdown-leadstage.index');
	}



	public function edit(Request $request, LeadStage $lead_stage)
	{
		if($request->ajax()) :
			$status = true;
			$info = null;

			if(isset($lead_stage) && isset($request->id)) :
				if($lead_stage->id == $request->id) :
					$info = $lead_stage->toArray();
					$info['position'] = $lead_stage->prev_position_id;

					$info['freeze'] = [];

					if($lead_stage->fixed) :
						$info['freeze'][] = 'category';
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

		return redirect()->route('admin.administration-dropdown-leadstage.index');
	}



	public function update(Request $request, LeadStage $lead_stage)
	{
		if($request->ajax()) :
			$status = true;
			$errors = null;
			$data = $request->all();

			if(isset($lead_stage) && isset($request->id) && $lead_stage->id == $request->id) :
				$validation = LeadStage::validate($data, $lead_stage);
				$picked_position_id = $request->position;

				if($validation->passes()) :					
					$position_val = LeadStage::getTargetPositionVal($picked_position_id, $lead_stage->id);

					$lead_stage->name = $request->name;
					$lead_stage->description = null_if_empty($request->description);
					$lead_stage->position = $position_val;

					if(!$lead_stage->fixed) :
						$lead_stage->category = $request->category;
					endif;
						
					$lead_stage->save();
				else :
					$status = false;
					$errors = $validation->getMessageBag()->toArray();
				endif;
			else :
				$status = false;
			endif;

			return response()->json(['status' => $status, 'errors' => $errors, 'saveId' => $request->id]);
		endif;

		return redirect()->route('admin.administration-dropdown-leadstage.index');
	}	



	public function destroy(Request $request, LeadStage $lead_stage)
	{
		if($request->ajax()) :
			$status = true;

			if($lead_stage->id != $request->id || $lead_stage->fixed) :
				$status = false;
			endif;

			if($status == true) :
				$lower_stages = LeadStage::whereCategory($lead_stage->category)->where('id', '!=', $lead_stage->id)->where('position', '<', $lead_stage->position);
				
				if($lower_stages->count()) :
					$replace_stage_id = $lower_stages->latest('position')->first()->id;
				else :
					$replace_stage_id = LeadStage::whereCategory($lead_stage->category)->where('id', '!=', $lead_stage->id)->orderBy('position')->first()->id;
				endif;

				$lead_stage->leads()->update(['lead_stage_id' => $replace_stage_id]);
				$lead_stage->delete();
			endif;	
			
			return response()->json(['status' => $status]);
		endif;	
	}
}