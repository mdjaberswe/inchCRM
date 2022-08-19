<?php

namespace App\Http\Composers;

use Session;
use Carbon\Carbon;
use App\Models\Role;
use App\Models\User;
use App\Models\Lead;
use App\Models\Deal;
use App\Models\Task;
use App\Models\Event;
use App\Models\Staff;
use App\Models\Source;
use App\Models\Country;
use App\Models\Account;
use App\Models\Contact;
use App\Models\Invoice;
use App\Models\Project;
use App\Models\Currency;
use App\Models\DealType;
use App\Models\Campaign;
use App\Models\Estimate;
use App\Models\DealStage;
use App\Models\LeadStage;
use App\Models\TaskStatus;
use App\Models\Permission;
use App\Models\ContactType;
use App\Models\AccountType;
use App\Models\DealPipeline;
use App\Models\IndustryType;
use App\Models\CampaignType;
use App\Models\PaymentMethod;
use App\Models\ExpenseCategory;

class AdminViewComposer
{
	public function compose($view)
	{
		$class = get_layout_status();

		$unread_notifications_count = auth_staff()->unread_notifications_count;
		$take = $unread_notifications_count > 15 ? $unread_notifications_count : 15;		
		$notifications = auth_staff()->notifications->sortByDesc('id')->take($take);

		$unread_messages_count = auth_staff()->unread_messages_count;
		$take_messages = $unread_messages_count > 15 ? $unread_messages_count : 15;
		$chat_messages = auth_staff()->getChatRoomsAttribute($take_messages);

		$base_currency = Currency::getBase();

		$view->with(compact('class', 'unread_notifications_count', 'notifications', 'unread_messages_count', 'chat_messages', 'base_currency'));
	}



	public function userForm($view)
	{
		$roles_list = Role::onlyGeneral()->orderBy('id')->get(['id', 'display_name'])->pluck('display_name', 'id')->toArray();
		$receivers_list = Staff::orderBy('id')->whereNotIn('id', [auth_staff()->id])->get(['id', 'first_name', 'last_name'])->where('status', 1)->pluck('name', 'id')->toArray();
		$view->with(compact('roles_list', 'receivers_list'));
	}



	public function userInformation($view)
	{
		$roles_list = Role::onlyGeneral()->orderBy('id')->get(['id', 'display_name'])->pluck('display_name', 'id')->toArray();
		$countries_list = ['' => '-None-'] + Country::orderBy('ascii_name')->get(['id', 'code', 'ascii_name'])->pluck('ascii_name', 'code')->toArray();
		$employee_type_list = ['' => 'None', 'full_time' => 'Full Time', 'part_time' => 'Part Time', 'casual' => 'Casual', 'fixed_term' => 'Fixed Term', 'probation' => 'Probation'];	

		$projects_json_column = table_json_columns(['name', 'completion_percentage', 'project_owner', 'member', 'tasks', 'milestones', 'issues', 'date']);
		$projects_table = ['json_columns' => $projects_json_column, 'thead' => ['PROJECT NAME', 'PROGRESS', 'OWNER', 'MEMBERS', 'TASKS', 'MILESTONES', 'ISSUES', 'DATE'], 'checkbox' => false, 'action' => false];

		$tasks_json_column = table_json_columns(['name', 'task_owner', 'completion_percentage', 'date']);
		$tasks_table = ['json_columns' => $tasks_json_column, 'thead' => ['NAME', 'TASK OWNER', 'PROGRESS', 'DATE'], 'checkbox' => false, 'action' => false];
	
		$view->with(compact('roles_list', 'countries_list', 'employee_type_list', 'projects_table', 'tasks_table'));
	}



	public function accessModal($view)
	{
		$admin_users_list = $this->getAdminUsersList();
		$view->with(compact('admin_users_list'));
	}



	public function leadForm($view)
	{
		$admin_users_list = $this->getAdminUsersList();
		$sources_list = ['' => '-None-'] + Source::orderBy('position')->get(['id', 'name'])->pluck('name', 'id')->toArray();
		$lead_stages_list = ['' => '-None-'] + LeadStage::orderBy('position')->get(['id', 'name'])->pluck('name', 'id')->toArray();
		$countries_list = ['' => '-None-'] + Country::orderBy('ascii_name')->get(['id', 'code', 'ascii_name'])->pluck('ascii_name', 'code')->toArray();
		$lead_field_list = ['' => '-Select a field-', 'annual_revenue' => 'Annual Revenue', 'city' => 'City', 'company' => 'Company', 'country_code' => 'Country', 'facebook' => 'Facebook', 'fax' => 'Fax', 'title' => 'Job Title', 'source_id' => 'Lead Source', 'lead_stage_id' => 'Lead Stage', 'no_of_employees' => 'No. of Employees', 'phone' => 'Phone', 'skype' => 'Skype', 'state' => 'State', 'street' => 'Street', 'twitter' => 'Twitter', 'website' => 'Website', 'zip' => 'Zip Code'];
		$currency_list = Currency::dropdownList();
		$base_currency = Currency::getBase();

		$view->with(compact('admin_users_list', 'sources_list', 'lead_stages_list', 'lead_field_list', 'currency_list', 'base_currency', 'countries_list'));
	}



