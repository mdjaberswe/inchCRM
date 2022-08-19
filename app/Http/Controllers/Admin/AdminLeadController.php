<?php

namespace App\Http\Controllers\Admin;

use Session;
use App\Models\User;
use App\Models\Role;
use App\Models\Lead;
use App\Models\Item;
use App\Models\Deal;
use App\Models\Staff;
use App\Models\Source;
use App\Models\Import;
use App\Models\Account;
use App\Models\Contact;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Campaign;
use App\Models\LeadStage;
use App\Models\DealStage;
use App\Models\SocialMedia;
use App\Jobs\SaveAllowedStaff;
use Illuminate\Http\Request;
use App\Library\DatatablesManager;
use App\Http\Controllers\Admin\AdminBaseController;

class AdminLeadController extends AdminBaseController
{
	public function __construct()
	{
		parent::__construct();

		$this->middleware('admin:lead.view', ['only' => ['index', 'leadData', 'show']]);
		$this->middleware('admin:lead.create', ['only' => ['store']]);
		$this->middleware('admin:lead.edit', ['only' => ['edit', 'update', 'convertData', 'convert']]);
		$this->middleware('admin:lead.delete', ['only' => ['destroy', 'bulkDestroy']]);
	}



	public function index()
	{
		$page = ['title' => 'Leads List', 'item' => 'Lead', 'field' => 'leads', 'view' => 'admin.lead', 'route' => 'admin.lead', 'permission' => 'lead', 'script' => true, 'import' => permit('import.lead'), 'add_icon' => 'fa fa-user-plus', 'bulk' => 'email,sms,update', 'mass_update_permit' => permit('mass_update.lead'), 'mass_del_permit' => permit('mass_delete.lead')];
		$table = ['thead' => ['name', ['lead&nbsp;score', 'data_class' => 'center'], 'email', 'phone', 'stage', 'source', 'owner'], 'custom_filter' => true, 'checkbox' => Lead::allowMassAction(), 'action' => Lead::allowAction()];
		$table['json_columns'] = table_json_columns(['checkbox', 'name', 'score', 'email', 'phone', 'stage', 'source', 'lead_owner', 'action'], Lead::hideColumns());
		$table['filter_input']['stage'] = ['type' => 'dropdown', 'options' => ['' => '- Lead Stage -'] + LeadStage::orderBy('position')->get(['id', 'name'])->pluck('name', 'id')->toArray()];
		$table['filter_input']['source'] = ['type' => 'dropdown', 'options' => ['' => '- Lead Source -'] + Source::orderBy('position')->get(['id', 'name'])->pluck('name', 'id')->toArray()];
		$table['filter_input']['lead_owner'] = ['type' => 'dropdown', 'options' => ['' => '- Lead Owner -'] + Staff::orderBy('id')->get(['id', 'first_name', 'last_name'])->pluck('name', 'id')->toArray()];
		$clear_non_imported = Import::clearNonImported('lead');

		return view('admin.lead.index', compact('page', 'table'));
	}



	public function leadData(Request $request)
	{
		if($request->ajax()) :
			$leads = Lead::getAuthViewData()->latest('id')->get();
			return DatatablesManager::leadData($leads, $request);
		endif;
	}



	public function indexKanban(Request $request)
	{
		$page = ['title' => 'Leads Kanban', 'item' => 'Lead', 'item_title' => breadcrumbs_render('admin.lead.index:Leads|All Leads'), 'view' => 'admin.lead', 'route' => 'admin.lead', 'permission' => 'lead', 'script' => true, 'import' => permit('import.lead'), 'modal_edit' => true, 'modal_delete' => true, 'modal_bulk_update' => false, 'modal_bulk_delete' => false];
		$leads_kanban = Lead::getKanbanData();

		return view('admin.lead.kanban', compact('page', 'leads_kanban'));
	}



