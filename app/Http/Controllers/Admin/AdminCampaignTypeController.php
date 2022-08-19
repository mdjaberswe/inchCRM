<?php

namespace App\Http\Controllers\Admin;

use App\Models\CampaignType;
use Illuminate\Http\Request;
use App\Library\DatatablesManager;
use App\Http\Controllers\Admin\AdminBaseController;

class AdminCampaignTypeController extends AdminBaseController
{
	public function __construct()
	{
		parent::__construct();

		$this->middleware('admin:custom_dropdowns.campaign_type.view', ['only' => ['index', 'campaignTypeData']]);
		$this->middleware('admin:custom_dropdowns.campaign_type.create', ['only' => ['store']]);
		$this->middleware('admin:custom_dropdowns.campaign_type.edit', ['only' => ['edit', 'update']]);
		$this->middleware('admin:custom_dropdowns.campaign_type.delete', ['only' => ['destroy']]);
	}



	public function index()
	{
		$page = ['title' => 'Campaign Type List', 'item' => 'Campaign Type', 'field' => 'campaign_types', 'view' => 'admin.campaigntype', 'route' => 'admin.administration-dropdown-campaigntype', 'plain_route' => 'admin.campaigntype', 'permission' => 'custom_dropdowns.campaign_type', 'subnav' => 'custom-dropdown', 'modal_bulk_delete' => false, 'modal_size' => 'medium', 'save_and_new' => false];
		$table = ['thead' => [['NAME', 'style' => 'min-width: 200px'], 'DESCRIPTION'], 'action' => CampaignType::allowAction()];
		$table['json_columns'] = table_json_columns(['sequence' => ['className' => 'reorder'], 'name', 'description', 'action'], CampaignType::hideColumns());
		$reset_position = CampaignType::resetPosition();

		return view('admin.campaigntype.index', compact('page', 'table', 'reset_position'));
	}



	public function campaignTypeData(Request $request)
	{
		if($request->ajax()) :
			$campaign_types = CampaignType::orderBy('position')->get(['id', 'position', 'name', 'description']);
			return DatatablesManager::campaignTypeData($campaign_types, $request);
		endif;
	}



	public function store(Request $request)
	{
		if($request->ajax()) :
			$status = true;
			$errors = null;
			$data = $request->all();
			$validation = CampaignType::validate($data);
			$picked_position_id = $request->position;

			if($validation->passes()) :				
				$position_val = CampaignType::getTargetPositionVal($picked_position_id);

				$campaign_type = new CampaignType;
				$campaign_type->name = $request->name;
				$campaign_type->description = null_if_empty($request->description);
				$campaign_type->position = $position_val;
				$campaign_type->save();				
			else :
				$status = false;
				$errors = $validation->getMessageBag()->toArray();
			endif;

			return response()->json(['status' => $status, 'errors' => $errors]);
		endif;
	}



	public function edit(Request $request, CampaignType $campaign_type)
	{
		if($request->ajax()) :
			$status = true;
			$info = null;

			if(isset($campaign_type) && isset($request->id)) :
				if($campaign_type->id == $request->id) :
					$info = $campaign_type->toArray();
					$info['position'] = $campaign_type->prev_position_id;
					$info = (object)$info;
				else :
					$status = false;
				endif;
			else :
				$status = false;
			endif;

			return response()->json(['status' => $status, 'info' => $info]);
		endif;

		return redirect()->route('admin.administration-dropdown-campaigntype.index');
	}



	public function update(Request $request, CampaignType $campaign_type)
	{
		if($request->ajax()) :
			$status = true;
			$errors = null;
			$data = $request->all();

			if(isset($campaign_type) && isset($request->id) && $campaign_type->id == $request->id) :
				$validation = CampaignType::validate($data);
				$picked_position_id = $request->position;

				if($validation->passes()) :					
					$position_val = CampaignType::getTargetPositionVal($picked_position_id, $campaign_type->id);

					$campaign_type->name = $request->name;
					$campaign_type->description = null_if_empty($request->description);
					$campaign_type->position = $position_val;
					$campaign_type->save();
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



	public function destroy(Request $request, CampaignType $campaign_type)
	{
		if($request->ajax()) :
			$status = true;

			if($campaign_type->id != $request->id) :
				$status = false;
			endif;

			if($status == true) :
				$campaign_type->campaigns()->update(['campaign_type' => null]);
				$campaign_type->delete();
			endif;	
			
			return response()->json(['status' => $status]);
		endif;	
	}
}