	public function leadInformation($view)
	{
		$lead_hide_details = (Session::has('lead_hide_details') && Session::get('lead_hide_details')) ? true : false;	
		$admin_users_list = $this->getAdminUsersList();
		$sources_list = ['' => '-None-'] + Source::orderBy('position')->get(['id', 'name'])->pluck('name', 'id')->toArray();
		$lead_stages_list = ['' => '-None-'] + LeadStage::orderBy('position')->get(['id', 'name'])->pluck('name', 'id')->toArray();
		$countries_list = ['' => '-None-'] + Country::orderBy('ascii_name')->get(['id', 'code', 'ascii_name'])->pluck('ascii_name', 'code')->toArray();
		$access_list = ['private' => 'Private', 'public' => 'Public Read Only', 'public_rwd' => 'Public Read/Write/Delete'];
		$currency_list = Currency::dropdownList();
		$base_currency = Currency::getBase();

		$view->with(compact('lead_hide_details', 'admin_users_list', 'sources_list', 'lead_stages_list', 'access_list', 'currency_list', 'base_currency', 'countries_list'));
	}



	public function contactInformation($view)
	{
		$contact_hide_details = (Session::has('contact_hide_details') && Session::get('contact_hide_details')) ? true : false;	
		$contacts_json_column = table_json_columns(['name', 'phone', 'open_deals_amount', 'email', 'status' , 'action']);
		$contacts_table = ['json_columns' => $contacts_json_column, 'thead' => ['CONTACT NAME', 'PHONE', 'OPEN DEALS AMOUNT', 'EMAIL', 'STATUS'], 'checkbox' => false];
		$admin_users_list = $this->getAdminUsersList();
		$accounts_list = $this->getAccountsList();
		$sources_list = ['' => '-None-'] + Source::orderBy('position')->get(['id', 'name'])->pluck('name', 'id')->toArray();
		$contact_types_list = ['' => '-None-'] + ContactType::orderBy('position')->get(['id', 'name'])->pluck('name', 'id')->toArray();
		$countries_list = ['' => '-None-'] + Country::orderBy('ascii_name')->get(['id', 'code', 'ascii_name'])->pluck('ascii_name', 'code')->toArray();
		$access_list = ['private' => 'Private', 'public' => 'Public Read Only', 'public_rwd' => 'Public Read/Write/Delete'];
		$client_roles = Role::getClientRoleMap();
		$currency_list = Currency::dropdownList();
		$base_currency = Currency::getBase();

		$view->with(compact('contact_hide_details', 'contacts_table', 'admin_users_list', 'accounts_list', 'sources_list', 'contact_types_list', 'access_list', 'client_roles', 'currency_list', 'base_currency', 'countries_list'));
	}



	public function tab($view)
	{
		$at_who_data = Staff::atWhoData();
		
		$deals_json_column = table_json_columns(['name', 'amount', 'closing_date', 'pipeline', 'deal_stage', 'deal_owner', 'action']);
		$deals_table = ['json_columns' => $deals_json_column, 'thead' => ['DEAL NAME', 'AMOUNT', 'CLOSING DATE', 'PIPELINE', 'STAGE', 'OWNER'], 'checkbox' => false];

		$projects_json_column = table_json_columns(['name', 'completion_percentage', 'tasks', 'milestones', 'issues', 'start_date', 'end_date', 'project_owner', 'action']);
		$projects_table = ['json_columns' => $projects_json_column, 'thead' => ['PROJECT NAME', 'PROGRESS', 'TASKS', 'MILESTONES', 'ISSUES', 'START DATE', 'END DATE', 'OWNER'], 'checkbox' => false];

		$campaigns_json_column = table_json_columns(['name', 'type', 'status', 'start_date', 'end_date', 'expected_revenue', 'budgeted_cost', 'member_status', 'remove']);
		$campaigns_table = ['json_columns' => $campaigns_json_column, 'thead' => ['CAMPAIGN NAME', 'TYPE', 'STATUS', 'START DATE', 'END DATE', 'EXPECTED REVENUE', 'BUDGETED COST', 'MEMBER STATUS', 'REMOVE'], 'checkbox' => false, 'action' => false];

		$tasks_table = Task::getTabTableFormat();

		$events_json_column = table_json_columns(['name', 'start_date', 'end_date', 'location', 'attendee' , 'event_owner', 'action']);
		$events_table = ['json_columns' => $events_json_column, 'thead' => ['EVENT NAME', 'START DATE', 'END DATE', 'LOCATION', 'ATTENDEES', 'OWNER'], 'checkbox' => false];

		$calls_json_column = table_json_columns(['type', 'client', 'subject', 'related_to', 'owner', 'action']);
		$calls_table = ['json_columns' => $calls_json_column, 'thead' => ['CALL TYPE', 'CONVERSATION WITH', 'SUBJECT', 'RELATED TO', 'OWNER'], 'checkbox' => false];

		$estimates_json_column = table_json_columns(['number', 'account', 'status', 'total', 'estimate_date', 'expiry_date', 'sale_agent', 'action']);
		$estimates_table = ['json_columns' => $estimates_json_column, 'thead' => ['ESTIMATE #', 'ACCOUNT', 'STATUS', 'TOTAL', 'ESTIMATE DATE', 'EXPIRY DATE', 'SALES AGENT'], 'checkbox' => false];

		$invoices_json_column = table_json_columns(['number', 'account', 'status', 'total', 'invoice_date', 'date_pay_before', 'sale_agent', 'action']);
		$invoices_table = ['json_columns' => $invoices_json_column, 'thead' => ['INVOICE #', 'ACCOUNT', 'STATUS', 'TOTAL', 'INVOICE DATE', 'DUE DATE', 'SALES AGENT'], 'checkbox' => false];

		$items_json_column = table_json_columns(['serial', 'name', 'quantity', 'price', 'total', 'remove']);
		$items_table = ['json_columns' => $items_json_column, 'thead' => ['#', 'ITEM NAME', 'QUANTITY', 'PRICE', 'TOTAL', 'REMOVE'], 'checkbox' => false, 'action' => false];

		$files_json_column = table_json_columns(['name', 'uploaded_by', 'updated_at', 'size', 'action']);
		$files_table = ['json_columns' => $files_json_column, 'thead' => ['NAME', 'UPLOADED BY', 'DATE MODIFIED', 'SIZE'], 'checkbox' => false];
	
		$view->with(compact('deals_table', 'projects_table', 'items_table', 'campaigns_table', 'tasks_table', 'calls_table', 'events_table', 'estimates_table', 'invoices_table', 'files_table', 'at_who_data'));
	}



