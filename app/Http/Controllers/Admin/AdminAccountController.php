<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Contact;
use App\Models\Project;
use App\Models\Account;
use App\Jobs\SaveAllowedStaff;
use Illuminate\Http\Request;
use App\Library\DatatablesManager;
use App\Http\Controllers\Admin\AdminBaseController;

class AdminAccountController extends AdminBaseController
{
	public function __construct()
	{
		parent::__construct();
		
		$this->middleware('admin:account.view', ['only' => ['index', 'accountData', 'show']]);
		$this->middleware('admin:account.create', ['only' => ['create', 'store']]);
		$this->middleware('admin:account.edit', ['only' => ['edit', 'update']]);
		$this->middleware('admin:account.delete', ['only' => ['destroy', 'bulkDestroy']]);
	}



	public function index()
	{
		$page = ['title' => 'Accounts List', 'item' => 'Account', 'field' => 'accounts', 'view' => 'admin.account', 'route' => 'admin.account', 'permission' => 'account', 'import' => permit('import.account'), 'bulk' => 'email,sms,update', 'mass_update_permit' => permit('mass_update.account'), 'mass_del_permit' => permit('mass_delete.account')];
		$table = ['thead' => ['name', 'phone', ['open&nbsp;deals&nbsp;amt', 'tooltip' => 'Open deals amount'], 'invoice', 'payment', 'owner'], 'checkbox' => Account::allowMassAction(), 'action' => Account::allowAction()];
		$table['json_columns'] = table_json_columns(['checkbox', 'account_name' => ['className' => 'align-l-md-space'], 'account_phone', 'open_deals_amount' => ['className' => 'align-r'], 'invoice' => ['className' => 'align-r'], 'payment' => ['className' => 'align-r'], 'account_owner' => ['className' => 'align-l-space'], 'action'], Account::hideColumns());

		return view('admin.account.index', compact('page', 'table'));
	}



	public function accountData(Request $request)
	{
		if($request->ajax()) :
			$accounts = Account::latest('id')->get();
			return DatatablesManager::accountData($accounts, $request);
		endif;
	}



	public function subAccountData(Request $request, Account $account)
	{
		if($request->ajax()) :
			$sub_accounts = $account->subAccounts()->latest('id')->get();
			return DatatablesManager::subAccountData($sub_accounts, $request);
		endif;
	}



	public function contactData(Request $request, Account $account)
	{
		if($request->ajax()) :
			$contacts = $account->contacts()
								->select(['id', 'account_id', 'currency_id', 'first_name', 'last_name', 'phone', 'title'])
								->latest('id')
								->get();

			return DatatablesManager::appendContactData($contacts, $request);
		endif;
	}



	public function store(Request $request)
	{
		if($request->ajax()) :
			$status = true;
			$errors = null;
			$notification = null;
			$data = $request->all();
			$node = null;
			$validation = Account::validate($data);

			if($validation->passes()) :
				$parent = not_null_empty($request->parent_account) ? Account::find($request->parent_account) : null;
				$sibling_id = (!is_null($parent) && $parent->directChilds->count()) ? $parent->directChilds->pluck('id')->last() : null; 

				$account = new Account;
				$account->parent_id = null_if_empty($request->parent_account);
				$account->account_owner = $request->account_owner;
				$account->account_name = $request->account_name;
				$account->account_email = $request->account_email;
				$account->account_phone = $request->account_phone;
				$account->account_type_id = null_if_empty($request->account_type_id);
				$account->industry_type_id = null_if_empty($request->industry_type_id);
				$account->fax = $request->fax;
				$account->website = $request->website;
				$account->no_of_employees = $request->no_of_employees;
				$account->currency_id = $request->currency_id;
				$account->annual_revenue = $request->annual_revenue;
				$account->street = $request->street;
				$account->city = $request->city;
				$account->state = $request->state;
				$account->zip = $request->zip;
				$account->country_code = null_if_empty($request->country_code);
				$account->description = $request->description;
				$account->access = $request->access;

				if(isset($request->image) && !empty($request->image)) :
					$temp_path = storage_path('app/temp/' . $request->image);
					if(file_exists($temp_path)) :
						$image_path = storage_path('app/accounts/' . $request->image);
						\File::move($temp_path, $image_path);
						$account->image = $request->image;
					endif;	
				endif;	

				$account->save();
				$node = ['id' => $account->id, 'parentId' => $account->parent_id, 'siblingId' => $sibling_id, 'image' => $account->avatar, 'template' => $account->getHierarchyTemplateAttribute($request->hierarchy_id)];

				if($request->access == 'private') :
					dispatch(new SaveAllowedStaff($request->staffs, 'account', $account->id, $request->can_write, $request->can_delete));
				endif;	

				$notification = notification_log('account_created', 'account', $account->id, 'staff', $request->account_owner);
			else :
				$status = false;
				$errors = $validation->getMessageBag()->toArray();
			endif;

			return response()->json(['status' => $status, 'errors' => $errors, 'module' => 'account', 'tabTable' => '#sub-account', 'node' => $node, 'notification' => $notification]);
		endif;
	}



