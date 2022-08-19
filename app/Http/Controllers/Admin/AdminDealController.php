<?php

namespace App\Http\Controllers\Admin;

use App\Models\Deal;
use App\Models\User;
use App\Models\Account;
use App\Models\Contact;
use App\Models\DealStage;
use App\Models\DealPipeline;
use App\Jobs\SaveAllowedStaff;
use Illuminate\Http\Request;
use App\Library\DatatablesManager;
use App\Http\Controllers\Admin\AdminBaseController;

class AdminDealController extends AdminBaseController
{
	public function __construct()
	{
		parent::__construct();

		$this->middleware('admin:deal.view', ['only' => ['index', 'dealData', 'show']]);
		$this->middleware('admin:deal.create', ['only' => ['store']]);
		$this->middleware('admin:deal.edit', ['only' => ['edit', 'update']]);
		$this->middleware('admin:deal.delete', ['only' => ['destroy', 'bulkDestroy']]);
	}



	public function index()
	{
		$page = ['title' => 'Deals List', 'item' => 'Deal', 'field' => 'deals', 'view' => 'admin.deal', 'route' => 'admin.deal', 'script' => true, 'permission' => 'deal', 'import' => permit('import.deal'), 'bulk' => 'email,sms,update', 'mass_update_permit' => permit('mass_update.deal'), 'mass_del_permit' => permit('mass_delete.deal')];
		$table = Deal::getTableFormat();
		return view('admin.deal.index', compact('page', 'table'));
	}



	public function dealData(Request $request)
	{
		if($request->ajax()) :
			return Deal::getTableData($request);
		endif;
	}



	public function stageHistory(Request $request, Deal $deal)
	{
		if($request->ajax()) :
			return Deal::getStageHistoryData($request, $deal);
		endif;
	}



	public function indexKanban(Request $request)
	{
		$page = ['title' => 'Deals Kanban', 'item' => 'Deal', 'item_title' => Deal::getKanbanBreadcrumb(), 'view' => 'admin.deal', 'route' => 'admin.deal', 'permission' => 'deal', 'script' => true, 'import' => permit('import.deal'), 'modal_edit' => true, 'modal_delete' => true, 'modal_bulk_update' => false, 'modal_bulk_delete' => false];
		return view('admin.deal.kanban', compact('page'));
	}



	public function pipelineKanbanView(Request $request)
	{
		if($request->ajax()) :
			$status = true;
			$html = null;
			$realtime = [];
			$errors = null;
			$rules = ['deal_pipeline_id' => 'required|exists:deal_pipelines,id,deleted_at,NULL'];
			$validation = \Validator::make($request->all(), $rules);

			if($validation->passes()) :
				\Session::put('deal_pipeline', $request->deal_pipeline_id);
				$html = Deal::getKanbanHtml();
				$total_info = Deal::getTotalInfo();
				$realtime['total_deal'] = $total_info['total_deal'];
				$realtime['total_amount'] = $total_info['total_amount_html'];
				$realtime['revenue_forecast'] = $total_info['total_forecast_html'];
			else :
				$status = false;
				$errors = $validation->getMessageBag()->toArray();
			endif;

			return response()->json(['status' => $status, 'html' => $html, 'realtime' => $realtime, 'errors' => $errors]);
		endif;	
	}



	public function report(Request $request)
	{

	}



