<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Role;
use App\Models\Staff;
use App\Models\Account;
use App\Models\Contact;
use App\Models\AllowedStaff;
use Illuminate\Http\Request;
use App\Library\DatatablesManager;
use App\Http\Controllers\Admin\AdminBaseController;

class AdminContactController extends AdminBaseController
{
	public function __construct()
	{
		parent::__construct();

		$this->middleware('admin:contact.view', ['only' => ['index', 'contactData', 'show']]);
		$this->middleware('admin:contact.create', ['only' => ['store']]);
		$this->middleware('admin:contact.edit', ['only' => ['edit', 'update', 'updateStatus', 'confirmAccount']]);
		$this->middleware('admin:contact.delete', ['only' => ['destroy', 'bulkDestroy']]);
	}



	public function index()
	{
		$page = ['title' => 'Contacts List', 'item' => 'Contact', 'field' => 'contacts', 'view' => 'admin.contact', 'route' => 'admin.contact', 'permission' => 'contact', 'script' => true, 'import' => permit('import.contact'), 'add_icon' => 'fa fa-user-plus', 'bulk' => 'email,sms,update', 'mass_update_permit' => permit('mass_update.contact'), 'mass_del_permit' => permit('mass_delete.contact')];
		$table = ['thead' => ['name', 'phone', ['open&nbsp;deals&nbsp;amt', 'tooltip' => 'Open deals amount'], 'email', 'last&nbsp;login', ['status', 'orderable' => 'false', 'data_class' => 'center'], 'owner'], 'checkbox' => Contact::allowMassAction(), 'action' => Contact::allowAction()];
		$table['json_columns'] = table_json_columns(['checkbox', 'name', 'phone', 'open_deals_amount' => ['className' => 'align-r'], 'email' => ['className' => 'align-l-space'], 'last_login', 'status', 'contact_owner', 'action'], Contact::hideColumns());

		return view('admin.contact.index', compact('page', 'table'));
	}



	public function contactData(Request $request)
	{
		if($request->ajax()) :
			$contacts = Contact::getAuthViewData()
								->select(['id', 'account_id', 'currency_id', 'contact_owner', 'first_name', 'last_name', 'phone', 'title'])
								->latest('id')
								->get();
								
			return DatatablesManager::contactData($contacts, $request);
		endif;
	}



	public function reportingContactData(Request $request, Contact $contact)
	{
		if($request->ajax()) :
			$contacts = $contact->childContacts()
								->select(['id', 'account_id', 'currency_id', 'contact_owner', 'first_name', 'last_name', 'phone', 'title'])
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
			$validation = Contact::validate($data);

			if($validation->passes()) :
				$contact = new Contact;
				$contact->contact_owner = $request->contact_owner;
				$contact->account_id = $request->account_id;
				$contact->parent_id = null_if_empty($request->supervisor);
				$contact->first_name = null_if_empty($request->first_name);
				$contact->last_name  = $request->last_name;
				$contact->date_of_birth = null_if_empty($request->date_of_birth);
				$contact->title = null_if_empty($request->title);
				$contact->phone = null_if_empty($request->phone);
				$contact->fax = null_if_empty($request->fax);
				$contact->source_id = null_if_empty($request->source_id);
				$contact->contact_type_id = null_if_empty($request->contact_type_id);
				$contact->currency_id = $request->currency_id;
				$contact->annual_revenue = $request->annual_revenue;
				$contact->street = null_if_empty($request->street);
				$contact->city = null_if_empty($request->city);
				$contact->state = null_if_empty($request->state);
				$contact->zip = null_if_empty($request->zip);
				$contact->country_code = null_if_empty($request->country_code);
				$contact->description = null_if_empty($request->description);
				$contact->access = $request->access;

				if(isset($request->image) && !empty($request->image)) :
					$temp_path = storage_path('app/temp/' . $request->image);
					if(file_exists($temp_path)) :
						$image_path = storage_path('app/contacts/' . $request->image);
						\File::move($temp_path, $image_path);
						$contact->image = $request->image;
					endif;	
				endif;	

				$contact->save();

				$user = new User;				
				$user->email = $request->email;				
				$user->password = bcrypt($request->password);
				$user->linked_id = $contact->id;
				$user->linked_type = 'contact';
				$user->save();	

				$roles = [];
				$client_modules = Role::getClientModule();
				foreach($client_modules as $client_module) :
					$field = $client_module . '_role';
					if(isset($request->$field)) :
						$roles[] = $request->$field;
					endif;	
				endforeach;
				$user->roles()->attach($roles);	

				if($request->access == 'private') :
					if(isset($request->staffs) && count($request->staffs) > 0) :
						foreach($request->staffs as $staff_id) :
								$staff = Staff::find($staff_id);

								if(!is_null($staff)) :
									$allowed_staff = new AllowedStaff;
									$allowed_staff->staff_id = $staff_id;
									$allowed_staff->linked_id = $contact->id;
									$allowed_staff->linked_type = 'contact';
									$allowed_staff->can_edit = isset($request->can_write) ? 1 : 0;
									$allowed_staff->can_delete = isset($request->can_delete) ? 1 : 0;
									$allowed_staff->save();
								endif;
						endforeach;	
					endif;
				endif;	

				$notification = notification_log('contact_created', 'contact', $contact->id, 'staff', $request->contact_owner);
			else :
				$status = false;
				$errors = $validation->getMessageBag()->toArray();
			endif;

			return response()->json(['status' => $status, 'errors' => $errors, 'tabTable' => '#append-contact', 'notification' => $notification]);
		endif;
	}