	public function show(Account $account, $infotype = null)
	{
		$page = ['title' => 'Account: ' . $account->name, 'item_title' => breadcrumbs_render("admin.account.index:Accounts|<span data-realtime='account_name'>" . $account->name . "</span>"), 'item' => 'Account', 'view' => 'admin.account', 'tabs' => ['list' => Account::informationTypes(), 'default' => Account::defaultInfoType($infotype), 'item_id' => $account->id, 'url' => 'tab/account']];
		return view('admin.account.show', compact('page', 'account'));
	}



	public function edit(Request $request, Account $account)
	{
		if($request->ajax()) :
			$status = true;
			$info = null;
			$html = null;

			if(isset($account) && isset($request->id) && $account->auth_can_edit) :
				if($account->id == $request->id) :
					$info = $account->toArray();

					$info['freeze'] = [];

					if(!$account->auth_can_change_owner) :
						$info['freeze'][] = 'account_owner';
					endif;	

					$info['parent_account'] = $account->parent_id;
					$info['modal_image'] = $account->getAvatarAttribute(true);

					$info['selectlist'] = [];
					$info['selectlist']['parent_account'] = Account::where('id', '!=', $account->id)->get(['id', 'account_name'])->pluck('account_name', 'id')->toArray();					

					$info = (object)$info;

					if(isset($request->html)) :
						$html = view('admin.account.partials.form', ['form' => 'edit'])->render();
					endif;	
				else :
					$status = false;
				endif;
			else :
				$status = false;
			endif;

			return response()->json(['status' => $status, 'info' => $info, 'html' => $html]);
		endif;

		return redirect()->route('admin.account.index');
	}



	public function update(Request $request, Account $account)
	{
		if($request->ajax()) :
			$status = true;
			$errors = null;
			$data = $request->all();			

			if(isset($account) && isset($request->id) && $account->id == $request->id && $account->auth_can_edit) :
				$data['change_owner'] = $account->auth_can_change_owner;
				$validation = Account::validate($data);
				if($validation->passes()) :
					if($account->auth_can_change_owner) :
						$account->account_owner = $request->account_owner;
					endif;	

					if(not_null_empty($request->parent_account)) :
						$parent_account = Account::find($request->parent_account);
						if($parent_account->parent_id == $account->id) :
							$parent_account->update(['parent_id' => $account->parent_id]);
						endif;	
					endif;					

					$account->parent_id = null_if_empty($request->parent_account);
					$account->account_name = $request->account_name;
					$account->account_email = $request->account_email;
					$account->account_phone = $request->account_phone;
					$account->account_type_id = null_if_empty($request->account_type_id);
					$account->industry_type_id = null_if_empty($request->industry_type_id);
					$account->fax = $request->fax;
					$account->website = $request->website;
					$account->no_of_employees = $request->no_of_employees;
					$account->currency_id = $request->currency_id;
					$account->annual_revenue = $request->annual_revenue;
					$account->street = $request->street;
					$account->city = $request->city;
					$account->state = $request->state;
					$account->zip = $request->zip;
					$account->country_code = null_if_empty($request->country_code);
					$account->description = $request->description;
					$account->access = $request->access;
					$account_image = $account->image;
					$account_image_path = $account->image_path;

					if(isset($request->image) && ($account->image != $request->image)) :
						$temp_path = storage_path('app/temp/' . $request->image);
						if(file_exists($temp_path)) :
							$image_path = storage_path('app/accounts/' . $request->image);
							\File::move($temp_path, $image_path);
							$account->image = $request->image;
						endif;
					endif;	

					if(is_null($request->image) || (isset($request->image) && ($account_image != $request->image))) :
						if(!is_null($account_image)) :
							\Storage::disk('base')->delete($account_image_path);
						endif;	
					endif;

					$account->update();

					if($request->access != 'private') :
						$account->allowedstaffs()->forceDelete();
					endif;					
				else :
					$status = false;
					$errors = $validation->getMessageBag()->toArray();
				endif;
			else :
				$status = false;
			endif;

			return response()->json(['status' => $status, 'errors' => $errors, 'tabTable' => '#sub-account', 'saveId' => $request->id]);
		endif;
	}