	public function report(Request $request)
	{
		$page = ['title' => 'Lead Report', 'item' => 'Lead', 'view' => 'admin.lead', 'route' => 'admin.lead', 'permission' => 'lead', 'script' => true, 'import' => permit('import.lead'), 'modal_edit' => false, 'modal_delete' => false, 'modal_bulk_update' => false, 'modal_bulk_delete' => false];
		$lead_report = ['lead_funnel' => Lead::funnelJsonData(), 'lead_pie_source' => Lead::pieSourceData(), 'lead_stat' => Lead::numberOfLeadsReport('lead_stat'), 'lead_conversion' => Lead::numberOfLeadsReport('lead_conversion'), 'lost_lead_rate' => Lead::numberOfLeadsReport('lost_lead_rate'), 'lead_conversion_timeline' => Lead::conversionTimelineData(), 'lead_converted_leaderboard' => Lead::convertedLeaderboardData()];

		return view('admin.lead.report', compact('page', 'lead_report'));
	}



	public function store(Request $request)
	{
		if($request->ajax()) :
			$status = true;
			$errors = null;
			$kanban = [];
			$kanban_count = [];
			$notification = null;
			$data = $request->all();
			$validation = Lead::validate($data);

			if($validation->passes()) :
				$position = Lead::getTargetPositionVal(-1);

				$lead = new Lead;
				$lead->lead_owner = $request->lead_owner;
				$lead->company = null_if_empty($request->company);
				$lead->first_name = null_if_empty($request->first_name);
				$lead->last_name  = $request->last_name;
				$lead->title = null_if_empty($request->title);
				$lead->email = null_if_empty($request->email);
				$lead->phone = null_if_empty($request->phone);
				$lead->source_id = null_if_empty($request->source_id);
				$lead->lead_stage_id = $request->lead_stage_id;
				$lead->no_of_employees = $request->no_of_employees;
				$lead->currency_id = $request->currency_id;
				$lead->annual_revenue = $request->annual_revenue;
				$lead->street = null_if_empty($request->street);
				$lead->city = null_if_empty($request->city);
				$lead->state = null_if_empty($request->state);
				$lead->zip = null_if_empty($request->zip);
				$lead->country_code = null_if_empty($request->country_code);
				$lead->description = null_if_empty($request->description);
				$lead->access = $request->access;
				$lead->position = $position;

				if(isset($request->image) && !empty($request->image)) :
					$temp_path = storage_path('app/temp/' . $request->image);
					if(file_exists($temp_path)) :
						$image_path = storage_path('app/leads/' . $request->image);
						\File::move($temp_path, $image_path);
						$lead->image = $request->image;
					endif;	
				endif;	

				$lead->save();	

				if($request->access == 'private') :
					dispatch(new SaveAllowedStaff($request->staffs, 'lead', $lead->id, $request->can_write, $request->can_delete));
				endif;

				$kanban[$lead->kanban_stage_key][] = $lead->kanban_card_html;
				$kanban_count = Lead::getKanbanStageCount();

				$notification = notification_log('lead_created', 'lead', $lead->id, 'staff', $request->lead_owner);
			else :
				$status = false;
				$errors = $validation->getMessageBag()->toArray();
			endif;

			return response()->json(['status' => $status, 'kanban' => $kanban, 'kanbanCount' => $kanban_count, 'errors' => $errors, 'notification' => $notification]);
		endif;
	}



	public function show(Request $request, Lead $lead, $infotype = null)
	{
		$page = ['title' => 'Lead: ' . $lead->name, 'item_title' => breadcrumbs_render("admin.lead.index:Leads|<span data-realtime='first_name'>" . $lead->name . "</span>"), 'item' => 'Lead', 'view' => 'admin.lead', 'tabs' => ['list' => Lead::informationTypes(), 'default' => Lead::defaultInfoType($infotype), 'item_id' => $lead->id, 'url' => 'tab/lead']];
		return view('admin.lead.show', compact('page', 'lead'));
	}