	public function show(Contact $contact, $infotype = null)
	{
		$page = ['title' => 'Contact: ' . $contact->name, 'item_title' => breadcrumbs_render("admin.contact.index:Contacts|<span data-realtime='first_name'>" . $contact->name . "</span>"), 'item' => 'Contact', 'view' => 'admin.contact', 'tabs' => ['list' => Contact::informationTypes(), 'default' => Contact::defaultInfoType($infotype), 'item_id' => $contact->id, 'url' => 'tab/contact']];
		return view('admin.contact.show', compact('page', 'contact'));
	}



	public function edit(Request $request, Contact $contact)
	{
		if($request->ajax()) :
			$status = true;
			$info = null;
			$html = null;

			if(isset($contact) && isset($request->id) && $contact->auth_can_edit) :
				if($contact->id == $request->id) :
					$info = $contact->toArray();
					$info['email'] = $contact->email;

					$info['freeze'] = [];

					if(!$contact->auth_can_change_owner) :
						$info['freeze'][] = 'contact_owner';
					endif;	

					if(is_null($contact->deal_role)) :
						$info['freeze'][] = 'deal_role';
					endif;	

					if(is_null($contact->project_role)) :
						$info['freeze'][] = 'project_role';
					endif;	

					if(is_null($contact->estimate_role)) :
						$info['freeze'][] = 'estimate_role';
					endif;	

					if(is_null($contact->invoice_role)) :
						$info['freeze'][] = 'invoice_role';
					endif;	

					$info['modal_image'] = $contact->getAvatarAttribute(true);

					$info = ['invalid_parent_id' => $contact->id] + $info;

					$info = (object)$info;

					if(isset($request->html)) :
						$html = view('admin.contact.partials.form', ['form' => 'edit'])->render();
					endif;
				else :
					$status = false;
				endif;
			else :
				$status = false;
			endif;

			return response()->json(['status' => $status, 'info' => $info, 'html' => $html]);
		endif;

		return redirect()->route('admin.contact.index');
	}