	public function store(Request $request)
	{
		if($request->ajax()) :
			$status = true;
			$errors = null;
			$kanban = [];
			$kanban_header = null;
			$kanban_count = [];
			$kanban_add_status = true;
			$realtime = [];
			$notification = null;
			$data = $request->all();
			$validation = Deal::validate($data);

			if($validation->passes()) :
				$position = Deal::getTargetPositionVal(-1);

				$deal = new Deal;
				$deal->name = $request->name;
				$deal->amount = $request->amount;
				$deal->currency_id = $request->currency_id;
				$deal->closing_date = null_if_empty($request->closing_date);
				$deal->deal_pipeline_id = $request->deal_pipeline_id;
				$deal->deal_stage_id = $request->deal_stage_id;
				$deal->probability = not_null_empty($request->probability) ? $request->probability : DealStage::find($request->deal_stage_id)->probability;
				$deal->deal_owner = $request->deal_owner;
				$deal->account_id = $request->account_id;
				$deal->contact_id = null_if_empty($request->contact_id);
				$deal->deal_type_id = null_if_empty($request->deal_type_id);
				$deal->source_id = null_if_empty($request->source_id);
				$deal->campaign_id = null_if_empty($request->campaign_id);
				$deal->description = null_if_empty($request->description);
				$deal->access = $request->access;
				$deal->position = $position;
				$deal->save();

				if(not_null_empty($request->contact_id)) :
					$deal->participants()->attach($request->contact_id);
				endif;	

				if($request->access == 'private') :
					dispatch(new SaveAllowedStaff($request->staffs, 'deal', $deal->id, $request->can_write, $request->can_delete));
				endif;

				$kanban_add_status = (DealPipeline::getCurrentPipeline()->id == $deal->deal_pipeline_id);
				$kanban[$deal->kanban_stage_key][] = $deal->kanban_card_html;
				$kanban_header = Deal::getKanbanStageHeaderInfo();
				$kanban_count = $kanban_header['count'];

				$total_info = Deal::getTotalInfo();
				$realtime['total_deal'] = $total_info['total_deal'];
				$realtime['total_amount'] = $total_info['total_amount_html'];
				$realtime['revenue_forecast'] = $total_info['total_forecast_html'];

				$notification = notification_log('deal_created', 'deal', $deal->id, 'staff', $request->deal_owner);
			else :
				$status = false;
				$errors = $validation->getMessageBag()->toArray();
			endif;

			return response()->json(['status' => $status, 'kanban' => $kanban, 'kanbanCount' => $kanban_count, 'kanbanHeader' => $kanban_header, 'kanbanAddStatus' => $kanban_add_status, 'realtime' => $realtime, 'errors' => $errors, 'notification' => $notification]);
		endif;
	}



	public function show(Request $request, Deal $deal, $infotype = null)
	{
		$page = ['title' => 'Deal: ' . $deal->name, 'item_title' => breadcrumbs_render("admin.deal.index:Deals|<span data-realtime='name'>" . $deal->name . "</span>"), 'item' => 'Deal', 'view' => 'admin.deal', 'tabs' => ['list' => Deal::informationTypes(), 'default' => Deal::defaultInfoType($infotype), 'item_id' => $deal->id, 'url' => 'tab/deal']];
		return view('admin.deal.show', compact('page', 'deal'));
	}



	public function edit(Request $request, Deal $deal)
	{
		if($request->ajax()) :
			$status = true;
			$info = null;
			$html = null;

			if(isset($deal) && isset($request->id) && $deal->id == $request->id && $deal->auth_can_edit) :
				$info = $deal->toArray();
				$info['primary_contact'] = $deal->contact_id;
				$info['deal_stage'] = $deal->deal_stage_id;
				$info['forecast_percentage'] = $deal->probability;
				$info = (object)$info;

				if(isset($request->html)) :
					$html = view('admin.deal.partials.form', ['form' => 'edit'])->render();
				endif;
			else :
				$status = false;
			endif;

			return response()->json(['status' => $status, 'info' => $info, 'html' => $html]);
		endif;

		return redirect()->route('admin.deal.index');
	}



