<?php

namespace App\Http\Controllers\Admin;

use App\Models\Deal;
use App\Models\DealStage;
use App\Models\DealPipeline;
use Illuminate\Http\Request;
use App\Library\DatatablesManager;
use App\Http\Controllers\Admin\AdminBaseController;

class AdminDealPipelineController extends AdminBaseController
{
	public function __construct()
	{
		parent::__construct();

		$this->middleware('admin:custom_dropdowns.deal_pipeline.view', ['only' => ['index', 'pipelineData']]);
		$this->middleware('admin:custom_dropdowns.deal_pipeline.create', ['only' => ['store']]);
		$this->middleware('admin:custom_dropdowns.deal_pipeline.edit', ['only' => ['edit', 'update']]);
		$this->middleware('admin:custom_dropdowns.deal_pipeline.delete', ['only' => ['destroy']]);
	}



	public function index()
	{
		$page = ['title' => 'Deal Pipeline List', 'item' => 'Deal Pipeline', 'field' => 'deal_pipelines', 'view' => 'admin.dealpipeline', 'route' => 'admin.administration-dropdown-dealpipeline', 'plain_route' => 'admin.dealpipeline', 'script' => true, 'permission' => 'custom_dropdowns.deal_pipeline', 'subnav' => 'custom-dropdown', 'modal_bulk_delete' => false, 'modal_size' => 'medium', 'save_and_new' => false];
		$table = ['thead' => [['PIPELINE NAME', 'style' => 'min-width: 200px'], ['STAGES', 'data_class' => 'center', 'style' => 'max-width: 120px'], ['ROTTING PERIOD', 'data_class' => 'center', 'style' => 'max-width: 120px']], 'list_order' => 'asc', 'action' => DealPipeline::allowAction()];
		$table['json_columns'] = table_json_columns(['sequence' => ['className' => 'reorder'], 'name', 'total_stages', 'period', 'action'], DealPipeline::hideColumns());

		return view('admin.dealpipeline.index', compact('page', 'table'));
	}



	public function pipelineData(Request $request)
	{
		if($request->ajax()) :
			$deal_pipelines = DealPipeline::orderBy('position')->get();
			return DatatablesManager::pipelineData($deal_pipelines, $request);
		endif;
	}



	public function pipelineStageData(Request $request, $pipeline_id = null, $stage_ids = null)
	{
		if($request->ajax()) :
			if(is_null($stage_ids)) :
				$dealpipeline = is_null($pipeline_id) ? DealPipeline::whereDefault(1)->first() : DealPipeline::find($pipeline_id);
				$pipeline_stages = $dealpipeline->stages()->orderBy('pipeline_stages.position')->get();
			else :
				$order_ids = str_replace('_', ',', $stage_ids);
				$stage_ids = explode('_', $stage_ids);
				$pipeline_stages = DealStage::whereIn('id', $stage_ids)->orderByRaw(\DB::raw("FIELD(id, $order_ids)"))->get();
			endif;

			return DatatablesManager::pipelineStageData($pipeline_stages, $request);
		endif;
	}



	public function store(Request $request)
	{
		if($request->ajax()) :
			$status = true;
			$errors = null;
			$data = $request->all();
			$data['deal_stage'] = $request->positions;
			$validation = DealPipeline::validate($data);
			$open_count = DealStage::whereIn('id', $request->positions)->whereCategory('open')->count();
			$won_count = DealStage::whereIn('id', $request->positions)->whereCategory('closed_won')->count();
			$lost_count = DealStage::whereIn('id', $request->positions)->whereCategory('closed_lost')->count();
			$bottom_position = DealPipeline::getBottomPosition();

			if($validation->passes() && $open_count && $won_count && $lost_count) :				
				$deal_pipeline = new DealPipeline;
				$deal_pipeline->name = $request->name;
				$deal_pipeline->period = $request->period;
				$deal_pipeline->position = $bottom_position;
				$deal_pipeline->save();

				if(isset($request->default)) :
					DealPipeline::where('id', '!=', $deal_pipeline->id)->update(['default' => 0]);
					$deal_pipeline->update(['default' => 1]);
				endif;				

				$pipeline_stages = [];
				$forecast = is_array($request->forecast) ? $request->forecast : [0];
				$closed_stages = DealStage::onlyClosed()->pluck('id')->toArray();
				foreach($request->positions as $position => $deal_stage_id) :
					$stage_forecast = in_array($deal_stage_id, $closed_stages) ? true : in_array($deal_stage_id, $forecast);
					$pipeline_stages[] = ['deal_pipeline_id' => $deal_pipeline->id, 'deal_stage_id' => $deal_stage_id, 'position' => ($position + 1), 'forecast' => $stage_forecast];					
				endforeach;
				\DB::table('pipeline_stages')->insert($pipeline_stages);
			else :
				$status = false;
				$errors = $validation->getMessageBag()->toArray();

				if($open_count == 0) :
					$errors['deal_stage'][] = "<br>At least one 'Open' stage category is required.";
				endif;
				
				if($won_count == 0) :
					$errors['deal_stage'][] = "<br>At least one 'Closed Won' stage category is required.";
				endif;

				if($lost_count == 0) :
					$errors['deal_stage'][] = "<br>At least one 'Closed Lost' stage category is required.<br>";
				endif;
			endif;

			return response()->json(['status' => $status, 'errors' => $errors]);
		endif;
	}



