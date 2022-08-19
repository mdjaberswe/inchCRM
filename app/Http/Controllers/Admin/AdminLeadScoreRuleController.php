<?php

namespace App\Http\Controllers\Admin;

use App\Models\Setting;
use App\Models\LeadScore;
use App\Models\LeadScoreRule;
use Illuminate\Http\Request;
use App\Library\DatatablesManager;
use App\Http\Controllers\Admin\AdminBaseController;

class AdminLeadScoreRuleController extends AdminBaseController
{
	public function __construct()
	{
		parent::__construct();

		$this->middleware('admin:settings.lead_scoring_rule.view', ['only' => ['index', 'ruleData']]);
		$this->middleware('admin:settings.lead_scoring_rule.create', ['only' => ['store']]);
		$this->middleware('admin:settings.lead_scoring_rule.edit', ['only' => ['edit', 'update', 'classifyLeadScore', 'postClassifyLeadScore']]);
		$this->middleware('admin:settings.lead_scoring_rule.delete', ['only' => ['destroy']]);
	}



	public function index()
	{
		$page = ['title' => 'Lead Scoring Rules', 'item' => 'Rule', 'list_title' => 'Lead Scoring Rules', 'field' => 'lead_score_rules', 'view' => 'admin.setting.leadscore', 'route' => 'admin.administration-setting-lead-scoring-rule', 'permission' => 'settings.currency', 'subnav' => 'setting', 'modal_bulk_delete' => false, 'modal_footer_delete' => true, 'page_length' => 100, 'modal_size' => 'medium', 'save_and_new' => false, 'script' => true];
		$table = ['thead' => [['RULE DESCRIPTION', 'style' => 'min-width: 350px', 'orderable' => false], ['SCORE', 'data_class' => 'center', 'style' => 'min-width: 80px; max-width: 80px']], 'checkbox' => false, 'action' => LeadScoreRule::allowAction(), 'class' => 'bg-none v-space-td'];
		$table['json_columns'] = table_json_columns(['rule', 'score', 'action'], LeadScoreRule::hideColumns());

		return view('admin.setting.leadscore.index', compact('page', 'table'));
	}



	public function ruleData(Request $request)
	{
		if($request->ajax()) :
			$scores = LeadScore::latest('id')->get(['id', 'score']);
			return DatatablesManager::leadScoreRuleData($scores, $request);
		endif;
	}



	public function store(Request $request)
	{
		if($request->ajax()) :
			$status = true;
			$errors = null;
			$data = $request->all();
			$data_check = LeadScoreRule::formDataCheck($data);

			if($data_check['validation']->passes()) :	
				$lead_score_id = $request->lead_score_id;

				if(!$request->lead_score_id)	:
					$lead_score = new LeadScore;
					$lead_score->score = $request->scoring_type ? $request->score : -$request->score;
					$lead_score->save();

					$lead_score_id = $lead_score->id;
				endif;	

				$lead_score_rule = new LeadScoreRule;
				$lead_score_rule->lead_score_id = $lead_score_id;
				$lead_score_rule->related_to = $request->related_to;

				if($request->related_to == 'email_activity') :
					$lead_score_rule->attribute = $request->email_activity;
					$lead_score_rule->condition = $request->email_condition;
					$lead_score_rule->value = $request->subject;
					$lead_score_rule->description = LeadScoreRule::descriptionMaker($request);
				endif;	

				if($request->related_to == 'lead_property') :
					$condition_field = $data_check['condition'];
					$value_field = $data_check['value'];		
								
					$lead_score_rule->attribute = $request->lead_property;
					$lead_score_rule->condition = $request->$condition_field;
					$lead_score_rule->value = json_if_array($request->$value_field);
					$lead_score_rule->description = LeadScoreRule::descriptionMaker($request, $request->lead_property, $request->$condition_field, $request->$value_field);		
				endif;	

				$lead_score_rule->save();		
			else :
				$status = false;
				$errors = $data_check['validation']->getMessageBag()->toArray();
			endif;

			return response()->json(['status' => $status, 'errors' => $errors]);
		endif;
	}



	public function edit(Request $request, LeadScoreRule $lead_score_rule)
	{
		if($request->ajax()) :
			$status = true;
			$spec_show = false;
			$html = null;
			$info = null;

			if(isset($lead_score_rule) && isset($request->id)) :
				if($lead_score_rule->id == $request->id) :
					$info = $lead_score_rule->toArray();
					$default_data = get_data_from_attribute($request->default);

					if(is_array($default_data) && array_key_exists('score_only', $default_data) && $default_data['score_only']) :
						$info['hide'] = ['related_to'];
						$info['score_only'] = 1;
						$info['score'] = $lead_score_rule->score_val;
						$info['scoring_type'] = $lead_score_rule->scoring_type;
					else :
						$info['show'] = [];
						$info['hide'] = ['scoring_type', 'score'];

						if($lead_score_rule->related_to == 'email_activity') :
							$info['subject'] = $lead_score_rule->value;
							$info['email_activity'] = $lead_score_rule->attribute;
							$info['email_condition'] = $lead_score_rule->condition;
							$info['show'] = ['related_to', 'subject', 'email_activity', 'email_condition'];
						endif;

						if($lead_score_rule->related_to == 'lead_property') :
							$field_name = $lead_score_rule->lead_form_field;
							$condition_field = $field_name['condition'];						
							$info['lead_property'] = $lead_score_rule->attribute;
							$info[$condition_field] = $lead_score_rule->condition;						
							$info['show'] = ['related_to', 'lead_property', $condition_field];

							if(!in_array($lead_score_rule->condition, ['empty', 'not_empty'])) :
								$value_field = $field_name['value'];
								$info[$value_field] = decode_if_json($lead_score_rule->value);
								array_push($info['show'], $value_field);
							endif;	
						endif;

						$spec_show = true;
						$info['modal_footer_delete'] = ['action' => route('admin.administration-setting-lead-scoring-rule.destroy', $lead_score_rule->id), 'id' => $lead_score_rule->id];
					endif;

					$info = (object)$info;

					if(isset($request->html)) :
						$html = view('admin.setting.leadscore.partials.form', ['form' => 'edit'])->render();
					endif;	
				else :
					$status = false;
				endif;
			else :
				$status = false;
			endif;

			return response()->json(['status' => $status, 'info' => $info, 'html' => $html, 'specShow' => $spec_show]);
		endif;

		return redirect()->route('admin.administration-setting-lead-scoring-rule.index');
	}



