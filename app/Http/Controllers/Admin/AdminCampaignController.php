<?php

namespace App\Http\Controllers\Admin;

use DB;
use App\Models\Campaign;
use Illuminate\Http\Request;
use App\Library\DatatablesManager;
use App\Http\Controllers\Admin\AdminBaseController;

class AdminCampaignController extends AdminBaseController
{
	public function __construct()
	{
		parent::__construct();

		$this->middleware('admin:campaign.view', ['only' => ['index', 'campaignData', 'show']]);
		$this->middleware('admin:campaign.create', ['only' => ['store']]);
		$this->middleware('admin:campaign.edit', ['only' => ['edit', 'update']]);
		$this->middleware('admin:campaign.delete', ['only' => ['destroy', 'bulkDestroy']]);
	}



	public function index()
	{
		$page = ['title' => 'Campaigns List', 'item' => 'Campaign', 'field' => 'campaigns', 'view' => 'admin.campaign', 'route' => 'admin.campaign', 'permission' => 'campaign', 'mass_update_permit' => permit('mass_update.campaign'), 'mass_del_permit' => permit('mass_delete.campaign')];
		$table = ['thead' => [['CAMPAIGN NAME', 'style' => 'min-width: 200px'], 'TYPE', 'STATUS', ['START&nbsp;DATE', 'style' => 'min-width: 80px'], ['END&nbsp;DATE', 'style' => 'min-width: 80px'], 'CAMPAIGN OWNER'], 'checkbox' => Campaign::allowMassAction(), 'action' => Campaign::allowAction()];
		$table['json_columns'] = table_json_columns(['checkbox', 'name', 'type', 'status', 'start_date', 'end_date', 'campaign_owner', 'action'], Campaign::hideColumns());

		return view('admin.campaign.index', compact('page', 'table'));
	}



	public function campaignData(Request $request)
	{
		if($request->ajax()) :
			$campaigns = Campaign::latest('id')->get();
			return DatatablesManager::campaignData($campaigns, $request);
		endif;
	}



	public function store(Request $request)
	{
		if($request->ajax()) :
			$status = true;
			$errors = null;
			$notification = null;
			$data = $request->all();
			$validation = Campaign::validate($data);

			if($validation->passes()) :
				$campaign = new Campaign;
				$campaign->campaign_owner = $request->campaign_owner;
				$campaign->campaign_type = null_if_empty($request->campaign_type);
				$campaign->name = $request->name;
				$campaign->description = null_if_empty($request->description);
				$campaign->start_date = null_if_empty($request->start_date);
				$campaign->end_date = null_if_empty($request->end_date);
				$campaign->status = null_if_empty($request->status);
				$campaign->currency_id = $request->currency_id;
				$campaign->expected_revenue = $request->expected_revenue;
				$campaign->budgeted_cost = $request->budgeted_cost;
				$campaign->actual_cost = $request->actual_cost;
				$campaign->numbers_sent = $request->numbers_sent;
				$campaign->expected_response = $request->expected_response;
				$campaign->save();

				$notification = notification_log('campaign_created', 'campaign', $campaign->id, 'staff', $request->campaign_owner);
			else :
				$status = false;
				$errors = $validation->getMessageBag()->toArray();
			endif;

			return response()->json(['status' => $status, 'errors' => $errors, 'notification' => $notification]);
		endif;
	}



	public function show(Campaign $campaign)
	{
		$page['title'] = $campaign->name;
		return view('admin.campaign.show', compact('page', 'campaign'));
	}



	public function edit(Request $request, Campaign $campaign)
	{
		if($request->ajax()) :
			$status = true;
			$info = null;

			if(isset($campaign) && isset($request->id)) :
				if($campaign->id == $request->id) :
					$info = $campaign;
				else :
					$status = false;
				endif;
			else :
				$status = false;
			endif;

			return response()->json(['status' => $status, 'info' => $info]);
		endif;

		return redirect()->route('admin.campaign-type.index');
	}



