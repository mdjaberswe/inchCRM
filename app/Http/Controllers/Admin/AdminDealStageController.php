<?php

namespace App\Http\Controllers\Admin;

use App\Models\DealStage;
use Illuminate\Http\Request;
use App\Library\DatatablesManager;
use App\Http\Controllers\Admin\AdminBaseController;

class AdminDealStageController extends AdminBaseController
{
	public function __construct()
	{
		parent::__construct();

		$this->middleware('admin:custom_dropdowns.deal_stage.view', ['only' => ['index', 'dealstageData']]);
		$this->middleware('admin:custom_dropdowns.deal_stage.create', ['only' => ['store']]);
		$this->middleware('admin:custom_dropdowns.deal_stage.edit', ['only' => ['edit', 'update']]);
		$this->middleware('admin:custom_dropdowns.deal_stage.delete', ['only' => ['destroy']]);
	}



	public function index()
	{
		$page = ['title' => 'Deal Stage List', 'item' => 'Deal Stage', 'field' => 'deal_stages', 'view' => 'admin.dealstage', 'route' => 'admin.administration-dropdown-dealstage', 'plain_route' => 'admin.dealstage', 'permission' => 'custom_dropdowns.deal_stage', 'subnav' => 'custom-dropdown', 'modal_bulk_delete' => false, 'modal_size' => 'medium', 'save_and_new' => false];
		$table = ['thead' => [['NAME', 'style' => 'min-width: 200px'], 'CATEGORY', ['PROBABILITY', 'data_class' => 'center'], 'DESCRIPTION'], 'action' => DealStage::allowAction()];
		$table['json_columns'] = table_json_columns(['sequence' => ['className' => 'reorder'], 'name', 'category', 'probability', 'description', 'action'], DealStage::hideColumns());
		$reset_position = DealStage::resetPosition();

		return view('admin.dealstage.index', compact('page', 'table', 'reset_position'));
	}



	public function dealstageData(Request $request)
	{
		if($request->ajax()) :
			$deal_stages = DealStage::orderBy('position')->get(['id', 'position', 'name', 'fixed', 'category', 'probability', 'description']);
			return DatatablesManager::dealstageData($deal_stages, $request);
		endif;
	}



	public function store(Request $request)
	{
		if($request->ajax()) :
			$status = true;
			$errors = null;
			$data = $request->all();
			$validation = DealStage::validate($data);
			$picked_position_id = $request->position;

			if($validation->passes()) :				
				$position_val = DealStage::getTargetPositionVal($picked_position_id);

				$deal_stage = new DealStage;
				$deal_stage->name = $request->name;
				$deal_stage->description = null_if_empty($request->description);
				$deal_stage->position = $position_val;
				$deal_stage->probability = $request->probability;
				$deal_stage->category = $request->category;
				$deal_stage->save();				
			else :
				$status = false;
				$errors = $validation->getMessageBag()->toArray();
			endif;

			return response()->json(['status' => $status, 'errors' => $errors]);
		endif;
	}



	public function edit(Request $request, DealStage $deal_stage)
	{
		if($request->ajax()) :
			$status = true;
			$info = null;

			if(isset($deal_stage) && isset($request->id)) :
				if($deal_stage->id == $request->id) :
					$info = $deal_stage->toArray();
					$info['position'] = $deal_stage->prev_position_id;

					$info['freeze'] = [];

					if($deal_stage->fixed) :
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

		return redirect()->route('admin.administration-dropdown-dealstage.index');
	}



	public function update(Request $request, DealStage $deal_stage)
	{
		if($request->ajax()) :
			$status = true;
			$errors = null;
			$data = $request->all();

			if(isset($deal_stage) && isset($request->id) && $deal_stage->id == $request->id) :
				$validation = DealStage::validate($data, $deal_stage);
				$picked_position_id = $request->position;

				if($validation->passes()) :					
					$position_val = DealStage::getTargetPositionVal($picked_position_id, $deal_stage->id);

					$deal_stage->name = $request->name;
					$deal_stage->description = null_if_empty($request->description);
					$deal_stage->position = $position_val;
					$deal_stage->probability = $request->probability;

					if(!$deal_stage->fixed) :
						$deal_stage->category = $request->category;
					endif;

					$deal_stage->save();
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



	public function destroy(Request $request, DealStage $deal_stage)
	{
		if($request->ajax()) :
			$status = true;

			if($deal_stage->id != $request->id || $deal_stage->fixed) :
				$status = false;
			endif;

			if($status == true) :
				$default_stage_id = DealStage::whereCategory($deal_stage->category)->whereFixed(1)->first()->id;
				
				if($deal_stage->pipelines->count()) :
					foreach($deal_stage->pipelines as $pipeline) :
						$pipeline_stage = \DB::table('pipeline_stages')->where('deal_pipeline_id', $pipeline->id)->where('deal_stage_id', $deal_stage->id)->first();
						$pipeline_alter_stages = $pipeline->stages()->where('id', '!=', $deal_stage->id)->whereCategory($deal_stage->category);
					
						if($pipeline_alter_stages->count()) :
							$lower_stages = $pipeline->stages()->where('id', '!=', $deal_stage->id)->whereCategory($deal_stage->category)->where('pipeline_stages.position', '<', $pipeline_stage->position);
							
							if($lower_stages->count()) :
								$replace_stage_id = $lower_stages->orderBy('pipeline_stages.position', 'desc')->first()->id;
							else :
								$replace_stage_id = $pipeline_alter_stages->orderBy('pipeline_stages.position')->first()->id;
							endif;
						else :
							$pipeline->stages()->attach($default_stage_id);
							$replace_stage_id = $default_stage_id;
						endif;

						$deal_stage->deals()->where('deal_pipeline_id', $pipeline->id)->update(['deal_stage_id' => $replace_stage_id]);
					endforeach;	
				endif;

				$deal_stage->delete();
				\DB::table('pipeline_stages')->where('deal_stage_id', $request->id)->delete();
			endif;	
			
			return response()->json(['status' => $status]);
		endif;	
	}
}