	public function edit(Request $request, DealPipeline $deal_pipeline)
	{
		if($request->ajax()) :
			$status = true;
			$info = null;

			if(isset($deal_pipeline) && isset($request->id)) :
				if($deal_pipeline->id == $request->id) :
					$info = $deal_pipeline->toArray();
					$info = (object)$info;
				else :
					$status = false;
				endif;
			else :
				$status = false;
			endif;

			return response()->json(['status' => $status, 'info' => $info]);
		endif;

		return redirect()->route('admin.administration-dropdown-dealpipeline.index');
	}



	public function update(Request $request, DealPipeline $deal_pipeline)
	{
		if($request->ajax()) :
			$status = true;
			$errors = null;
			$data = $request->all();

			if(isset($deal_pipeline) && isset($request->id) && $deal_pipeline->id == $request->id) :
				$data['deal_stage'] = $request->positions;
				$validation = DealPipeline::validate($data);
				$open_count = DealStage::whereIn('id', $request->positions)->whereCategory('open')->count();
				$won_count = DealStage::whereIn('id', $request->positions)->whereCategory('closed_won')->count();
				$lost_count = DealStage::whereIn('id', $request->positions)->whereCategory('closed_lost')->count();

				if($validation->passes() && $open_count && $won_count && $lost_count) :					
					$deal_pipeline->name = $request->name;
					$deal_pipeline->period = $request->period;
					$deal_pipeline->update();

					if(isset($request->default)) :
						DealPipeline::where('id', '!=', $deal_pipeline->id)->update(['default' => 0]);
						$deal_pipeline->update(['default' => 1]);
					endif;				

					$pipeline_stages = [];					
					$forecast = is_array($request->forecast) ? $request->forecast : [0];
					$closed_stages = DealStage::onlyClosed()->pluck('id')->toArray();
					foreach($request->positions as $position => $deal_stage_id) :
						$stage_forecast = in_array($deal_stage_id, $closed_stages) ? true : in_array($deal_stage_id, $forecast);
						$pipeline_stages[$deal_stage_id] = ['position' => ($position + 1), 'forecast' => $stage_forecast];
					endforeach;

					$deleted_stages = array_diff($deal_pipeline->stages()->pluck('id')->toArray(), $request->positions);
					$deleted_stages = $deal_pipeline->stages()->whereIn('id', $deleted_stages)->get(['id', 'category']);

					$deal_pipeline->stages()->sync($pipeline_stages);

					if($deleted_stages->count()) :
						foreach($deleted_stages as $deleted_stage) :
							$lower_stages = $deal_pipeline->stages()->whereCategory($deleted_stage->category)->where('pipeline_stages.position', '<', $deleted_stage->pivot->position);
							
							if($lower_stages->count()) :
								$replace_stage_id = $lower_stages->orderBy('pipeline_stages.position', 'desc')->first()->id;
							else :
								$replace_stage_id = $deal_pipeline->stages()->whereCategory($deleted_stage->category)->orderBy('pipeline_stages.position')->first()->id;
							endif;

							Deal::where('deal_pipeline_id', $deal_pipeline->id)->where('deal_stage_id', $deleted_stage->id)->update(['deal_stage_id' => $replace_stage_id]);
						endforeach;	
					endif;
				else :
					$status = false;
					$errors = $validation->getMessageBag()->toArray();

					if($open_count == 0) :
						$errors['deal_stage'][] = "At least one 'Open' stage category is required. <br>";
					endif;
					
					if($won_count == 0) :
						$errors['deal_stage'][] = "At least one 'Closed Won' stage category is required. <br>";
					endif;

					if($lost_count == 0) :
						$errors['deal_stage'][] = "At least one 'Closed Lost' stage category is required. <br>";
					endif;
				endif;
			else :
				$status = false;
			endif;

			return response()->json(['status' => $status, 'errors' => $errors, 'saveId' => $request->id]);
		endif;
	}	



	public function destroy(Request $request, DealPipeline $deal_pipeline)
	{
		if($request->ajax()) :
			$status = true;

			if($deal_pipeline->id != $request->id || $deal_pipeline->default || !$deal_pipeline->can_delete) :
				$status = false;
			endif;

			if($status == true) :
				$deal_pipeline->delete();
			endif;	
			
			return response()->json(['status' => $status]);
		endif;	
	}



	public function pipelineStageDropdown(Request $request, $pipeline_id = null)
	{
		if($request->ajax()) :
			$status = false;
			$option_html = '';
			$topval = null;
			$pipeline = is_null($pipeline_id) ? DealPipeline::default()->first() : DealPipeline::find($pipeline_id);

			if(isset($pipeline)) :
				$pipeline_stages = $pipeline->stages()->orderBy('pipeline_stages.position')->get(['id', 'name', 'probability']);
				foreach($pipeline_stages as $stage) :
					$option_html .= "<option value='$stage->id' relatedval='$stage->probability'>$stage->name</option>";
				endforeach;
				$topval = $pipeline_stages->first()->id;
				$status = true;	
			endif;	

			return response()->json(['status' => $status, 'optionHtml' => $option_html, 'topval' => $topval]);
		endif;	
	}
}