	public function hierarchyChildTable($view)
	{
		$json_column = table_json_columns(['checkbox', 'child_name', 'child_phone', 'open_deals_amount', 'parent_name']);
		$hierarchy_childs_table = ['json_columns' => $json_column, 'thead' => ['NAME', 'PHONE', 'OPEN DEALS AMOUNT', 'PARENT'], 'action' => false];
	
		$view->with(compact('hierarchy_childs_table'));
	}



	public function itemTable($view)
	{
		$items_json_column = table_json_columns(['checkbox', 'name', 'price']);
		$items_table = ['json_columns' => $items_json_column, 'thead' => ['ITEM NAME', 'PRICE'], 'action' => false];
	
		$view->with(compact('items_table'));
	}	



	public function campaignTable($view)
	{
		$campaigns_json_column = table_json_columns(['checkbox', 'name', 'status', 'type', 'start_date', 'end_date', 'expected_revenue']);
		$campaigns_table = ['json_columns' => $campaigns_json_column, 'thead' => ['CAMPAIGN NAME', 'STATUS', 'TYPE', 'START DATE', 'END DATE', 'EXPECTED REVENUE'], 'action' => false];
		$member_status_list = $status = ['planned' => 'Planned', 'invited' => 'Invited', 'sent' => 'Sent', 'received' => 'Received', 'opened' => 'Opened', 'responded' => 'Responded', 'bounced' => 'Bounced', 'opted_out' => 'Opted Out'];

		$view->with(compact('campaigns_table', 'member_status_list'));
	}	



	public function contactForm($view)
	{
		$admin_users_list = $this->getAdminUsersList();
		$field_list = ['' => '-Select a field-'] + Contact::massdropdown();
		$accounts_list = $this->getAccountsList(['' => '-None-']);
		$client_roles = Role::getClientRoleMap();
		$contacts_list = ['' => '-None-'] + Contact::orderBy('id')->get(['id', 'first_name', 'last_name'])->pluck('name', 'id')->toArray();
		$contacts_info_list = ['' => '-None-'] + Contact::orderBy('account_id')->get(['id', 'first_name', 'last_name', 'account_id'])->pluck('full_name', 'id')->toArray();
		$contact_types_list = ['' => '-None-'] + ContactType::orderBy('position')->get(['id', 'name'])->pluck('name', 'id')->toArray();		
		$sources_list = ['' => '-None-'] + Source::orderBy('position')->get(['id', 'name'])->pluck('name', 'id')->toArray();
		$countries_list = ['' => '-None-'] + Country::orderBy('ascii_name')->get(['id', 'code', 'ascii_name'])->pluck('ascii_name', 'code')->toArray();
		$currency_list = Currency::dropdownList();
		$base_currency = Currency::getBase();

		$view->with(compact('admin_users_list', 'field_list', 'accounts_list', 'client_roles', 'contacts_list', 'contacts_info_list', 'contact_types_list', 'sources_list', 'countries_list', 'currency_list', 'base_currency'));
	}



	public function participantContact($view)
	{
		$contacts_json_column = table_json_columns(['checkbox', 'name', 'phone', 'email', 'type' , 'account']);
		$contacts_table = ['json_columns' => $contacts_json_column, 'thead' => ['CONTACT NAME', 'PHONE', 'EMAIL', 'TYPE', 'ACCOUNT'], 'action' => false];
		$contacts_table['filter_input']['account'] = ['type' => 'dropdown', 'no_search' => true, 'options' => ['0' => 'Related Contacts', '-1' => 'All Contacts']];

		$view->with(compact('contacts_table'));
	}



	public function convertLeadForm($view)
	{
		$admin_users_list = $this->getAdminUsersList();
		$accounts_list = $this->getAccountsList(['' => '-None-']);
		$lead_stages_list = LeadStage::orderBy('position')->whereCategory('converted')->get(['id', 'name'])->pluck('name', 'id')->toArray();
		$currency_list = Currency::dropdownList();
		$base_currency = Currency::getBase();
		$deal_pipelines_list = DealPipeline::orderBy('position')->get()->pluck('name', 'id')->toArray();
		$default_pipeline = DealPipeline::default()->first();
		$closing_date = date("Y-m-d", strtotime("+$default_pipeline->period days"));
		$pipeline_stages = $default_pipeline->stages()->orderBy('pipeline_stages.position')->get(['id', 'name', 'probability']);
		$deal_stages_list = $pipeline_stages->pluck('name', 'id')->toArray();
		$default_stage = $pipeline_stages->first();

		$view->with(compact('admin_users_list', 'accounts_list', 'lead_stages_list', 'deal_pipelines_list', 'default_pipeline', 'closing_date', 'deal_stages_list', 'default_stage', 'currency_list', 'base_currency'));
	}