	public function update(Request $request, Deal $deal)
	{
		if($request->ajax()) :
			$status = true;
			$kanban = [];
			$kanban_header = null;
			$kanban_count = [];
			$kanban_card_remove = false;
			$realtime = [];
			$errors = null;
			$data = $request->all();

			if(isset($deal) && isset($request->id) && $deal->id == $request->id && $deal->auth_can_edit) :
				$data['change_owner'] = $deal->auth_can_change_owner;
				$validation = Deal::validate($data);
				if($validation->passes()) :
					if($deal->auth_can_change_owner) :
						$deal->deal_owner = $request->deal_owner;
					endif;	
					
					if($deal->deal_pipeline_id != (int)$request->deal_pipeline_id) :
						$kanban_card_remove = [$deal->kanban_card_key];
					endif;
					
					$stage_change = false;
					if($deal->deal_stage_id != (int)$request->deal_stage_id) :
						$position = Deal::getTargetPositionVal(-1);
						$deal->position = $position;
						$stage_change = true;
					endif;

					$deal->name = $request->name;
					$deal->amount = $request->amount;
					$deal->currency_id = $request->currency_id;
					$deal->closing_date = null_if_empty($request->closing_date);
					$deal->deal_pipeline_id = $request->deal_pipeline_id;
					$deal->deal_stage_id = $request->deal_stage_id;
					$deal->probability = not_null_empty($request->probability) ? $request->probability : DealStage::find($request->deal_stage_id)->probability;
					$deal->account_id = $request->account_id;
					$deal->contact_id = null_if_empty($request->contact_id);
					$deal->deal_type_id = null_if_empty($request->deal_type_id);
					$deal->source_id = null_if_empty($request->source_id);
					$deal->campaign_id = null_if_empty($request->campaign_id);
					$deal->description = null_if_empty($request->description);
					$deal->access = $request->access;
					$deal->update();

					if($request->access != 'private') :
						$deal->allowedstaffs()->forceDelete();
					endif;

					$kanban[$deal->kanban_stage_key][$deal->kanban_card_key] = $stage_change ? $deal->kanban_card_html : $deal->kanban_card;
					$kanban_header = Deal::getKanbanStageHeaderInfo();
					$kanban_count = $kanban_header['count'];

					$total_info = Deal::getTotalInfo();
					$realtime['total_deal'] = $total_info['total_deal'];
					$realtime['total_amount'] = $total_info['total_amount_html'];
					$realtime['revenue_forecast'] = $total_info['total_forecast_html'];
				else :
					$status = false;
					$errors = $validation->getMessageBag()->toArray();
				endif;
			else :
				$status = false;
			endif;

			return response()->json(['status' => $status, 'errors' => $errors, 'saveId' => $request->id, 'kanban' => $kanban, 'kanbanCount' => $kanban_count, 'kanbanHeader' => $kanban_header, 'kanbanCardRemove' => $kanban_card_remove, 'realtime' => $realtime]);
		endif;
	}



	public function singleUpdate(Request $request, Deal $deal)
	{
		if($request->ajax()) :
			$status = true;
			$html = null;
			$realtime = [];
			$real_replace = [];
			$inner_html = [];
			$tab_table = null;
			$updated_by = null;
			$last_modified = null;
			$errors = null;
			$data = $request->all();

			if(isset($deal) && $deal->auth_can_edit) :
				$data['id'] = $deal->id;
				$data['change_owner'] = (isset($request->deal_owner) && $deal->auth_can_change_owner);
				$validation = Deal::singleValidate($data, $deal);
				if($validation->passes()) :	
					if(isset($request->account_id) && $deal->account_id != $request->account_id) :
						$new_account = Account::find($request->account_id);
						$deal->update(['contact_id' => null]);
						$inner_html[] = ["select[name='contact_id']", option_attr_render($new_account->contacts_list, ''), true];
					endif;

					if(not_null_empty($request->contact_id) && !$deal->participants()->where('contact_id', $request->contact_id)->count()) :
						$deal->participants()->attach($request->contact_id);
					endif;

					if(isset($request->deal_stage_id) && $deal->deal_stage_id != $request->deal_stage_id) :
						$new_stage = DealStage::find($request->deal_stage_id);
						$deal->update(['probability' => $new_stage->probability]);
					endif;	

					if(isset($request->deal_pipeline_id) && $deal->deal_pipeline_id != $request->deal_pipeline_id) :
						$new_pipeline = DealPipeline::find($request->deal_pipeline_id);
						$new_stage = $new_pipeline->stages()->orderBy('pipeline_stages.position')->get()->first();
						$deal->update(['deal_stage_id' => $new_stage->id, 'probability' => $new_stage->probability]);
						$inner_html[] = ["select[name='deal_stage_id']", $new_pipeline->stage_options_html, false];
						$stage_html = "<div class='value' data-value='" . $new_stage->id . "' data-realtime='deal_stage_id'>" . $new_stage->name . "</div>";
						$real_replace[] = ["[data-realtime='deal_stage_id']", $stage_html];
					endif;	
						
					$update_data = replace_null_if_empty($request->all());					
					$deal->update($update_data);

					if(isset($request->access)) :
						$html = $deal->access_html;

						if($request->access != 'private') :
							$deal->allowedstaffs()->forceDelete();
						endif;	
					endif;	

					if(isset($request->currency_id)) :
						$html = $deal->amountHtml('amount');
						$real_replace[] = ["span.symbol.none", $deal->hidden_currency_info];
					endif;

					if(isset($request->contact_id)) :
						$tab_table = '#deal-participant';
					endif;	

					if(isset($request->currency_id) || isset($request->deal_stage_id) || isset($request->probability) || isset($request->deal_pipeline_id) || isset($request->closing_date)) :
						$tab_table = '#deal-stage-history';
						$probability_html = "<div class='value' data-value='" . $deal->probability . "' data-realtime='probability'>" . $deal->probability_amount . "</div>";
						$real_replace[] = ["[data-realtime='probability']", $probability_html];
						$real_replace[] = ["#deal-stage-progress", $deal->stageline_html];
						$inner_html[] = ["#deal-probability", $deal->classified_probability, false];
					endif;	

					if(isset($request->closing_date)) :
						$html = not_null_empty($deal->closing_date) ? $deal->readableDate('closing_date') : '';
					endif;

					$updated_by = "<p class='compact'>" . $deal->updatedByName() . "<br><span class='c-shadow sm'>" . $deal->updated_ampm . "</span></p>";
					$last_modified = "<p data-toggle='tooltip' data-placement='bottom' title='" . $deal->readableDateAmPm('modified_at') . "'>" . time_short_form($deal->modified_at->diffForHumans()) . "</p>";
				else :
					$status = false;
					$errors = $validation->getMessageBag()->toArray();
				endif;
			else :
				$status = false;
			endif;

			return response()->json(['status' => $status, 'html' => $html, 'updatedBy' => $updated_by, 'lastModified' => $last_modified, 'realtime' => $realtime, 'realReplace' => $real_replace, 'innerHtml' => $inner_html, 'tabTable' => $tab_table, 'errors' => $errors]);
		endif;
	}	