	public function update(Request $request, Contact $contact)
	{
		if($request->ajax()) :
			$status = true;
			$errors = null;
			$data = $request->all();			

			if(isset($contact) && isset($request->id) && $contact->id == $request->id && $contact->auth_can_edit) :
				$data['user_id'] = $contact->user->id;
				$data['change_owner'] = $contact->auth_can_change_owner;
				$validation = Contact::validate($data);
				if($validation->passes()) :
					if($contact->auth_can_change_owner) :
						$contact->contact_owner = $request->contact_owner;
					endif;	

					if(not_null_empty($request->supervisor)) :
						$supervisor = Contact::find($request->supervisor);
						if(non_property_checker($supervisor, 'parent_id') == $contact->id) :
							$supervisor->update(['parent_id' => $contact->parent_id]);
						endif;
					endif;

					$contact->account_id = $request->account_id;
					$contact->parent_id = null_if_empty($request->supervisor);
					$contact->first_name = null_if_empty($request->first_name);
					$contact->last_name  = $request->last_name;
					$contact->date_of_birth = null_if_empty($request->date_of_birth);
					$contact->title = null_if_empty($request->title);
					$contact->phone = null_if_empty($request->phone);
					$contact->fax = null_if_empty($request->fax);
					$contact->source_id = null_if_empty($request->source_id);
					$contact->contact_type_id = null_if_empty($request->contact_type_id);
					$contact->currency_id = $request->currency_id;
					$contact->annual_revenue = $request->annual_revenue;
					$contact->street = null_if_empty($request->street);
					$contact->city = null_if_empty($request->city);
					$contact->state = null_if_empty($request->state);
					$contact->zip = null_if_empty($request->zip);
					$contact->country_code = null_if_empty($request->country_code);
					$contact->description = null_if_empty($request->description);
					$contact->access = $request->access;
					$contact_image = $contact->image;
					$contact_image_path = $contact->image_path;

					if(isset($request->image) && ($contact->image != $request->image)) :
						$temp_path = storage_path('app/temp/' . $request->image);
						if(file_exists($temp_path)) :
							$image_path = storage_path('app/contacts/' . $request->image);
							\File::move($temp_path, $image_path);
							$contact->image = $request->image;
						endif;
					endif;	

					if(is_null($request->image) || (isset($request->image) && ($contact_image != $request->image))) :
						if(!is_null($contact_image)) :
							\Storage::disk('base')->delete($contact_image_path);
						endif;	
					endif;

					$contact->update();

					if($request->access != 'private') :
						$contact->allowedstaffs()->forceDelete();
					endif;

					$user = $contact->user;				
					$user->email = $request->email;	
					if(isset($request->password) && $request->password != '') :			
						$user->password = bcrypt($request->password);
					endif;	
					$user->update();

					$roles = [];
					$client_modules = Role::getClientModule();
					foreach($client_modules as $client_module) :
						$field = $client_module . '_role';
						if(isset($request->$field)) :
							$roles[] = $request->$field;
						endif;	
					endforeach;

					if(count($roles)) :
						$user->roles()->sync($roles);
					else :
						$user->roles()->detach();
					endif;
				else :
					$status = false;
					$errors = $validation->getMessageBag()->toArray();
				endif;
			else :
				$status = false;
			endif;

			return response()->json(['status' => $status, 'errors' => $errors, 'tabTable' => '#append-contact', 'saveId' => $request->id]);
		endif;
	}