	public function edit(Request $request, Lead $lead)
	{
		if($request->ajax()) :
			$status = true;
			$info = null;

			if(isset($lead) && isset($request->id) && $lead->auth_can_edit) :
				if($lead->id == $request->id) :
					$info = $lead->toArray();

					$info['freeze'] = [];

					if(!$lead->auth_can_change_owner) :
						$info['freeze'][] = 'lead_owner';
					endif;	

					$info['modal_image'] = $lead->getAvatarAttribute(true);

					$info = (object)$info;
				else :
					$status = false;
				endif;
			else :
				$status = false;
			endif;

			return response()->json(['status' => $status, 'info' => $info]);
		endif;

		return redirect()->route('admin.lead.index');
	}



	public function update(Request $request, Lead $lead)
	{
		if($request->ajax()) :
			$status = true;
			$kanban = [];
			$kanban_count = [];
			$errors = null;
			$data = $request->all();

			if(isset($lead) && isset($request->id) && $lead->id == $request->id && $lead->auth_can_edit) :
				$data['change_owner'] = $lead->auth_can_change_owner;
				$validation = Lead::validate($data);
				if($validation->passes()) :
					if($lead->auth_can_change_owner) :
						$lead->lead_owner = $request->lead_owner;
					endif;

					$lead_image = $lead->image;
					$lead_image_path = $lead->image_path;
					$old_stage = $lead->lead_stage_id;
					$new_stage = (int)$request->lead_stage_id;

					if($old_stage != $new_stage) :
						$position = Lead::getTargetPositionVal(-1);
						$lead->position = $position;
					endif;
						
					$lead->company = null_if_empty($request->company);
					$lead->first_name = null_if_empty($request->first_name);
					$lead->last_name  = $request->last_name;
					$lead->title = null_if_empty($request->title);
					$lead->email = null_if_empty($request->email);
					$lead->phone = null_if_empty($request->phone);
					$lead->source_id = null_if_empty($request->source_id);
					$lead->lead_stage_id = $request->lead_stage_id;
					$lead->no_of_employees = $request->no_of_employees;
					$lead->currency_id = $request->currency_id;
					$lead->annual_revenue = $request->annual_revenue;
					$lead->street = null_if_empty($request->street);
					$lead->city = null_if_empty($request->city);
					$lead->state = null_if_empty($request->state);
					$lead->zip = null_if_empty($request->zip);
					$lead->country_code = null_if_empty($request->country_code);
					$lead->description = null_if_empty($request->description);
					$lead->access = $request->access;

					if(isset($request->image) && ($lead->image != $request->image)) :
						$temp_path = storage_path('app/temp/' . $request->image);
						if(file_exists($temp_path)) :
							$image_path = storage_path('app/leads/' . $request->image);
							\File::move($temp_path, $image_path);
							$lead->image = $request->image;
						endif;
					endif;	

					if(is_null($request->image) || (isset($request->image) && ($lead_image != $request->image))) :
						if(!is_null($lead_image)) :
							\Storage::disk('base')->delete($lead_image_path);
						endif;	
					endif;	

					$lead->update();	

					if($request->access != 'private') :
						$lead->allowedstaffs()->forceDelete();
					endif;

					$kanban[$lead->kanban_stage_key][$lead->kanban_card_key] = ($old_stage != $new_stage) ? $lead->kanban_card_html : $lead->kanban_card;
					$kanban_count = Lead::getKanbanStageCount();
				else :
					$status = false;
					$errors = $validation->getMessageBag()->toArray();
				endif;
			else :
				$status = false;
			endif;

			return response()->json(['status' => $status, 'kanban' => $kanban, 'kanbanCount' => $kanban_count, 'errors' => $errors, 'saveId' => $request->id]);
		endif;
	}