	public function update(Request $request, LeadScoreRule $lead_score_rule)
	{
		if($request->ajax()) :
			$status = true;
			$errors = null;
			$data = $request->all();

			if(isset($lead_score_rule) && isset($request->id) && $lead_score_rule->id == $request->id && isset($request->lead_score_id) && $lead_score_rule->lead_score_id == $request->lead_score_id) :
				$data_check = LeadScoreRule::formDataCheck($data);
				if($data_check['validation']->passes()) :	
					if($request->score_only == 1) :
						$lead_score = $lead_score_rule->score;
						$lead_score->score =  $request->scoring_type ? $request->score : -$request->score;
						$lead_score->update();
					else :	
						$lead_score_rule->related_to = $request->related_to;

						if($request->related_to == 'email_activity') :
							$lead_score_rule->attribute = $request->email_activity;
							$lead_score_rule->condition = $request->email_condition;
							$lead_score_rule->value = $request->subject;
							$lead_score_rule->description = LeadScoreRule::descriptionMaker($request);
						endif;	

						if($request->related_to == 'lead_property') :
							$condition_field = $data_check['condition'];
							$value_field = $data_check['value'];		
										
							$lead_score_rule->attribute = $request->lead_property;
							$lead_score_rule->condition = $request->$condition_field;
							$lead_score_rule->value = json_if_array($request->$value_field);
							$lead_score_rule->description = LeadScoreRule::descriptionMaker($request, $request->lead_property, $request->$condition_field, $request->$value_field);		
						endif;	

						$lead_score_rule->update();	
					endif;		
				else :
					$status = false;
					$errors = $data_check['validation']->getMessageBag()->toArray();
				endif;
			endif;	

			return response()->json(['status' => $status, 'errors' => $errors, 'saveId' => $request->id]);
		endif;
	}	



	public function classifyLeadScore(Request $request)
	{
		if($request->ajax()) :
			$status = true;
			$info = null;

			if(isset($request->classify) && $request->classify == true) :
				$info = [];
				$info['range_start'] = config('setting.warm_lead_low');
				$info['range_end'] = config('setting.warm_lead_up');
				$info['cold_lead'] = config('setting.cold_lead_label');
				$info['warm_lead'] = config('setting.warm_lead_label');
				$info['hot_lead'] = config('setting.hot_lead_label');

				$info['realtime'] = [];
				$info['realtime']['cold-range'] = config('setting.cold_lead_low') . '-' . config('setting.cold_lead_up');
				$info['realtime']['warm-range'] = config('setting.warm_lead_low') . '-' . config('setting.warm_lead_up');
				$info['realtime']['hot-range'] = config('setting.hot_lead_low') . '-' . config('setting.hot_lead_up');

				$info = (object)$info;
			else :
				$status = false;
			endif;

			return response()->json(['status' => $status, 'info' => $info]);
		endif;
	}



	public function postClassifyLeadScore(Request $request)
	{
		if($request->ajax()) :
			$status = true;
			$errors = null;
			$data = $request->all();
			$validation = LeadScoreRule::classifyScoreValidate($data);

			if($validation->passes()) :	
				$save_data = ['cold_lead_label' => $request->cold_lead,
							  'warm_lead_label'	=> $request->warm_lead,
							  'hot_lead_label'	=> $request->hot_lead,
							  'cold_lead_low'	=> 0,
							  'cold_lead_up'	=> $request->range_start - 1,
							  'warm_lead_low'	=> $request->range_start,
							  'warm_lead_up'	=> $request->range_end,
							  'hot_lead_low'	=> $request->range_end + 1,
							  'hot_lead_up'		=> 99];	

				Setting::mergeSave($save_data);			  	
			else :
				$status = false;
				$errors = $validation->getMessageBag()->toArray();
			endif;

			return response()->json(['status' => $status, 'errors' => $errors]);
		endif;
	}



	public function destroy(Request $request, $id)
	{
		if($request->ajax()) :
			$status = false;

			if(isset($request->delete_all) && $request->delete_all == true) :
				$lead_score = LeadScore::find($id);
				if(isset($lead_score) && $lead_score->id == $request->id) :
					$status = true;
					$lead_score->rules()->delete();
					$lead_score->delete();
				endif;	
			else :	
				$lead_score_rule = LeadScoreRule::find($id);
				if(isset($lead_score_rule) && $lead_score_rule->id == $request->id) :
					$status = true;
					$lead_score = $lead_score_rule->score;
					if($lead_score->rules()->count() == 1) :
						$lead_score->delete();
					endif;
						
					$lead_score_rule->delete();
				endif;	
			endif;

			return response()->json(['status' => $status]);
		endif;	
	}
}