	public function accountInformation($view)
	{
		$account_hide_details = (Session::has('account_hide_details') && Session::get('account_hide_details')) ? true : false;	
		$base_currency = Currency::getBase();
		$currency_list = Currency::dropdownList();
		$admin_users_list = $this->getAdminUsersList();
		$parent_accounts_list = $this->getAccountsList(['' => '-None-']);
		$field_list = ['' => '-Select a field-'] + Account::massdropdown();
		$account_types_list = ['' => '-None-'] + AccountType::orderBy('position')->get(['id', 'name'])->pluck('name', 'id')->toArray();
		$industry_types_list = ['' => '-None-'] + IndustryType::orderBy('position')->get(['id', 'name'])->pluck('name', 'id')->toArray();
		$countries_list = ['' => '-None-'] + Country::orderBy('ascii_name')->get(['id', 'code', 'ascii_name'])->pluck('ascii_name', 'code')->toArray();
		$access_list = ['private' => 'Private', 'public' => 'Public Read Only', 'public_rwd' => 'Public Read/Write/Delete'];
		$contacts_json_column = table_json_columns(['name', 'phone', 'open_deals_amount', 'email', 'status' , 'action']);
		$contacts_table = ['json_columns' => $contacts_json_column, 'thead' => ['CONTACT NAME', 'PHONE', 'OPEN DEALS AMOUNT', 'EMAIL', 'STATUS'], 'checkbox' => false];
		$accounts_json_column = table_json_columns(['account_name', 'account_phone', 'open_deals_amount', 'invoice', 'payment' , 'action']);
		$sub_accounts_table = ['json_columns' => $accounts_json_column, 'thead' => ['ACCOUNT NAME', 'PHONE', 'OPEN DEALS AMOUNT', 'INVOICE', 'PAYMENT'], 'checkbox' => false];

		$view->with(compact('account_hide_details', 'admin_users_list', 'parent_accounts_list', 'field_list', 'account_types_list', 'industry_types_list', 'access_list', 'currency_list', 'base_currency', 'countries_list', 'contacts_table', 'sub_accounts_table'));
	}



	public function estimateForm($view)
	{
		$number = Estimate::max('number') + 1;
		$expiry_date = $this->getAutomatedExpiryDate();
		$accounts_list = $this->getAccountsList(['' => '-None-']);
		$status_list = ['draft' => 'Draft', 'sent' => 'Sent', 'accepted' => 'Accepted', 'expired' => 'Expired', 'declined' => 'Declined'];
		$sale_agents_list = $this->getAdminUsersList();
		$currencies = Currency::orderBy('position')->get();
		$base_currency = Currency::getBase();
		$discount_types_list = ['pre' => 'Pre Tax Discount ( % )', 'post' => 'Post Tax Discount ( % )', 'flat' => 'Flat Discount'];

		$view->with(compact('number', 'expiry_date', 'accounts_list', 'status_list', 'sale_agents_list', 'currencies', 'base_currency', 'discount_types_list'));
	}



	public function invoiceForm($view)
	{
		$number = Invoice::max('number') + 1;
		$date_pay_before = $this->getAutomatedDatePayBefore();
		$accounts_list = $this->getAccountsList(['' => '-None-']);
		$status_list = ['draft' => 'Draft', 'unpaid' => 'Sent to Account'];
		$sale_agents_list = $this->getAdminUsersList();
		$currencies = Currency::orderBy('position')->get();
		$base_currency = Currency::getBase();
		$discount_types_list = ['pre' => 'Pre Tax Discount ( % )', 'post' => 'Post Tax Discount ( % )', 'flat' => 'Flat Discount'];

		$view->with(compact('number', 'date_pay_before', 'accounts_list', 'status_list', 'sale_agents_list', 'currencies', 'base_currency', 'discount_types_list'));
	}



	public function itemForm($view)
	{
		$currency_list = Currency::dropdownList();
		$base_currency = Currency::getBase();

		$view->with(compact('currency_list', 'base_currency'));
	}



	public function projectForm($view)
	{
		$accounts_list = $this->getAccountsList(['' => '-None-']);
		$project_owners_list = $this->getAdminUsersList();
		$status_list = ['upcoming' => 'Upcoming', 'in_progress' => 'In Progress', 'completed' => 'Completed', 'cancelled' => 'Cancelled'];
	
		$view->with(compact('accounts_list', 'project_owners_list', 'status_list'));
	}