	public function singleUpdate(Request $request, Lead $lead)
	{
		if($request->ajax()) :
			$status = true;
			$html = null;
			$realtime = [];
			$real_replace = [];
			$updated_by = null;
			$last_modified = null;
			$modal_title = null;
			$errors = null;
			$data = $request->all();

			if(isset($lead) && $lead->auth_can_edit) :
				$data['id'] = $lead->id;
				$data['change_owner'] = (isset($request->lead_owner) && $lead->auth_can_change_owner);
				$validation = Lead::singleValidate($data);
				if($validation->passes()) :	
					$update_data = replace_null_if_empty($request->all());
					$lead->update($update_data);

					$media = single_request_field($request, ['facebook', 'twitter', 'skype']);
					if(isset($media)) :
						$lead->socialmedia()->whereMedia($media)->forceDelete();
						$lead->socialmedia()->create(['media' => $media, 'data' => json_encode(['link' => $request->$media])]);
						
						if($media == 'skype') :
							$html = non_property_checker($lead->getSocialDataAttribute($media), 'link');
						else :	
							$html = "<a href='" . $lead->getSocialLinkAttribute($media) . "' target='_blank'>" . non_property_checker($lead->getSocialDataAttribute($media), 'link') . "</a>";
						endif;
					endif;

					if(isset($request->access)) :
						$html = $lead->access_html;

						if($request->access != 'private') :
							$lead->allowedstaffs()->forceDelete();
						endif;	
					endif;	

					if(isset($request->first_name)) :
						$html = $lead->name;
					endif;

					if(isset($request->date_of_birth)) :
						$html = not_null_empty($lead->date_of_birth) ? $lead->readableDate('date_of_birth') : '';
					endif;

					if(isset($request->currency_id)) :
						$html = "<span class='symbol'>" . $lead->currency->symbol . "</span> " . $lead->amountFormat('annual_revenue');
						$real_replace[] = ['span.symbol.none', $lead->hidden_currency_info];
					endif;	

					if(isset($request->website)) :
						$html = "<a href='" . quick_url($lead->website) . "' target='_blank'>" . $lead->website . "</a>";
					endif;	

					$realtime[] = ['lead_score_html', $lead->lead_score_html];

					$updated_by = "<p class='compact'>" . $lead->updatedByName() . "<br><span class='c-shadow sm'>" . $lead->updated_ampm . "</span></p>";
					$last_modified = "<p data-toggle='tooltip' data-placement='bottom' title='" . $lead->readableDateAmPm('modified_at') . "'>" . time_short_form($lead->modified_at->diffForHumans()) . "</p>";
					$modal_title = $lead->complete_name;
				else :
					$status = false;
					$errors = $validation->getMessageBag()->toArray();
				endif;
			else :
				$status = false;
			endif;

			return response()->json(['status' => $status, 'html' => $html, 'updatedBy' => $updated_by, 'lastModified' => $last_modified, 'modalTitle' => $modal_title, 'realtime' => $realtime, 'realReplace' => $real_replace, 'errors' => $errors]);
		endif;
	}	



	public function itemData(Request $request, Lead $lead)
	{
		if($request->ajax()) :
			$items = $lead->items;
			return DatatablesManager::leadItemData($items, $request);
		endif;
	}



	public function updateItem(Request $request, Lead $lead, Item $item)
	{
		if($request->ajax()) :
			$status = false;
			$realtime = [];
			$errors = [];

			if(isset($lead) && isset($item)) :
				$rules = ['quantity' => 'numeric', 'price' => 'numeric'];
				$validation = \Validator::make($request->all(), $rules);

				if($validation->passes()) :
					$status = true;
					$data = [];
					
					if(isset($request->price)) :
						$data['rate'] = $request->price;
					endif;	

					if(isset($request->quantity)) :
						$data['quantity'] = $request->quantity;
					endif;

					\DB::table('item_lead')
					->where('item_id', $item->id)
					->where('lead_id', $lead->id)
					->update($data);

					$realtime['total'] = $lead->item_total;
				else :
					$errors = $validation->getMessageBag()->toArray();				
				endif;	
			else :
				$errors['invalid'] = 'Invalid lead or item';	
			endif;	

			return response()->json(['status' => $status, 'realtime' => $realtime, 'errors' => $errors]);
		endif;
	}