	public function destroy(Request $request, Deal $deal)
	{
		if($request->ajax()) :
			$status = true;
			$kanban = [];
			$kanban_header = null;
			$kanban_count = [];
			$realtime = [];

			if($deal->id != $request->id) :
				$status = false;
			endif;

			if($status == true) :
				$kanban[] = $deal->kanban_card_key;
				$deal->delete();
				$kanban_header = Deal::getKanbanStageHeaderInfo();
				$kanban_count = $kanban_header['count'];
				$total_info = Deal::getTotalInfo();
				$realtime['total_deal'] = $total_info['total_deal'];
				$realtime['total_amount'] = $total_info['total_amount_html'];
				$realtime['revenue_forecast'] = $total_info['total_forecast_html'];
				event(new \App\Events\DealDeleted([$request->id]));
			endif;	
			
			return response()->json(['status' => $status, 'kanban' => $kanban, 'kanbanCount' => $kanban_count, 'kanbanHeader' => $kanban_header, 'realtime' => $realtime]);
		endif;
	}



	public function bulkDestroy(Request $request)
	{
		if($request->ajax()) :
			$deals = $request->deals;

			$status = true;

			if(isset($deals) && count($deals) > 0) :
				$deal_ids = Deal::whereIn('id', $deals)->get()->where('auth_can_delete', true)->pluck('id')->toArray();
				Deal::whereIn('id', $deal_ids)->delete();
				event(new \App\Events\DealDeleted($deal_ids));
			else :
				$status = false;
			endif;

			return response()->json(['status' => $status]);
		endif;
	}