	public function singleUpdate(Request $request, Account $account)
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

			if(isset($account) && $account->auth_can_edit) :
				$data['id'] = $account->id;
				$data['change_owner'] = (isset($request->account_owner) && $account->auth_can_change_owner);
				$validation = Account::singleValidate($data);
				if($validation->passes()) :	
					if(isset($request->parent_id)) :
						$parent_account = Account::find($request->parent_id);
						if(non_property_checker($parent_account, 'parent_id') == $account->id) :
							$parent_account->update(['parent_id' => $account->parent_id]);
						endif;
					endif;
						
					$update_data = replace_null_if_empty($request->all());
					$account->update($update_data);

					$media = single_request_field($request, ['facebook', 'twitter', 'skype']);
					if(isset($media)) :
						$account->socialmedia()->whereMedia($media)->forceDelete();
						$account->socialmedia()->create(['media' => $media, 'data' => json_encode(['link' => $request->$media])]);
						
						if($media == 'skype') :
							$html = non_property_checker($account->getSocialDataAttribute($media), 'link');
						else :	
							$html = "<a href='" . $account->getSocialLinkAttribute($media) . "' target='_blank'>" . non_property_checker($account->getSocialDataAttribute($media), 'link') . "</a>";
						endif;
					endif;

					if(isset($request->access)) :
						$html = $account->access_html;

						if($request->access != 'private') :
							$account->allowedstaffs()->forceDelete();
						endif;	
					endif;	

					if(isset($request->currency_id)) :
						$html = "<span class='symbol'>" . $account->currency->symbol . "</span> " . $account->amountFormat('annual_revenue');
						$real_replace[] = ['span.symbol.none', $account->hidden_currency_info];
					endif;	

					if(isset($request->website)) :
						$html = "<a href='" . quick_url($account->website) . "' target='_blank'>" . $account->website . "</a>";
					endif;	

					$updated_by = "<p class='compact'>" . $account->updatedByName() . "<br><span class='c-shadow sm'>" . $account->updated_ampm . "</span></p>";
					$last_modified = "<p data-toggle='tooltip' data-placement='bottom' title='" . $account->readableDateAmPm('modified_at') . "'>" . time_short_form($account->modified_at->diffForHumans()) . "</p>";
					$modal_title = $account->name;
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



	public function destroy(Request $request, Account $account)
	{
		if($request->ajax()) :
			$status = true;
			$tab_table = '#sub-account';

			if($account->id != $request->id) :
				$status = false;
			endif;

			if($status == true) :
				$account->delete();
				flush_response(['status' => true, 'tabTable' => $tab_table]);
				event(new \App\Events\AccountDeleted([$request->id]));
			endif;
			
			return response()->json(['status' => $status, 'tabTable' => $tab_table]);
		endif;
	}