	public function update(Request $request, Campaign $campaign)
	{
		if($request->ajax()) :
			$status = true;
			$errors = null;
			$data = $request->all();

			if(isset($campaign) && isset($request->id) && $campaign->id == $request->id) :
				$validation = Campaign::validate($data);
				if($validation->passes()) :
					$campaign->campaign_owner = $request->campaign_owner;
					$campaign->campaign_type = null_if_empty($request->campaign_type);
					$campaign->name = $request->name;
					$campaign->description = null_if_empty($request->description);
					$campaign->start_date = null_if_empty($request->start_date);
					$campaign->end_date = null_if_empty($request->end_date);
					$campaign->status = null_if_empty($request->status);
					$campaign->currency_id = $request->currency_id;
					$campaign->expected_revenue = $request->expected_revenue;
					$campaign->budgeted_cost = $request->budgeted_cost;
					$campaign->actual_cost = $request->actual_cost;
					$campaign->numbers_sent = $request->numbers_sent;
					$campaign->expected_response = $request->expected_response;
					$campaign->save();
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



	public function destroy(Request $request, Campaign $campaign)
	{
		if($request->ajax()) :
			$status = true;

			if($campaign->id != $request->id) :
				$status = false;
			endif;

			if($status == true) :
				$campaign->delete();
			endif;
			
			return response()->json(['status' => $status]);
		endif;	
	}



	public function bulkDestroy(Request $request)
	{
		if($request->ajax()) :
			$campaigns = $request->campaigns;

			$status = true;

			if(isset($campaigns) && count($campaigns) > 0) :
				Campaign::whereIn('id', $campaigns)->delete();
			else :
				$status = false;
			endif;

			return response()->json(['status' => $status]);
		endif;
	}



	public function campaignSelectData(Request $request, $member_type = null, $member_id = null)
	{
		if($request->ajax()) :
			$where_not_in = [];

			if(isset($member_type) && isset($member_id)) :
				$member = morph_to_model($member_type)::find($member_id);
				if(isset($member)) :
					$where_not_in = $member->campaigns->pluck('id')->toArray();
				endif;	
			endif;

			$campaigns = Campaign::whereNotIn('id', $where_not_in)->latest('id')->get();
				
			return DatatablesManager::campaignSelectData($campaigns, $request);
		endif;
	}



	public function memberCampaignData(Request $request, $member_type, $member_id)
	{
		if($request->ajax()) :
			$member = morph_to_model($member_type)::find($member_id);
			if(isset($member)) :
				$campaigns = $member->campaigns;
				return DatatablesManager::memberCampaignData($campaigns, $request);
			endif;
			
			return null;	
		endif;
	}



	public function memberCampaignAdd(Request $request, $member_type, $member_id)
	{
		if($request->ajax()) :
			$status = false;
			$errors = null;
			$data = $request->all();
			$validation = Campaign::memberValidate($data);
			$member = morph_to_model($member_type)::find($member_id);			

			if(isset($member) && $member_id == $request->member_id && $member_type == $request->member_type) :
				if($validation->passes()) :
					$status = true;
					$data = [];
					
					if(count($request->campaigns)) :
						foreach($request->campaigns as $campaign_id) :
							$campaign_exists = $member->campaigns()->where('campaign_id', $campaign_id)->get()->count();

							if(!$campaign_exists) :
								$data = ['campaign_id' => $campaign_id, 'member_id' => $member_id, 'member_type' => $member_type, 'status' => $request->member_status];
								DB::table('campaign_members')->insert($data);
							endif;
						endforeach;
					endif;	
				else :
					$errors = $validation->getMessageBag()->toArray();				
				endif;	
			else :
				$errors['member_id'] = 'Invalid member';	
			endif;	

			return response()->json(['status' => $status, 'errors' => $errors]);
		endif;
	}



	public function memberCampaignEdit(Request $request, $member_type, $member_id, Campaign $campaign)
	{
		if($request->ajax()) :
			$member = morph_to_model($member_type)::find($member_id);
			$status = true;
			$info = null;
			$html = null;

			if(isset($member) && isset($campaign) && isset($request->id) && $campaign->id == $request->id) :
				$info = [];
				$info['campaign_id'] = $campaign->id;
				$info['member_id'] = $member_id;
				$info['member_type'] = $member_type;
				$member_campaign = $member->campaigns->where('id', $campaign->id)->first();
				$info['member_status'] = isset($member_campaign) ? $member_campaign->pivot->status : null;
				$info = (object)$info; 

				if(isset($request->html)) :
					$html = view('admin.campaign.partials.modal-edit-campaign')->render();
				endif;
			else :
				$status = false;
			endif;

			return response()->json(['status' => $status, 'info' => $info, 'html' => $html]);
		endif;
	}	



	public function memberCampaignUpdate(Request $request, $member_type, $member_id, Campaign $campaign)
	{
		if($request->ajax()) :
			$member = morph_to_model($member_type)::find($member_id);
			$data = $request->all();
			$status = false;
			$errors = [];

			if(isset($member) && isset($campaign) && $member_id == $request->member_id && $member_type == $request->member_type && $campaign->id == $request->campaign_id) :
				$validation = Campaign::memberUpdateValidate($data);			
				if($validation->passes()) :
					$status = true;

					DB::table('campaign_members')
					->where('campaign_id', $campaign->id)
					->where('member_id', $member_id)
					->where('member_type', $member_type)
					->update(['status' => $request->member_status]);
				else :
					$errors = $validation->getMessageBag()->toArray();				
				endif;	
			else :
				$errors['campaign_id'] = 'Invalid member or campaign';	
			endif;

			return response()->json(['status' => $status, 'errors' => $errors]);
		endif;
	}



	public function memberCampaignRemove(Request $request, $member_type, $member_id, Campaign $campaign)
	{
		if($request->ajax()) :
			$member = morph_to_model($member_type)::find($member_id);
			$status = false;

			if(isset($member) && isset($campaign) && $member_id == $request->member_id && $member_type == $request->member_type && $campaign->id == $request->campaign_id) :
				$member->campaigns()->detach($campaign->id);
				$status = true;
			endif;	

			return response()->json(['status' => $status]);
		endif;
	}
}