	public function addItem(Request $request, Lead $lead)
	{
		if($request->ajax()) :
			$status = false;
			$errors = null;
			$realtime = [];

			if(isset($lead) && $lead->id == $request->parent_id) :
				$rules = ['items' => 'required|exists:items,id,deleted_at,NULL'];
				$validation = \Validator::make($request->all(), $rules);

				if($validation->passes()) :
					$status = true;
					$data = [];
					
					foreach($request->items as $item_id) :
						$item_exists = $lead->items()->where('item_id', $item_id)->get()->count();

						if(!$item_exists) :
							$item = Item::find($item_id);
							$rate = $item->price;
							if($item->currency_id != $lead->currency_id) :
								$rate = Currency::exchangeCurrency([$item->currency_id, $item->price], $lead->currency_id);
							endif;

							$data = ['item_id' => $item_id, 'lead_id' => $lead->id, 'unit' => 'Unit', 'quantity' => 1, 'rate' => $rate];
							\DB::table('item_lead')->insert($data);
						endif;
					endforeach;	

					$realtime['total'] = $lead->item_total;
				else :
					$errors = $validation->getMessageBag()->toArray();				
				endif;	
			else :
				$errors['invalid'] = 'Invalid lead';	
			endif;	

			return response()->json(['status' => $status, 'realtime' => $realtime, 'errors' => $errors]);
		endif;
	}



	public function removeItem(Request $request, Lead $lead, Item $item)
	{
		if($request->ajax()) :
			$status = false;
			$realtime = [];

			if(isset($lead) && isset($item) && $request->remove == true) :
				$status = true;
				$lead->items()->detach($item->id);
				$realtime['total'] = $lead->item_total;
			endif;	

			return response()->json(['status' => $status, 'realtime' => $realtime]);
		endif;
	}



	public function destroy(Request $request, Lead $lead)
	{
		if($request->ajax()) :
			$status = true;
			$kanban = [];
			$kanban_count = [];
			$redirect = null;

			if($lead->id != $request->id || !$lead->auth_can_delete) :
				$status = false;
			endif;

			if($status == true) :
				if($request->redirect) :
					$prev = Lead::getAuthViewData()->where('id', '>', $lead->id)->get()->first();
					$next = Lead::getAuthViewData()->where('id', '<', $lead->id)->latest('id')->get()->first();
					
					if(isset($next)) :
						$redirect = route('admin.lead.show', $next->id);
					elseif(isset($prev)) :
						$redirect = route('admin.lead.show', $prev->id);
					else :
						$redirect = route('admin.lead.index');
					endif;	
				endif;	

				$kanban[] = $lead->kanban_card_key;
				$lead->delete();
				$kanban_count = Lead::getKanbanStageCount();
				event(new \App\Events\LeadDeleted([$request->id]));
			endif;	
			
			return response()->json(['status' => $status, 'kanban' => $kanban, 'kanbanCount' => $kanban_count, 'redirect' => $redirect]);
		endif;
	}