	public function taskForm($view)
	{
		$field_list = ['' => '-Select a field-'] + Task::massdropdown();
		$task_owner_list = $this->getAdminUsersList(['' => '-None-']);
		$admin_users_list = $this->getAdminUsersList();
		$priority_list = ['' => '-None-', 'high' => 'High', 'highest' => 'Highest', 'low' => 'Low', 'lowest' => 'Lowest', 'normal' => 'Normal'];
		$status_list = TaskStatus::getOptionsHtml();
		$status_plain_list = TaskStatus::orderBy('position')->get(['id', 'name'])->pluck('name', 'id')->toArray();
		$access_list = ['private' => 'Private', 'public' => 'Public Read Only', 'public_rwd' => 'Public Read/Write/Delete'];
		$repeat_type_list = ['day' => 'days', 'week' => 'weeks', 'month' => 'months', 'year' => 'years'];
		$related_type_list = ['' => '-None-', 'lead' => 'Lead', 'contact' => 'Contact', 'account' => 'Account', 'project' => 'Project', 'campaign' => 'Campaign', 'deal' => 'Deal', 'estimate' => 'Estimate', 'invoice' => 'Invoice'];
		$related_to_list['lead'] = ['' => '-None-'] + Lead::get(['id', 'first_name', 'last_name', 'company'])->pluck('full_name', 'id')->toArray();
		$related_to_list['contact'] = ['' => '-None-'] + Contact::orderBy('account_id')->get(['id', 'first_name', 'last_name', 'account_id'])->pluck('full_name', 'id')->toArray();
		$related_to_list['account'] = $this->getAccountsList(['' => '-None-']);
		$related_to_list['project'] = ['' => '-None-'] + Project::get(['id', 'name'])->pluck('name', 'id')->toArray();
		$related_to_list['campaign'] = ['' => '-None-'] + Campaign::get(['id', 'name'])->pluck('name', 'id')->toArray();
		$related_to_list['deal'] = ['' => '-None-'] + Deal::get(['id', 'name'])->pluck('name', 'id')->toArray();
		$related_to_list['estimate'] = ['' => '-None-'] + Estimate::get(['id', 'number'])->pluck('name', 'id')->toArray();
		$related_to_list['invoice'] = ['' => '-None-'] + Invoice::get(['id', 'number'])->pluck('name', 'id')->toArray();
	
		$view->with(compact('field_list', 'task_owner_list', 'admin_users_list', 'priority_list', 'status_list', 'status_plain_list', 'access_list', 'repeat_type_list', 'related_type_list', 'related_to_list'));
	}



	public function callForm($view)
	{
		$client_types = ['' => '-None-', 'lead' => 'Lead', 'contact' => 'Contact'];
		$client_list['lead'] = ['' => '-Select a lead-'] + Lead::get(['id', 'first_name', 'last_name', 'company'])->pluck('full_name', 'id')->toArray();
		$client_list['contact'] = ['' => '-Select a contact-'] + Contact::orderBy('account_id')->get(['id', 'first_name', 'last_name', 'account_id'])->pluck('full_name', 'id')->toArray();

		$related_type_list = ['' => '-None-', 'account' => 'Account', 'campaign' => 'Campaign', 'deal' => 'Deal', 'event' => 'Event', 'estimate' => 'Estimate', 'invoice' => 'Invoice', 'project' => 'Project', 'task' => 'Task'];
		$related_to_list['account'] = $this->getAccountsList(['' => '-Select an account-']);
		$related_to_list['deal'] = ['' => '-Select a deal-'] + Deal::get(['id', 'name'])->pluck('name', 'id')->toArray();
		$related_to_list['campaign'] = ['' => '-Select a campaign-'] + Campaign::get(['id', 'name'])->pluck('name', 'id')->toArray();
		$related_to_list['event'] = ['' => '-Select an event-'] + Event::get(['id', 'name'])->pluck('name', 'id')->toArray();
		$related_to_list['task'] = ['' => '-Select a task-'] + Task::get(['id', 'name'])->pluck('name', 'id')->toArray();
		$related_to_list['project'] = ['' => '-Select a project-'] + Project::get(['id', 'name'])->pluck('name', 'id')->toArray();
		$related_to_list['estimate'] = ['' => '-Select an estimate-'] + Estimate::get(['id', 'number'])->pluck('name', 'id')->toArray();
		$related_to_list['invoice'] = ['' => '-Select an invoice-'] + Invoice::get(['id', 'number'])->pluck('name', 'id')->toArray();
	
		$view->with(compact('client_types', 'client_list', 'related_type_list', 'related_to_list'));
	}



	public function expenseForm($view)
	{
		$expense_categories_list = ['' => '-None-'] + ExpenseCategory::get(['id', 'name'])->pluck('name', 'id')->toArray();
		$payment_methods_list = ['' => '-None-'] + PaymentMethod::whereStatus(1)->orderBy('position')->get(['id', 'name'])->pluck('name', 'id')->toArray();
		$accounts_list = $this->getAccountsList(['' => '-None-']);
		$currency_list = Currency::dropdownList();
		$base_currency = Currency::getBase();

		$view->with(compact('expense_categories_list', 'payment_methods_list', 'currency_list', 'base_currency', 'accounts_list'));
	}



	public function paymentForm($view)
	{
		$payment_methods_list = ['' => '-None-'] + PaymentMethod::whereStatus(1)->orderBy('position')->get(['id', 'name'])->pluck('name', 'id')->toArray();
		$base_currency = Currency::getBase();
		$view->with(compact('payment_methods_list', 'base_currency'));
	}



	public function campaignForm($view)
	{
		$campaign_owners_list = $this->getAdminUsersList();
		$status_list = ['' => '-None-', 'planning' => 'Planning', 'active' => 'Active', 'inactive' => 'Inactive', 'completed' => 'Completed'];
		$type_list = ['' => '-None-'] + CampaignType::orderBy('position')->get(['id', 'name'])->pluck('name', 'id')->toArray();
		$currency_list = Currency::dropdownList();
		$base_currency = Currency::getBase();

		$view->with(compact('campaign_owners_list', 'status_list', 'type_list', 'currency_list', 'base_currency'));
	}