	public function bulkUpdate(Request $request)
	{
		if($request->ajax()) :
			$accounts = $request->accounts;
			$status = true;
			$errors = null;
			$data = $request->all();

			if(isset($accounts) && count($accounts) > 0 && isset($request->related)) :
				$validation = Account::massValidate($data);

				if($validation->passes()) :
					$account_ids = Account::whereIn('id', $accounts)->get()->where('auth_can_edit', true)->pluck('id')->toArray();
					$accounts = Account::whereIn('id', $account_ids);

					if(\Schema::hasColumn('accounts', $request->related)) :
						$field = $request->related;
						$update_data = [$field => null_if_empty($request->$field)];

						if($request->related == 'annual_revenue') :
							$update_data['currency_id'] = $request->currency_id;
						endif;	

						if($request->related == 'parent_id' && not_null_empty($request->parent_id)) :
							$parent_account = Account::find($request->parent_id);
							if(in_array($parent_account->parent_id, $account_ids)) :
								$account = Account::find($parent_account->parent_id);
								if(in_array($account->parent_id, $account_ids)) :
									$closest_parents = array_diff($account->root_parent_hierarchy, $account_ids);
									if(count($closest_parents)) :
										$closest_parent = array_shift($closest_parents);
										$parent_account->update(['parent_id' => $closest_parent]);
									else :
										$parent_account->update(['parent_id' => null]);
									endif;
								else :
									$parent_account->update(['parent_id' => $account->parent_id]);
								endif;								
							endif;	

							$accounts = $accounts->where('id', '!=', $request->parent_id);
						endif;	

						$accounts->update($update_data);
					endif;	

					if(in_array($request->related, ['facebook', 'twitter', 'skype'])) :
						$media = $request->related;
						foreach($accounts->get() as $account) :
							$account->socialmedia()->whereMedia($media)->forceDelete();
							$account->socialmedia()->create(['media' => $media, 'data' => json_encode(['link' => $request->$media])]);
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
			$accounts = $request->accounts;

			$status = true;

			if(isset($accounts) && count($accounts) > 0) :
				$account_ids = Account::whereIn('id', $accounts)->get()->where('auth_can_delete', true)->pluck('id')->toArray();
				Account::whereIn('id', $account_ids)->delete();
				flush_response(['status' => $status]);
				event(new \App\Events\AccountDeleted($account_ids));
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
				$ids = $request->accounts;
				if(isset($ids) && count($ids) > 0) :
					$emails = Account::whereIn('id', $ids)->pluck('account_email')->toArray();
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



	public function hierarchy(Request $request, Account $account)
	{
		$hierarchy = $account->hierarchy_chart_format;
		return response()->json($hierarchy);
	}



	public function accountSingleData(Request $request)
	{
		if($request->ajax()) :
			$id = $request->id;
			$account = Account::find($id);

			$status = false;
			$error = null;
			$info = [];
			$info['account-name'] = '';
			$info['address-line-first'] = '';
			$info['address-line-second'] = '';
			$info['account-phone'] = '';
			$info['account-email'] = '';
			$currency = '';
			$currency_icon = null;
			$currency_symbol = null;

			if(isset($account)) :
				$status = true;
				$info['account-name'] = $account->account_name;
				$info['address-line-first'] = $account->street . ', ' . $account->city;
				$info['address-line-second'] = $account->state . ', ' . $account->country->ascii_name;
				$info['account-phone'] = $account->account_phone;
				$info['account-email'] = $account->account_email;
				$currency = $account->currency_id;
				$currency_icon = currency_icon($account->currency->code, $account->currency->symbol);
				$currency_symbol = $account->currency->symbol;
			else:
				$error = 'Account not found.';	
			endif;
			
			return response()->json(['status' => $status, 'info' => $info, 'currency' => $currency, 'currencyIcon' => $currency_icon, 'currencySymbol' => $currency_symbol, 'error' => $error]);	
		endif;

		return redirect()->route('admin.account.index');
	}



	public function projectsList(Request $request)
	{
		if($request->ajax()) :
			$id = $request->id;
			$projects = Project::whereAccount_id($id)->get(['id', 'name'])->pluck('name', 'id');

			$status = false;
			$error = null;

			if(isset($projects)) :
				$status = true;
			else:
				$error = 'Project not found.';
			endif;
			
			return response()->json(['status' => $status, 'optionList' => $projects, 'error' => $error]);
		endif;
		
		return redirect()->route('admin.account.index');
	}
}