	public function bulkUpdate(Request $request)
	{
		if($request->ajax()) :
			$leads = $request->leads;
			$status = true;
			$errors = null;
			$data = $request->all();

			if(isset($leads) && count($leads) > 0 && isset($request->related)) :
				$validation = Lead::massValidate($data);

				if($validation->passes()) :
					$leads = Lead::whereIn('id', $leads)->get()->where('auth_can_edit', true)->pluck('id')->toArray();
					$leads = Lead::whereIn('id', $leads);

					if(\Schema::hasColumn('leads', $request->related)) :
						$field = $request->related;
						$update_data = [$field => null_if_empty($request->$field)];

						if($request->related == 'annual_revenue') :
							$update_data['currency_id'] = $request->currency_id;
						endif;	

						$leads->update($update_data);
					endif;	

					if(in_array($request->related, ['facebook', 'twitter', 'skype'])) :
						$media = $request->related;
						foreach($leads->get() as $lead) :
							$lead->socialmedia()->whereMedia($media)->forceDelete();
							$lead->socialmedia()->create(['media' => $media, 'data' => json_encode(['link' => $request->$media])]);
						endforeach;
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



	public function bulkDestroy(Request $request)
	{
		if($request->ajax()) :
			$leads = $request->leads;

			$status = true;

			if(isset($leads) && count($leads) > 0) :
				$lead_ids = Lead::whereIn('id', $leads)->get()->where('auth_can_delete', true)->pluck('id')->toArray();
				Lead::whereIn('id', $lead_ids)->delete();
				event(new \App\Events\LeadDeleted($lead_ids));
			else :
				$status = false;
			endif;

			return response()->json(['status' => $status]);
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
				$ids = $request->leads;
				if(isset($ids) && count($ids) > 0) :
					$emails = Lead::whereIn('id', $ids)->pluck('email')->toArray();
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



	public function kanbanCard(Request $request, LeadStage $stage)
	{
		if($request->ajax()) :
			$status = true;
			$html = '';
			$load_status = true;
			$errors = null;
			$data = $request->all();

			if(isset($stage) && $stage->id == $request->stageId && isset($request->ids)) :
				$validation = Lead::kanbanCardValidate($data);

				if($validation->passes()) :
					$bottom_id = (int)last($request->ids);
					$bottom_lead = Lead::find($bottom_id);
					$leads = Lead::getAuthViewData()->where('position', '<', $bottom_lead->position)->where('lead_stage_id', $stage->id)->latest('position')->get();
					$load_status = ($leads->count() > 10);
					
					foreach($leads->take(10) as $lead) :
						$html .= $lead->kanban_card_html;
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



	public function convertData(Request $request, Lead $lead)
	{
		if($request->ajax()) :
			$status = true;
			$info = null;

			if(isset($lead) && isset($request->id)) :
				if($lead->id == $request->id && $lead->auth_can_convert) :
					$info = $lead->toArray();
					$info['owner'] = $lead->lead_owner;
					$info['account_name'] = $lead->company;
					$info['name'] = $lead->name;
					$lead_convert_stage = LeadStage::whereCategory('converted');
					$default_converted_stage = $lead_convert_stage->first()->id;
					$info['lead_stage_id'] = $lead->leadstage->category == 'converted' ? $lead->lead_stage_id : $default_converted_stage;
					$info['amount'] = $lead->deal_amount;

					$info['hide'] = [];
					if($lead_convert_stage->count() == 1) :
						$info['hide'][] = 'leadstage';
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
	}



	public function convert(Request $request, Lead $lead)
	{
		if($request->ajax()) :
			$status = true;
			$kanban = [];
			$kanban_count = [];
			$errors = null;
			$data = $request->all();

			if(isset($lead) && isset($request->id) && $lead->id == $request->id && $lead->auth_can_convert) :
				$validation = Lead::convertValidate($data);
				if($validation->passes()) :
					if($request->account_type == 'new') :
						$account = new Account;
						$account->account_owner = $request->owner;
						$account->account_name = $request->account_name;
						$account->account_email = $lead->email;
						$account->account_phone = $lead->phone;
						$account->fax = $lead->fax;
						$account->website = $lead->website;
						$account->no_of_employees = $lead->no_of_employees;
						$account->currency_id = $lead->currency_id;
						$account->annual_revenue = $lead->annual_revenue;
						$account->street = $lead->street;
						$account->city = $lead->city;
						$account->state = $lead->state;
						$account->zip = $lead->zip;
						$account->country_code = $lead->country_code;
						$account->timezone = $lead->timezone;
						$account->description = $lead->description;
						$account->save();
					else :
						$account = Account::find($request->account_id);
					endif;	

					$contact = new Contact;
					$contact->contact_owner = $request->owner;
					$contact->account_id = $account->id;
					$contact->source_id = $lead->source_id;
					$contact->first_name = $lead->first_name;
					$contact->last_name  = $lead->last_name;
					$contact->title = $lead->title;
					$contact->phone = $lead->phone;	
					$contact->fax = $lead->fax;	
					$contact->website = $lead->website;
					$contact->currency_id = $lead->currency_id;
					$contact->annual_revenue = $lead->annual_revenue;
					$contact->street = $lead->street;
					$contact->city = $lead->city;
					$contact->state = $lead->state;
					$contact->zip = $lead->zip;
					$contact->country_code = $lead->country_code;
					$contact->timezone = $lead->timezone;
					$contact->description = $lead->description;		
					$contact->access = $lead->access;

					if(isset($lead->image) && file_exists(storage_path($lead->image_path))) :
						$lead_image_path = storage_path($lead->image_path);						
						$image_original_name = uploaded_filename_original($lead->image);
						$contact_image = generate_uploaded_filename($image_original_name);
						$image_path = storage_path('app/contacts/' . $contact_image);
						\File::copy($lead_image_path, $image_path);
						$contact->image = $contact_image;
					endif;	

					$contact->save();

					if($lead->socialmedia()->count()) :
						foreach($lead->socialmedia as $lead_socialmedia) :
							SocialMedia::create(['linked_type' => 'contact', 'linked_id' => $contact->id, 'media' => $lead_socialmedia->media, 'data' => $lead_socialmedia->data]);
							if($request->account_type == 'new') :
								SocialMedia::create(['linked_type' => 'account', 'linked_id' => $account->id, 'media' => $lead_socialmedia->media, 'data' => $lead_socialmedia->data]);
							endif;	
						endforeach;	
					endif;	

					$user = new User;				
					$user->email = $request->email;				
					$user->password = bcrypt($request->password);
					$user->linked_id = $contact->id;
					$user->linked_type = 'contact';
					$user->save();
					$user->roles()->attach(Role::getClientDefaultIds());

					$old_stage = $lead->lead_stage_id;
					$new_stage = $request->lead_stage_id;

					$lead->lead_stage_id = $request->lead_stage_id;
					$lead->converted_account_id = $account->id;
					$lead->converted_contact_id = $contact->id;
					$lead->save();	

					if(isset($request->new_deal)) :
						$deal = new Deal;
						$deal->name = $request->deal_name;
						$deal->account_id = $account->id;
						$deal->contact_id = $contact->id;
						$deal->deal_owner = $request->owner;
						$deal->deal_pipeline_id = $request->deal_pipeline_id;
						$deal->deal_stage_id = $request->deal_stage_id;
						$deal->probability = DealStage::find($request->deal_stage_id)->probability;
						$deal->closing_date = $request->closing_date;				
						$deal->amount = $request->amount;
						$deal->currency_id = $request->currency_id;
						$deal->access = $lead->access;
						$deal->save();

						$deal->participants()->attach($contact->id);
					endif;

					if($lead->items->count()) :
						$contact->items()->attach($lead->cart_items);

						if($request->account_type == 'new') :
							$account->items()->attach($lead->cart_items);
						endif;
						
						if(isset($deal)) :	
							$deal->items()->attach($lead->cart_items);
						endif;	
					endif;

					$kanban[$lead->kanban_stage_key][$lead->kanban_card_key] = ($old_stage != $new_stage) ? $lead->kanban_card_html : $lead->kanban_card;
					$kanban_count = Lead::getKanbanStageCount();
				else :
					$status = false;
					$errors = $validation->getMessageBag()->toArray();
				endif;
			else :
				$status = false;
			endif;

			return response()->json(['status' => $status, 'kanban' => $kanban, 'kanbanCount' => $kanban_count, 'errors' => $errors]);
		endif;
	}



	public function reportFilter(Request $request, $type)
	{
		if($request->ajax()) :
			$status = true;
			$info = null;
			$html = null;

			if(isset($type) && isset($request->id) && $type == $request->id && in_array($type, Lead::reportTypes()['list'])) :
				$info = [];
				$info['show'] = [];
				$info['hide'] = [];		
				$html = view('admin.lead.partials.report-filter-form', ['type' => $type])->render();
				$info = Lead::reportFilterParameters($type);

				if($type == 'lead_funnel') :
					if($info['lead_stage_condition'] == '') :
						$info['hide'][] = 'lead_stage_id';					
					else :
						$info['show'][] = 'lead_stage_id';
					endif;						
					$html = view('admin.lead.partials.funnel-filter-form')->render();
				elseif($type == 'lead_pie_source') :
					if($info['lead_source_condition'] == '') :
						$info['hide'][] = 'source_id';					
					else :
						$info['show'][] = 'source_id';
					endif;
					$html = view('admin.lead.partials.pie-source-filter-form')->render();
				endif;	

				if($info['timeperiod'] == 'between') :
					$info['show'][] = 'start_date';
					$info['start_date'] = date('Y-m-d', strtotime($info['start_date']));
					$info['end_date'] = date('Y-m-d', strtotime($info['end_date']));
				else :
					$info['hide'][] = 'start_date';
				endif;	

				$info = (object)$info;
			else :
				$status = false;
			endif;

			return response()->json(['status' => $status, 'info' => $info, 'html' => $html]);
		endif;
	}



	public function postReportFilter(Request $request, $type)
	{
		if($request->ajax()) :
			$status = true;
			$funnel_json_data = null;
			$realtime = [];
			$errors = null;
			$data = $request->all();
			$json_response = [];

			if(isset($type) && isset($request->type) && $type == $request->type && in_array($type, Lead::reportTypes()['list'])) :
				$validation = Lead::reportFilterValidate($data);
				if($validation->passes()) :
					$update_param = Lead::updateReportParameters($request, $type);

					switch($type) :
						case 'lead_funnel' :
							$json_response['funnelId'] = '#lead-d3-funnel';
							$json_response['funnelJsonData'] = Lead::funnelJsonData();
							$realtime[] = ['lead-time-period', $json_response['funnelJsonData']['timeperiod_display']];
						break;

						case 'lead_pie_source' :
							$json_response['pieId'] = '#lead-source-pie';
							$json_response['pieData'] = Lead::pieSourceData();
							$realtime[] = ['lead-source-time-period', $json_response['pieData']['timeperiod_display']];		
						break;	

						case 'lead_stat' :
							$leads_stat = Lead::numberOfLeadsReport($type);
							$realtime[] = ['lead-stat-time-period', $leads_stat['timeperiod_display']];	
							$realtime[] = ['active-lead', $leads_stat['active_leads']];
							$realtime[] = ['converted-lead', $leads_stat['converted_leads']];
							$realtime[] = ['lost-lead', $leads_stat['lost_leads']];	
						break;	

						case 'lead_conversion' :
							$leads_conversion = Lead::numberOfLeadsReport($type);
							$realtime[] = ['lead-conversion-time-period', $leads_conversion['timeperiod_display']];	
							$realtime[] = ['lead-conversion', $leads_conversion['conversion']];
						break;	

						case 'lost_lead_rate' :
							$lost_lead_rate = Lead::numberOfLeadsReport($type);
							$realtime[] = ['lead-lost-rate-time-period', $lost_lead_rate['timeperiod_display']];	
							$realtime[] = ['lead-lost-rate', $lost_lead_rate['lost_lead_rate']];
						break;	

						case 'lead_conversion_timeline' :						
							$json_response['timelineId'] = '#lead-conversion-timeline';
							$json_response['timeline'] = Lead::conversionTimelineData();
							$realtime[] = ['lead-conversion-time-line-period', $json_response['timeline']['timeperiod_display']];					
						break;	

						case 'lead_converted_leaderboard' :
							$lead_converted_leaderboard = Lead::convertedLeaderboardData();
							$realtime[] = ['lead-converted-leaderboard-time-period', $lead_converted_leaderboard['timeperiod_display']];	
							$realtime[] = ['rank-html-1', $lead_converted_leaderboard['rank_html1']];
							$realtime[] = ['rank-html-2', $lead_converted_leaderboard['rank_html2']];
							$realtime[] = ['rank-html-3', $lead_converted_leaderboard['rank_html3']];
						break;	
					endswitch;	
				else :
					$status = false;
					$errors = $validation->getMessageBag()->toArray();
				endif;
			else :
				$status = false;
			endif;

			$json_response['status'] = $status;
			$json_response['errors'] = $errors;
			$json_response['realtime'] = $realtime;

			return response()->json($json_response);
		endif;
	}
}