	public function dealInfo($view)
	{
		$field_list = ['' => '-Select a field-'] + Deal::massdropdown();
		$accounts_list = $this->getAccountsList(['' => '-None-']);
		$contacts_list = ['' => '-None-'] + Contact::orderBy('id')->get(['id', 'first_name', 'last_name'])->pluck('name', 'id')->toArray();
		$admin_users_list = $this->getAdminUsersList();
		$deal_types_list = ['' => '-None-'] + DealType::orderBy('position')->get(['id', 'name'])->pluck('name', 'id')->toArray();
		$sources_list = ['' => '-None-'] + Source::orderBy('position')->get(['id', 'name'])->pluck('name', 'id')->toArray();
		$campaigns_list = ['' => '-None-'] + Campaign::orderBy('id')->get(['id', 'name'])->pluck('name', 'id')->toArray();
		$access_list = ['private' => 'Private', 'public' => 'Public Read Only', 'public_rwd' => 'Public Read/Write/Delete'];
		$currency_list = Currency::dropdownList();
		$base_currency = Currency::getBase();

		$deal_pipelines_list = DealPipeline::orderBy('position')->get()->pluck('name', 'id')->toArray();
		$default_pipeline = DealPipeline::default()->first();
		$closing_date = date("Y-m-d", strtotime("+$default_pipeline->period days"));
		$pipeline_stages = $default_pipeline->stages()->orderBy('pipeline_stages.position')->get(['id', 'name', 'probability']);
		$deal_stages_list = $pipeline_stages->pluck('name', 'id')->toArray();
		$all_stages_list = DealStage::orderBy('position')->pluck('name', 'id')->toArray();
		$default_stage = $pipeline_stages->first();

		$contacts_json_column = table_json_columns(['name', 'phone', 'email', 'type' , 'account', 'action']);
		$contacts_table = ['json_columns' => $contacts_json_column, 'thead' => ['CONTACT NAME', 'PHONE', 'EMAIL', 'TYPE', 'ACCOUNT'], 'checkbox' => false];

		$history_json_column = table_json_columns(['stage_name', 'amount', 'probability', 'expected_revenue', 'closing_date' , 'duration', 'modified_at', 'modified_by']);
		$stage_history_table = ['json_columns' => $history_json_column, 'thead' => ['STAGE', 'AMOUNT', 'PROBABILITY', 'EXPECTED REVENUE', 'CLOSING DATE', 'STAGE DURATION', 'MODIFIED TIME', 'MODIFIED BY'], 'checkbox' => false, 'action' => false];

		$view->with(compact('field_list', 'accounts_list', 'access_list', 'contacts_list', 'contacts_table', 'stage_history_table', 'admin_users_list', 'deal_pipelines_list', 'default_pipeline', 'closing_date', 'pipeline_stages', 'deal_stages_list', 'all_stages_list', 'default_stage', 'deal_types_list', 'sources_list', 'campaigns_list', 'base_currency', 'currency_list'));
	}



	public function dealKanban($view)
	{
		$deals_kanban = Deal::getKanbanData();
		$total_info = Deal::getTotalInfo();
		$current_pipeline = DealPipeline::getCurrentPipeline();

		$view->with(compact('deals_kanban', 'total_info', 'current_pipeline'));
	}



	public function leadstageForm($view)
	{
		$position_list = [0 => 'AT TOP'] + LeadStage::orderBy('position')->get(['id', 'name'])->pluck('position_after_name', 'id')->toArray();
		$max_position = LeadStage::max('position');
		$max_position_id = isset($max_position) ? LeadStage::wherePosition($max_position)->first()->id : 0;
		$category_list = ['open' => 'Open', 'converted' => 'Converted', 'closed_lost' => 'Closed Lost'];

		$view->with(compact('position_list', 'max_position_id', 'category_list'));
	}



	public function sourceForm($view)
	{
		$position_list = [0 => 'AT TOP'] + Source::orderBy('position')->get(['id', 'name'])->pluck('position_after_name', 'id')->toArray();
		$max_position = Source::max('position');
		$max_position_id = isset($max_position) ? Source::wherePosition($max_position)->first()->id : 0;

		$view->with(compact('position_list', 'max_position_id'));
	}



	public function contactTypeForm($view)
	{
		$position_list = [0 => 'AT TOP'] + ContactType::orderBy('position')->get(['id', 'name'])->pluck('position_after_name', 'id')->toArray();
		$max_position = ContactType::max('position');
		$max_position_id = isset($max_position) ? ContactType::wherePosition($max_position)->first()->id : 0;

		$view->with(compact('position_list', 'max_position_id'));
	}



	public function accountTypeForm($view)
	{
		$position_list = [0 => 'AT TOP'] + AccountType::orderBy('position')->get(['id', 'name'])->pluck('position_after_name', 'id')->toArray();
		$max_position = AccountType::max('position');
		$max_position_id = isset($max_position) ? AccountType::wherePosition($max_position)->first()->id : 0;

		$view->with(compact('position_list', 'max_position_id'));
	}



	public function industryTypeForm($view)
	{
		$position_list = [0 => 'AT TOP'] + IndustryType::orderBy('position')->get(['id', 'name'])->pluck('position_after_name', 'id')->toArray();
		$max_position = IndustryType::max('position');
		$max_position_id = isset($max_position) ? IndustryType::wherePosition($max_position)->first()->id : 0;

		$view->with(compact('position_list', 'max_position_id'));
	}



	public function campaigntypeForm($view)
	{
		$position_list = [0 => 'AT TOP'] + CampaignType::orderBy('position')->get(['id', 'name'])->pluck('position_after_name', 'id')->toArray();
		$max_position = CampaignType::max('position');
		$max_position_id = isset($max_position) ? CampaignType::wherePosition($max_position)->first()->id : 0;

		$view->with(compact('position_list', 'max_position_id'));
	}



	public function dealtypeForm($view)
	{
		$position_list = [0 => 'AT TOP'] + DealType::orderBy('position')->get(['id', 'name'])->pluck('position_after_name', 'id')->toArray();
		$max_position = DealType::max('position');
		$max_position_id = isset($max_position) ? DealType::wherePosition($max_position)->first()->id : 0;

		$view->with(compact('position_list', 'max_position_id'));
	}