	public function singleUpdate(Request $request, Contact $contact)
	{
		if($request->ajax()) :
			$status = true;
			$html = null;
			$realtime = [];
			$real_replace = [];
			$inner_html = [];
			$updated_by = null;
			$last_modified = null;
			$modal_title = null;
			$errors = null;
			$data = $request->all();

			if(isset($contact) && $contact->auth_can_edit) :
				$data['id'] = $contact->id;
				$data['change_owner'] = (isset($request->contact_owner) && $contact->auth_can_change_owner);
				$validation = Contact::singleValidate($data, $contact);
				if($validation->passes()) :	
					if(isset($request->parent_id)) :
						$supervisor = Contact::find($request->parent_id);
						if(isset($supervisor) && $supervisor->parent_id == $contact->id) :
							$supervisor->update(['parent_id' => $contact->parent_id]);
						endif;
						$inner_html[] = ["select[name='parent_id']", option_attr_render($contact->parent_contacts_list, $request->parent_id), false];
					endif;
						
					$update_data = replace_null_if_empty($request->all());					
					$contact->update($update_data);

					$user = $contact->user;
					if($request->email) :
						$user->update(['email' => $request->email]);
					endif;	

					$media = single_request_field($request, ['facebook', 'twitter', 'skype']);
					if(isset($media)) :
						$contact->socialmedia()->whereMedia($media)->forceDelete();
						$contact->socialmedia()->create(['media' => $media, 'data' => json_encode(['link' => $request->$media])]);
						
						if($media == 'skype') :
							$html = non_property_checker($contact->getSocialDataAttribute($media), 'link');
						else :	
							$html = "<a href='" . $contact->getSocialLinkAttribute($media) . "' target='_blank'>" . non_property_checker($contact->getSocialDataAttribute($media), 'link') . "</a>";
						endif;
					endif;

					if(isset($request->access)) :
						$html = $contact->access_html;

						if($request->access != 'private') :
							$contact->allowedstaffs()->forceDelete();
						endif;	
					endif;	

					if(isset($request->first_name)) :
						$html = $contact->name;
					endif;

					if(isset($request->date_of_birth)) :
						$html = not_null_empty($contact->date_of_birth) ? $contact->readableDate('date_of_birth') : '';
					endif;

					if(isset($request->currency_id)) :
						$html = "<span class='symbol'>" . $contact->currency->symbol . "</span> " . $contact->amountFormat('annual_revenue');
						$real_replace[] = ['span.symbol.none', $contact->hidden_currency_info];
					endif;	

					if(isset($request->website)) :
						$html = "<a href='" . quick_url($contact->website) . "' target='_blank'>" . $contact->website . "</a>";
					endif;	

					if(isset($request->client_permission) && $request->client_permission) :
						$roles = [];
						$client_modules = Role::getClientModule();
						foreach($client_modules as $client_module) :
							$field = $client_module . '_role';
							if(isset($request->$field)) :
								$roles[] = $request->$field;
							endif;	
						endforeach;

						if(count($roles)) :
							$user->roles()->sync($roles);
						else :
							$user->roles()->detach();
						endif;
					endif;	

					$updated_by = "<p class='compact'>" . $contact->updatedByName() . "<br><span class='c-shadow sm'>" . $contact->updated_ampm . "</span></p>";
					$last_modified = "<p data-toggle='tooltip' data-placement='bottom' title='" . $contact->readableDateAmPm('modified_at') . "'>" . time_short_form($contact->modified_at->diffForHumans()) . "</p>";
					$modal_title = $contact->complete_name;
				else :
					$status = false;
					$errors = $validation->getMessageBag()->toArray();
				endif;
			else :
				$status = false;
			endif;

			return response()->json(['status' => $status, 'html' => $html, 'updatedBy' => $updated_by, 'lastModified' => $last_modified, 'modalTitle' => $modal_title, 'realtime' => $realtime, 'realReplace' => $real_replace, 'innerHtml' => $inner_html, 'errors' => $errors]);
		endif;
	}	