	public function bulkUpdate(Request $request)
	{
		if($request->ajax()) :
			$deals = $request->deals;
			$status = true;
			$errors = null;
			$data = $request->all();

			if(isset($deals) && count($deals) > 0 && isset($request->related)) :
				$validation = Deal::massValidate($data);

				if($validation->passes()) :
					$deal_ids = Deal::whereIn('id', $deals)->get()->where('auth_can_edit', true)->pluck('id')->toArray();
					$deals = Deal::whereIn('id', $deal_ids);

					if(\Schema::hasColumn('deals', $request->related)) :
						$field = $request->related;
						$update_data = [$field => null_if_empty($request->$field)];

						if($request->related == 'amount') :
							$update_data['currency_id'] = $request->currency_id;
						endif;

						if($request->related == 'account_id') :
							$deals->where('account_id', '!=', $request->account_id)->update(['contact_id' => null]);
						endif;

						if($request->related == 'contact_id') :
							$contact = Contact::find($request->contact_id);
							$deals = $deals->where('account_id', $contact->account_id);
						endif;

						if($request->related == 'deal_pipeline_id') :
							$new_pipeline = DealPipeline::find($request->deal_pipeline_id);
							$new_stage = $new_pipeline->stages()->orderBy('pipeline_stages.position')->get()->first();
							$deals->where('deal_pipeline_id', '!=', $request->deal_pipeline_id)->update(['deal_stage_id' => $new_stage->id, 'probability' => $new_stage->probability]);
						endif;

						if($request->related == 'deal_stage_id') :
							$pipeline_ids = DealStage::find($request->deal_stage_id)->pipelines->pluck('id')->toArray();
							$deals = $deals->whereIn('deal_pipeline_id', $pipeline_ids)->where('deal_stage_id', '!=', $request->deal_stage_id);
							$update_data['probability'] = DealStage::find($request->deal_stage_id)->probability;
						endif;

						$deals->update($update_data);
					endif;	
				else :
					$status = false;
					$errors = $validation->getMessageBag()->toArray();
				endif;
			else :
				$status = false;
			endif;

			return response()->json(['status' => $status, 'errors' => $errors]);
		endif;
	}



	public function bulkEmail(Request $request)
	{
		if($request->ajax()) :
			$status = true;
			$errors = null;
			$data = $request->all();

			$rules = ['from' => 'required|email', 'subject' => 'required'];
			$validation = \Validator::make($data, $rules);

			if($validation->passes()) :
				$ids = $request->deals;
				if(isset($ids) && count($ids) > 0) :
					$account_ids = Deal::whereIn('id', $ids)->groupBy('account_id')->pluck('account_id')->toArray();
					$contact_ids = Deal::whereIn('id', $ids)->whereNotNull('contact_id')->groupBy('contact_id')->pluck('contact_id')->toArray();
					$account_emails = Account::whereIn('id', $account_ids)->pluck('account_email')->toArray();
					$contact_emails = User::onlyContact()->whereIn('linked_id', $contact_ids)->pluck('email')->toArray();
					$emails = array_merge($account_emails, $contact_emails);
					$mail_message = $request->message;
					$from = $request->from;
					$from_name = auth()->user()->first_name . ' ' . auth()->user()->last_name;
					$subject = $request->subject;

					\Mail::queue('emails.regular', compact('mail_message'), function($message) use($emails, $from, $from_name, $subject)
					{
						$message->from($from, $from_name)
								->to($emails)
								->subject($subject);
					});
				endif;
			else :
				$status = false;
				$errors = $validation->getMessageBag()->toArray();
			endif;

			return response()->json(['status' => $status, 'errors' => $errors]);
		endif;
	}



	public function connectedDealData(Request $request, $module_name, $module_id)
	{
		if($request->ajax()) :
			$module = morph_to_model($module_name)::find($module_id);
			if(isset($module)) :
				$deals = $module->deals()->latest('id')->get();
				return DatatablesManager::connectedDealData($deals, $request);
			endif;
			
			return null;	
		endif;
	}



	public function kanbanCard(Request $request, DealPipeline $pipeline, DealStage $stage)
	{
		if($request->ajax()) :
			$status = true;
			$html = '';
			$load_status = true;
			$errors = null;
			$data = $request->all();

			if(isset($pipeline) && $request->pipelineId == $pipeline->id && isset($stage) && $stage->id == $request->stageId && isset($request->ids)) :
				$validation = Deal::kanbanCardValidate($data);

				if($validation->passes()) :
					$bottom_id = (int)last($request->ids);
					$bottom_deal = Deal::find($bottom_id);
					$deals = Deal::getAuthViewData()->where('position', '<', $bottom_deal->position)->where('deal_pipeline_id', $pipeline->id)->where('deal_stage_id', $stage->id)->latest('position')->get();
					$load_status = ($deals->count() > 10);
					
					foreach($deals->take(10) as $deal) :
						$html .= $deal->kanban_card_html;
					endforeach;
				else :
					$status = false;
					$errors = $validation->getMessageBag()->toArray();
				endif;	
			else :
				$status = false;	
			endif;

			return response()->json(['status' => $status, 'errors' => $errors, 'html' => $html, 'loadStatus' => $load_status]);
		endif;	
	}
}