	public function dealstageForm($view)
	{
		$position_list = [0 => 'AT TOP'] + DealStage::orderBy('position')->get(['id', 'name'])->pluck('position_after_name', 'id')->toArray();
		$max_position = DealStage::max('position');
		$max_position_id = isset($max_position) ? DealStage::wherePosition($max_position)->first()->id : 0;
		$category_list = ['open' => 'Open', 'closed_won' => 'Closed Won', 'closed_lost' => 'Closed Lost'];

		$view->with(compact('position_list', 'max_position_id', 'category_list'));
	}



	public function dealpipelineForm($view)
	{
		$deal_stages = DealStage::orderBy('position')->get(['name', 'id', 'probability', 'category', 'position']);
		$json_columns = table_json_columns(['sequence' => ['className' => 'reorder'], 'name', 'category', 'probability', 'forecast', 'remove']);
		$view->with(compact('deal_stages', 'json_columns'));
	}



	public function taskstatusForm($view)
	{
		$position_list = [0 => 'AT TOP'] + TaskStatus::orderBy('position')->get(['id', 'name'])->pluck('position_after_name', 'id')->toArray();
		$max_position = TaskStatus::max('position');
		$max_position_id = isset($max_position) ? TaskStatus::wherePosition($max_position)->first()->id : 0;
		$category_list = ['open' => 'Open', 'closed' => 'Closed'];

		$view->with(compact('position_list', 'max_position_id', 'category_list'));
	}



	public function paymentmethodForm($view)
	{
		$position_list = [0 => 'AT TOP'] + PaymentMethod::whereMasked(0)->orderBy('position')->get(['id', 'name'])->pluck('position_after_name', 'id')->toArray();
		$max_position = PaymentMethod::max('position');
		$max_position_id = isset($max_position) ? PaymentMethod::wherePosition($max_position)->first()->id : 0;

		$view->with(compact('position_list', 'max_position_id'));
	}



	public function expensecategoryForm($view)
	{
		$position_list = [0 => 'AT TOP'] + ExpenseCategory::orderBy('position')->get(['id', 'name'])->pluck('position_after_name', 'id')->toArray();
		$max_position = ExpenseCategory::max('position');
		$max_position_id = isset($max_position) ? ExpenseCategory::wherePosition($max_position)->first()->id : 0;

		$view->with(compact('position_list', 'max_position_id'));
	}



	public function goalForm($view)
	{
		$goal_owners_list = $this->getAdminUsersList(['' => '-None-']);
		$currency_list = Currency::dropdownList();
		$base_currency = Currency::getBase();
		$view->with(compact('goal_owners_list', 'currency_list', 'base_currency'));
	}



	public function eventForm($view)
	{
		$event_owners_list = $this->getAdminUsersList();
		$admin_users_list = $this->getAdminUsersList();
		$campaigns_list = ['' => '-None-'] + Campaign::get(['id', 'name'])->pluck('name', 'id')->toArray();
		
		if(Lead::count() > 0) :
			$attendees_list = Lead::get()->pluck('name', 'id_type')->toArray();
		endif;
		if(Contact::count() > 0) :
			$attendees_list = $attendees_list + Contact::get()->pluck('name', 'id_type')->toArray();
		endif;
		$attendees_list = $attendees_list + Staff::get()->pluck('name', 'id_type')->toArray();
		
		$time_unit_list = ['minute' => 'Minutes', 'hour' => 'Hours', 'day' => 'Days', 'week' => 'Weeks'];
		$repeat_type_list = ['day' => 'Days', 'week' => 'Weeks', 'month' => 'Months', 'year' => 'Years'];
		$priority_list = ['' => '-None-', 'high' => 'High', 'highest' => 'Highest', 'low' => 'Low', 'lowest' => 'Lowest', 'normal' => 'Normal'];
		$start_date = Carbon::now()->setTime(10,0)->format('Y-m-d h:i A');
		$end_date = Carbon::now()->setTime(11,0)->format('Y-m-d h:i A');

		$related_type_list = ['' => '-None-', 'lead' => 'Lead', 'contact' => 'Contact', 'account' => 'Account', 'project' => 'Project', 'campaign' => 'Campaign', 'deal' => 'Deal', 'estimate' => 'Estimate', 'invoice' => 'Invoice'];
		$related_to_list['lead'] = ['' => '-None-'] + Lead::get(['id', 'first_name', 'last_name', 'company'])->pluck('full_name', 'id')->toArray();
		$related_to_list['contact'] = ['' => '-None-'] + Contact::orderBy('account_id')->get(['id', 'first_name', 'last_name', 'account_id'])->pluck('full_name', 'id')->toArray();
		$related_to_list['account'] = $this->getAccountsList(['' => '-None-']);
		$related_to_list['project'] = ['' => '-None-'] + Project::get(['id', 'name'])->pluck('name', 'id')->toArray();
		$related_to_list['campaign'] = ['' => '-None-'] + Campaign::get(['id', 'name'])->pluck('name', 'id')->toArray();
		$related_to_list['deal'] = ['' => '-None-'] + Deal::get(['id', 'name'])->pluck('name', 'id')->toArray();
		$related_to_list['estimate'] = ['' => '-None-'] + Estimate::get(['id', 'number'])->pluck('name', 'id')->toArray();
		$related_to_list['invoice'] = ['' => '-None-'] + Invoice::get(['id', 'number'])->pluck('name', 'id')->toArray();

		$view->with(compact('event_owners_list', 'admin_users_list', 'campaigns_list', 'attendees_list', 'time_unit_list', 'repeat_type_list', 'priority_list', 'start_date', 'end_date', 'related_type_list', 'related_to_list'));
	}