	public function confirmAccount(Request $request, Contact $contact)
	{
		if($request->ajax()) :
			$status = true;
			$html = null;
			$list = null;
			$value = [];
			$errors = null;
			$data = $request->all();

			if(isset($contact) && isset($request->id) && $contact->id == $request->id) :
				$validation = Contact::moveAccountValidate($data);
				if($validation->passes()) :
					if($request->confirmation == 'new') :
						$assign_contact = null_if_empty($request->assign_contact);

						if($request->deal) :
							foreach($request->deal_categories as $deal_category) :
								$category_ids = \App\Models\DealStage::getCategoryIds($deal_category);
								\DB::table('deals')->where('contact_id', $contact->id)
												   ->where('account_id', $contact->account_id)
												   ->whereIn('deal_stage_id', $category_ids)
												   ->update(['contact_id' => $assign_contact]);
							endforeach;
						endif;

						if($request->estimate) :
							\DB::table('estimates')
								->where('contact_id', $contact->id)
								->where('account_id', $contact->account_id)
								->update(['contact_id' => $assign_contact]);
						endif;

						if($request->invoice) :
							\DB::table('invoices')
								->where('contact_id', $contact->id)
								->where('account_id', $contact->account_id)
								->update(['contact_id' => $assign_contact]);
						endif;

						if($request->project) :
							$projects = \DB::table('project_contact')
											->join('projects', 'projects.id', '=', 'project_contact.project_id')
											->where('contact_id', $contact->id)
											->where('account_id', $contact->account_id);

							if(is_null($assign_contact)) :
								$projects->delete();
							else :
								$projects->update(['contact_id' => $request->assign_contact]);
							endif;								
						endif;
					endif;

					$contact->childContacts()->update(['parent_id' => $contact->parent_id]);

					$contact->account_id = $request->account_id;
					$contact->parent_id = null;
					$contact->update();

					$value = $request->account_id;
					$html = $contact->account_name;
					$list = $contact->assign_new_contact;
				else :
					$status = false;
					$errors = $validation->getMessageBag()->toArray();
				endif;
			else :
				$status = false;
			endif;

			return response()->json(['status' => $status, 'html' => $html, 'val' => $value, 'list' => $list, 'errors' => $errors]);
		endif;
	}



	public function destroy(Request $request, Contact $contact)
	{
		if($request->ajax()) :
			$status = true;
			$tab_table = '#append-contact';

			if($contact->id != $request->id || !$contact->auth_can_delete) :
				$status = false;
			endif;

			if($status == true) :
				$contact->user->update(['status' => 0]);
				$contact->user->delete();
				$contact->delete();
				flush_response(['status' => true, 'tabTable' => $tab_table]);
				event(new \App\Events\ContactDeleted([$request->id]));
			endif;	
			
			return response()->json(['status' => $status, 'tabTable' => $tab_table]);
		endif;
	}