	public function eventAttendee($view)
	{
		$attendees_table = Event::getAttendeeTableFormat();
		$view->with(compact('attendees_table'));
	}



	public function settingGeneralForm($view)
	{
		$currency_codes_list = $this->getCurrenciesList();
		$currency_position_list = ['left' => 'Left (ex. $75)', 'right' => 'Right (ex. 75$)'];
		$time_zones_list = $this->getTimeZonesList();
		$date_formats = ['Y-m-d', 'd-m-Y', 'm-d-Y', 'Y.m.d', 'd.m.Y', 'm.d.Y', 'Y/m/d', 'd/m/Y', 'm/d/Y'];
		$date_format_lists = array_combine($date_formats, $date_formats);
		$time_format_list = ['12' => '12 AM', '12_am' => '12 am', '24' => '24 Hours'];				  

		$view->with(compact('currency_codes_list', 'currency_position_list', 'time_zones_list', 'date_format_lists', 'time_format_list'));
	}



	public function currencyForm($view)
	{
		$separators_list = ["," => ", comma", "." => ". dot", "'" => "' apostrophe", " " => "&nbsp;space"];
		$base_currency = Currency::getBase();
		$view->with(compact('separators_list', 'base_currency'));
	}



	public function leadScoreRuleForm($view)
	{
		$related_to_list = ['' => '-None-', 'lead_property' => 'Lead Property', 'email_activity' => 'Email Activity'];
		$condition_list = get_field_conditions_list();
		
		$dropdown['days'] = ['7' => '7 days', '30' => '30 days', '90' => '90 days'];		
		$dropdown['currency'] = Currency::orderBy('position')->get()->pluck('name_info', 'id')->toArray();
		$dropdown['no_of_employees'] = ['1-10' => '1-10', '11-50' => '11-50', '51-200' => '51-200', '201-500' => '201-500', '501-1000' => '501-1000', '1001' => '1000+'];
		$dropdown['responsible_person'] = $this->getAdminUsersList();
		$dropdown['access'] = ['private' => 'Private', 'public' => 'Public read only', 'public_rwd' => 'Public read/write/delete'];
		$dropdown['country'] = Country::orderBy('ascii_name')->get(['id', 'code', 'ascii_name'])->pluck('ascii_name', 'code')->toArray();
		$dropdown['source'] = Source::orderBy('position')->get(['id', 'name'])->pluck('name', 'id')->toArray();
		$dropdown['stage'] = LeadStage::orderBy('position')->get(['id', 'name'])->pluck('name', 'id')->toArray();
		$dropdown['campaign'] = Campaign::get(['id', 'name'])->pluck('name', 'id')->toArray();
		$dropdown['event'] = Event::get(['id', 'name'])->pluck('name', 'id')->toArray();
		$dropdown['activity_type'] = ['chat' => 'Chat', 'phone' => 'Phone', 'task' => 'Task', 'email' => 'Email', 'sms' => 'SMS'];
		
		$lead_property_css = Lead::scoreConditionCssClass();
		$lead_property_list = ['' => '-None-'] + Lead::scorePropertyList();
		asort($lead_property_list);

		$view->with(compact('related_to_list', 'condition_list', 'dropdown', 'lead_property_css', 'lead_property_list'));
	}



	public function leadReportFilterForm($view)
	{
		$timeperiod_list = time_period_list();		
		$stage_list = LeadStage::orderBy('position')->get(['id', 'name'])->pluck('name', 'id')->toArray();
		$source_list = Source::orderBy('position')->get(['id', 'name'])->pluck('name', 'id')->toArray();
	
		$view->with(compact('timeperiod_list', 'stage_list', 'source_list'));
	}



	private function getTimeZonesList()
	{
		$outcome = ['' => '-None-'] + time_zones_list();
		return $outcome;
	}



	private function getCurrenciesList($select_item = [])
	{
		$outcome = $select_item + Currency::orderBy('position')->get(['id', 'code'])->pluck('code', 'id')->toArray();
		return $outcome;
	}



	private function getAdminUsersList($select_item = [])
	{
		$outcome = $select_item + Staff::orderBy('id')->get(['id', 'first_name', 'last_name'])->where('status', 1)->pluck('name', 'id')->toArray();
		return $outcome;
	}



	private function getAccountsList($select_item = [])
	{
		$outcome = $select_item + Account::get(['id', 'account_name'])->pluck('account_name', 'id')->toArray();
		return $outcome;
	}



	private function getAutomatedExpiryDate()
	{
		$last_estimate = Estimate::last();

		$days = 0;
		if(isset($last_estimate->estimate_date) && isset($last_estimate->expiry_date)) :
			$days = days_between_dates($last_estimate->estimate_date, $last_estimate->expiry_date);
		endif;

		$expiry_date = get_date_after($days);

		return $expiry_date;
	}

	

	private function getAutomatedDatePayBefore()
	{
		$last_invoice = Invoice::last();

		$days = 0;
		if(isset($last_invoice->invoice_date) && isset($last_invoice->date_pay_before)) :
			$days = days_between_dates($last_invoice->invoice_date, $last_invoice->date_pay_before);
		endif;

		$date_pay_before = get_date_after($days);

		return $date_pay_before;
	}
}