	public function bulkUpdate(Request $request)
	{
		if($request->ajax()) :
			$contacts = $request->contacts;
			$status = true;
			$errors = null;
			$data = $request->all();

			if(isset($contacts) && count($contacts) > 0 && isset($request->related)) :
				$validation = Contact::massValidate($data);

				if($validation->passes()) :
					$contact_ids = Contact::whereIn('id', $contacts)->get()->where('auth_can_edit', true)->pluck('id')->toArray();
					$contacts = Contact::whereIn('id', $contact_ids);

					if(\Schema::hasColumn('contacts', $request->related)) :
						$field = $request->related;
						$update_data = [$field => null_if_empty($request->$field)];

						if($request->related == 'annual_revenue') :
							$update_data['currency_id'] = $request->currency_id;
						endif;	

						if($request->related == 'parent_id' && not_null_empty($request->parent_id)) :
							$supervisor = Contact::find($request->parent_id);
							if(in_array($supervisor->parent_id, $contact_ids)) :
								$contact = Contact::find($supervisor->parent_id);
								if(in_array($contact->parent_id, $contact_ids)) :
									$closest_parents = array_diff($contact->root_parent_hierarchy, $contact_ids);
									if(count($closest_parents)) :
										$closest_parent = array_shift($closest_parents);
										$supervisor->update(['parent_id' => $closest_parent]);
									else :
										$supervisor->update(['parent_id' => null]);
									endif;
								else :
									$supervisor->update(['parent_id' => $contact->parent_id]);
								endif;								
							endif;	

							$contacts = $contacts->where('id', '!=', $request->parent_id)->where('account_id', $supervisor->account_id);
						endif;	

						$contacts->update($update_data);
					endif;	

					if($request->related == 'status') :
						User::onlyContact()->whereIn('linked_id', $contact_ids)->update(['status' => $request->status]);
					endif;	

					if(in_array($request->related, ['facebook', 'twitter', 'skype'])) :
						$media = $request->related;
						foreach($contacts->get() as $contact) :
							$contact->socialmedia()->whereMedia($media)->forceDelete();
							$contact->socialmedia()->create(['media' => $media, 'data' => json_encode(['link' => $request->$media])]);
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
			$contacts = $request->contacts;

			$status = true;

			if(isset($contacts) && count($contacts) > 0) :
				$contact_ids = Contact::whereIn('id', $contacts)->get()->where('auth_can_delete', true)->pluck('id')->toArray();
				
				User::where('linked_type', 'contact')->whereIn('linked_id', $contact_ids)->update(['status' => 0]);
				User::where('linked_type', 'contact')->whereIn('linked_id', $contact_ids)->delete();
				Contact::whereIn('id', $contact_ids)->delete();

				flush_response(['status' => $status]);
				event(new \App\Events\ContactDeleted($contact_ids));
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
				$ids = $request->contacts;
				if(isset($ids) && count($ids) > 0) :
					$emails = User::where('linked_type', 'contact')->whereIn('linked_id', $ids)->pluck('email')->toArray();
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



	public function updateStatus(Request $request)
	{
		if($request->ajax()) :
			$status = false;
			$checked = null;

			if(isset($request->id) && isset($request->checked)) :
				$contact = Contact::whereId($request->id)->first();
				if(isset($contact) && $contact->auth_can_edit) :
					$checked = $request->checked ? 1 : 0;
					$contact->user->update(['status' => $checked]);
					$status = true;
				endif;
			endif;

			return response()->json(['status' => $status, 'checked' => $checked]);
		endif;
	}




	public function participantContactData(Request $request, $module_name, $module_id)
	{
		if($request->ajax()) :
			$module = morph_to_model($module_name)::find($module_id);
			if(isset($module)) :
				$contacts = $module->participants()->groupBy('participant_contacts.contact_id')->latest('participant_contacts.id')->get();
				return Contact::getParticipantData($request, $contacts, $module->account_id, true);
			endif;
			
			return null;	
		endif;
	}



	public function participantSelect(Request $request, $module_name = null, $module_id = null)
	{
		if($request->ajax()) :
			$where_not_in = [];

			if(isset($module_name) && isset($module_id)) :
				$module = morph_to_model($module_name)::find($module_id);
				if(isset($module)) :
					$where_not_in = $module->participants->pluck('id')->toArray();
				endif;	
			endif;

			$contacts = Contact::whereNotIn('id', $where_not_in)->latest('id')->get();
				
			return Contact::getParticipantData($request, $contacts, $module->account_id, false, true, true);
		endif;
	}



	public function participantAdd(Request $request, $module_name, $module_id)
	{
		if($request->ajax()) :
			$status = false;
			$errors = null;
			$data = $request->all();
			$tab_table = '#' . $module_name . '-participant';
			$validation = Contact::participantValidate($data);
			$module = morph_to_model($module_name)::find($module_id);			

			if(isset($module) && $module_id == $request->module_id && $module_name == $request->module_name) :
				if($validation->passes()) :
					$status = true;
					$data = [];
					
					if(count($request->contacts)) :
						foreach($request->contacts as $contact_id) :
							$participant_exists = $module->participants()->where('contact_id', $contact_id)->count();

							if(!$participant_exists) :
								$data = ['contact_id' => $contact_id, 'linked_id' => $module_id, 'linked_type' => $module_name];
								\DB::table('participant_contacts')->insert($data);
							endif;
						endforeach;
					endif;	
				else :
					$errors = $validation->getMessageBag()->toArray();				
				endif;	
			else :
				$errors['module_id'] = 'Invalid module';	
			endif;	

			return response()->json(['status' => $status, 'errors' => $errors, 'tabTable' => $tab_table]);
		endif;
	}



	public function participantRemove(Request $request, $module_name, $module_id, Contact $contact)
	{
		if($request->ajax()) :
			$module = morph_to_model($module_name)::find($module_id);
			$tab_table = '#' . $module_name . '-participant';
			$status = false;

			if(isset($module) && isset($contact) && $module_id == $request->module_id && $module_name == $request->module_name && $contact->id == $request->contact_id) :
				$module->participants()->detach($contact->id);				
				$status = true;
			endif;	

			return response()->json(['status' => $status, 'tabTable' => $tab_table]);
		endif;
	}
}
