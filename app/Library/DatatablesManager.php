<?php

namespace App\Library;

use Yajra\Datatables\Datatables;

class DatatablesManager extends Datatables
{
	public static function userData($staffs, $request)
	{
		return self::of($staffs)
				->addColumn('checkbox', function($staff)
				{
					return $staff->checkbox_html;
				})
				->addColumn('name', function($staff)
				{
					return $staff->name_html;
				})
				->addColumn('email', function($staff)
				{
					return $staff->email;
				})
				->addColumn('phone', function($staff)
				{
					return $staff->phone;
				})
				->addColumn('last_login', function($staff)
				{
					return $staff->last_login_html;
				})
				->addColumn('status', function($staff)
				{
					return $staff->status_html;
				})
				->addColumn('action', function($staff)
				{
					if($staff->logged_in) :
						$action_permission = ['edit' => permit('user.edit')];						
						return $staff->getCompactActionHtml('User', null, 'admin.user.destroy', $action_permission);
					endif;

					if((auth_staff()->admin == false && $staff->admin == true) || $staff->super_admin == true) :
						return $staff->getCompactActionHtml('User', null, 'admin.user.destroy');
					endif;
						
					$action_permission = ['edit' => permit('user.edit'), 'delete' => permit('user.delete')];							
					return $staff->getCompactActionHtml('User', null, 'admin.user.destroy', $action_permission);
				})
				->filter(function($instance) use ($request)
				{
	            	$instance->collection = $instance->collection->filter(function ($row) use ($request)
	            	{
	            		$status = true;

	            		if($request->has('globalSearch') && $request->globalSearch != '') :
	            			$name = str_contains($row->name, $request->globalSearch) ? true : false;
	            			$title = str_contains($row->title, $request->globalSearch) ? true : false;
	            			$email = str_contains($row->email, $request->globalSearch) ? true : false;
	            			$phone = str_contains($row->phone, $request->globalSearch) ? true : false;
	            		
	            			if(!$name && !$title && !$email && !$phone) :
	            				$status = false;
	            			endif;
	            		endif;

	            		if(!$request->has('status') && !$row->status) :
	            			$status = false;
	            		endif;

	            	    if($request->has('status') && $request->status != '' && $row->status != $request->status) :
	            	    	$status = false;
	            	    endif;

	            	    return $status;
	            	});	            	
	            })
				->make(true);
	}



	public static function roleData($roles, $request)
	{
		return self::of($roles)
				->addColumn('checkbox', function($role)
				{
					return $role->checkbox_html;
				})
				->editColumn('display_name', function($role)
				{
					return $role->display_name_html;
				})
				->addColumn('total_users', function($role)
				{
					return $role->total_users_html;
				})
				->addColumn('view_users', function($role)
				{
					return $role->view_users_html;
				})
				->addColumn('action', function($role)
				{
					$action_permission = ['edit' => permit('role.edit'), 'delete' => permit('role.delete')];							
					return $role->getCompactActionHtml('Role', 'admin.role.edit', 'admin.role.destroy', $action_permission);		
				})
				->make(true);
	}



	public static function leadData($leads, $request)
	{
		return self::of($leads)
				->addColumn('checkbox', function($lead)
				{
					return $lead->checkbox_html;
				})
				->addColumn('name', function($lead)
				{
					return $lead->name_html;
				})
				->addColumn('score', function($lead)
				{
					return $lead->lead_score_link;
				})
				->editColumn('stage', function($lead)
				{
					return $lead->stage_html;
				})
				->editColumn('source', function($lead)
				{
					return non_property_checker($lead->source, 'name');
				})
				->editColumn('lead_owner', function($lead)
				{
					return non_property_checker($lead->leadowner, 'name_link');
				})
				->addColumn('action', function($lead)
				{
					$action_permission = ['edit' => $lead->auth_can_edit, 'delete' => $lead->auth_can_delete];
					return $lead->getCompactActionHtml('Lead', null, 'admin.lead.destroy', $action_permission);
				})
				->filter(function($instance) use ($request)
				{
	            	$instance->collection = $instance->collection->filter(function ($row) use ($request)
	            	{
	            		$status = true;

	            		if($request->has('globalSearch') && $request->globalSearch != '') :
	            			$first_name = str_contains($row->first_name, $request->globalSearch) ? true : false;
	            			$last_name = str_contains($row->last_name, $request->globalSearch) ? true : false;
	            			$company = str_contains($row->company, $request->globalSearch) ? true : false;
	            			$email = str_contains($row->email, $request->globalSearch) ? true : false;
	            			$phone = str_contains($row->phone, $request->globalSearch) ? true : false;
            		
	            			if(!$first_name && !$last_name && !$company && !$email && !$phone) :
	            				$status = false;
	            			endif;
	            		endif;

            		    if($request->has('stage') && $row->lead_stage_id != $request->stage) :
            		    	$status = false;
            		    endif;

	            	    if($request->has('source') && $row->source_id != $request->source) :
	            	    	$status = false;
	            	    endif;

            	        if($request->has('lead_owner') && $row->lead_owner != $request->lead_owner) :
            	        	$status = false;
            	        endif;

	            	    return $status;
	            	});	            	
	            })
				->make(true);
	}



	public static function cartItemData($items, $request)
	{
		return self::of($items)
				->addColumn('serial', function($item)
				{
					return $item->pivot_serial;
				})
				->editColumn('name', function($item)
				{
					return $item->name;
				})
				->addColumn('quantity', function($item)
				{
					return $item->quantity_html;
				})
				->editColumn('price', function($item)
				{
					return $item->rate_html;
				})
				->addColumn('total', function($item)
				{
					return $item->amountHtml('total');
				})
				->addColumn('remove', function($item)
				{
					return $item->remove_cart_item;
				})
				->make(true);
	}



	public static function memberCampaignData($campaigns, $request)
	{
		return self::of($campaigns)
				->editColumn('name', function($campaign)
				{
					return $campaign->name_html;
				})
				->addColumn('type', function($campaign)
				{
					return non_property_checker($campaign->type, 'name');
				})
				->editColumn('status', function($campaign)
				{
					return $campaign->status_html;
				})
				->editColumn('start_date', function($campaign)
				{
					return $campaign->readableDateHtml('start_date');
				})
				->editColumn('end_date', function($campaign)
				{
					return $campaign->readableDateHtml('end_date');
				})
				->editColumn('expected_revenue', function($campaign)
				{
					return $campaign->amountHtml('expected_revenue');
				})
				->editColumn('budgeted_cost', function($campaign)
				{
					return $campaign->amountHtml('budgeted_cost');
				})
				->editColumn('member_status', function($campaign)
				{
					return $campaign->member_status;
				})
				->addColumn('remove', function($campaign)
				{
					return $campaign->member_action_html;
				})
				->make(true);
	}



	public static function leadScoreRuleData($scores, $request)
	{
		return self::of($scores)
				->addColumn('rule', function($score)
				{
					return $score->rule_html;
				})
				->editColumn('score', function($score)
				{
					return $score->score_html;
				})
				->addColumn('action', function($score)
				{
					$action_permission = ['edit' => false, 'delete' => permit('settings.lead_scoring_rule.delete')];
					return $score->getCompactActionHtml('Rule', null, 'admin.administration-setting-lead-scoring-rule.destroy', $action_permission);
				})
				->make(true);
	}



	public static function contactData($contacts, $request)
	{
		return self::of($contacts)
				->addColumn('checkbox', function($contact)
				{
					return $contact->checkbox_html;
				})
				->addColumn('name', function($contact)
				{
					return $contact->name_html;
				})
				->addColumn('open_deals_amount', function($contact)
				{
					return $contact->open_deals_amount_html;
				})
				->addColumn('email', function($contact)
				{
					return $contact->email;
				})
				->editColumn('contact_owner', function($contact)
				{
					return $contact->contactowner->name_link;
				})
				->addColumn('last_login', function($contact)
				{
					return $contact->last_login_html;
				})
				->addColumn('status', function($contact)
				{
					return $contact->status_html;
				})
				->addColumn('action', function($contact)
				{
					$action_permission = ['edit' => $contact->auth_can_edit, 'delete' => $contact->auth_can_delete];
					return $contact->getCompactActionHtml('Contact', null, 'admin.contact.destroy', $action_permission);
				})
				->make(true);
	}



	public static function appendContactData($contacts, $request)
	{
		return self::of($contacts)
				->addColumn('name', function($contact)
				{
					return $contact->getNameHtmlAttribute(false);
				})
				->addColumn('open_deals_amount', function($contact)
				{
					return $contact->open_deals_amount_html;
				})
				->addColumn('email', function($contact)
				{
					return $contact->email;
				})
				->addColumn('status', function($contact)
				{
					return $contact->status_html;
				})
				->addColumn('action', function($contact)
				{
					$action_permission = ['edit' => $contact->auth_can_edit, 'delete' => $contact->auth_can_delete];
					return $contact->getCompactActionHtml('Contact', null, 'admin.contact.destroy', $action_permission, true);
				})
				->make(true);
	}



	public static function sourceData($sources, $request)
	{
		return self::of($sources)
				->addColumn('sequence', function($source)
				{
					return $source->drag_and_drop;
				})
				->addColumn('action', function($source)
				{
					$action_permission = ['edit' => permit('custom_dropdowns.source.edit'), 'delete' => permit('custom_dropdowns.source.delete')];							
					return $source->getCompactActionHtml('Source', null, 'admin.administration-dropdown-source.destroy', $action_permission);
				})
				->make(true);
	}



	public static function leadStageData($lead_stages, $request)
	{
		return self::of($lead_stages)
				->addColumn('sequence', function($lead_stage)
				{
					return $lead_stage->drag_and_drop;
				})
				->editColumn('category', function($lead_stage)
				{
					return $lead_stage->category_html;
				})
				->addColumn('action', function($lead_stage)
				{
					$action_permission = ['edit' => permit('custom_dropdowns.lead_stage.edit'), 'delete' => (permit('custom_dropdowns.lead_stage.delete') && !$lead_stage->fixed)];							
					return $lead_stage->getCompactActionHtml('Stage', null, 'admin.administration-dropdown-leadstage.destroy', $action_permission);
				})
				->make(true);
	}



	public static function contactTypeData($contact_types, $request)
	{
		return self::of($contact_types)
				->addColumn('sequence', function($contact_type)
				{
					return $contact_type->drag_and_drop;
				})
				->addColumn('action', function($contact_type)
				{
					$action_permission = ['edit' => permit('custom_dropdowns.contact_type.edit'), 'delete' => permit('custom_dropdowns.contact_type.delete')];							
					return $contact_type->getCompactActionHtml('Type', null, 'admin.administration-dropdown-contacttype.destroy', $action_permission);
				})
				->make(true);
	}



	public static function accountTypeData($account_types, $request)
	{
		return self::of($account_types)
				->addColumn('sequence', function($account_type)
				{
					return $account_type->drag_and_drop;
				})
				->addColumn('action', function($account_type)
				{
					$action_permission = ['edit' => permit('custom_dropdowns.account_type.edit'), 'delete' => permit('custom_dropdowns.account_type.delete')];							
					return $account_type->getCompactActionHtml('Type', null, 'admin.administration-dropdown-accounttype.destroy', $action_permission);
				})
				->make(true);
	}



	public static function industryTypeData($industry_types, $request)
	{
		return self::of($industry_types)
				->addColumn('sequence', function($industry_type)
				{
					return $industry_type->drag_and_drop;
				})
				->addColumn('action', function($industry_type)
				{
					$action_permission = ['edit' => permit('custom_dropdowns.industry_type.edit'), 'delete' => permit('custom_dropdowns.industry_type.delete')];							
					return $industry_type->getCompactActionHtml('Type', null, 'admin.administration-dropdown-industrytype.destroy', $action_permission);
				})
				->make(true);
	}



	public static function accountData($accounts, $request)
	{
		return self::of($accounts)
				->addColumn('checkbox', function($account)
				{
					return $account->checkbox_html;
				})
				->editColumn('account_name', function($account)
				{
					return $account->name_html;
				})
				->addColumn('open_deals_amount', function($account)
				{
					return $account->open_deals_amount_html;
				})
				->addColumn('invoice', function($account)
				{
					return $account->amountTotalHtml('invoices', 'grand_total');
				})
				->addColumn('payment', function($account)
				{
					return $account->amountTotalHtml('payments', 'amount');
				})
				->editColumn('account_owner', function($account)
				{
					return $account->owner->name_link;
				})
				->addColumn('action', function($account)
				{
					$action_permission = ['edit' => permit('account.edit'), 'delete' => permit('account.delete')];							
					return $account->getCompactActionHtml('Account', null, 'admin.account.destroy', $action_permission);
				})
				->make(true);
	}



	public static function subAccountData($accounts, $request)
	{
		return self::of($accounts)
				->editColumn('account_name', function($account)
				{
					return $account->getNameHtmlAttribute(false);
				})
				->addColumn('open_deals_amount', function($account)
				{
					return $account->open_deals_amount_html;
				})
				->addColumn('invoice', function($account)
				{
					return $account->amountTotalHtml('invoices', 'grand_total');
				})
				->addColumn('payment', function($account)
				{
					return $account->amountTotalHtml('payments', 'amount');
				})
				->addColumn('action', function($account)
				{
					$action_permission = ['edit' => permit('account.edit'), 'delete' => permit('account.delete')];							
					return $account->getCompactActionHtml('Account', null, 'admin.account.destroy', $action_permission, true);
				})
				->make(true);
	}



	public static function hierarchyAddChildData($childs, $request)
	{
		return self::of($childs)
				->addColumn('checkbox', function($child)
				{
					return $child->getCheckboxHtmlAttribute('info');
				})
				->addColumn('child_name', function($child)
				{
					return $child->getNameHtmlAttribute(false);
				})
				->addColumn('child_phone', function($child)
				{
					return $child->phone;
				})
				->addColumn('open_deals_amount', function($child)
				{
					return $child->open_deals_amount_html;
				})
				->addColumn('parent_name', function($child)
				{
					return non_property_checker($child->directParent, 'name_link');
				})
				->make(true);
	}



	public static function itemData($items, $request)
	{
		return self::of($items)
				->addColumn('checkbox', function($item)
				{
					return $item->checkbox_html;
				})
				->editColumn('price', function($item)
				{
					return $item->amountHtml('price');
				})
				->editColumn('tax', function($item)
				{
					return $item->tax_format;
				})
				->editColumn('discount', function($item)
				{
					return $item->discount_format;
				})
				->addColumn('action', function($item)
				{
					$action_permission = ['edit' => permit('sale.item.edit'), 'delete' => permit('sale.item.delete')];						
					return $item->getCompactActionHtml('Item', null, 'admin.sale-item.destroy', $action_permission);
				})
				->make(true);
	}



	public static function selectItemData($items, $request)
	{
		return self::of($items)
				->addColumn('checkbox', function($item)
				{
					return $item->getCheckboxHtmlAttribute('info');
				})
				->editColumn('price', function($item)
				{
					return $item->amountHtml('price');
				})
				->make(true);
	}



	public static function estimateData($estimates, $request)
	{
		return self::of($estimates)
				->addColumn('checkbox', function($estimate)
				{
					return $estimate->checkbox_html;
				})
				->editColumn('number', function($estimate)
				{
					return $estimate->number_html;
				})
				->addColumn('account', function($estimate)
				{
					return $estimate->account->account_name;
				})
				->editColumn('status', function($estimate)
				{
					return $estimate->status_html;
				})
				->addColumn('total', function($estimate)
				{
					return $estimate->amountHtml('grand_total');
				})
				->editColumn('estimate_date', function($estimate)
				{
					return $estimate->readableDateHtml('estimate_date');
				})
				->editColumn('expiry_date', function($estimate)
				{
					return $estimate->readableDateHtml('expiry_date');
				})
				->addColumn('sale_agent', function($estimate)
				{
					return $estimate->saleagent->name_link;
				})
				->addColumn('action', function($estimate)
				{
					$action_permission = ['edit' => permit('sale.estimate.edit'), 'delete' => permit('sale.estimate.delete')];
					return $estimate->getCompactActionHtml('Estimate', 'admin.sale-estimate.edit', 'admin.sale-estimate.destroy', $action_permission);
				})
				->make(true);
	}



	public static function connectedEstimateData($estimates, $request)
	{
		return self::of($estimates)
				->editColumn('number', function($estimate)
				{
					return $estimate->number_html;
				})
				->addColumn('account', function($estimate)
				{
					return $estimate->account->account_name;
				})
				->editColumn('status', function($estimate)
				{
					return $estimate->status_html;
				})
				->addColumn('total', function($estimate)
				{
					return $estimate->amountHtml('grand_total');
				})
				->editColumn('estimate_date', function($estimate)
				{
					return $estimate->readableDateHtml('estimate_date');
				})
				->editColumn('expiry_date', function($estimate)
				{
					return $estimate->readableDateHtml('expiry_date');
				})
				->addColumn('sale_agent', function($estimate)
				{
					return $estimate->saleagent->name_link;
				})
				->addColumn('action', function($estimate)
				{
					$action_permission = ['edit' => permit('sale.estimate.edit'), 'delete' => permit('sale.estimate.delete')];
					return $estimate->getCompactActionHtml('Estimate', 'admin.sale-estimate.edit', 'admin.sale-estimate.destroy', $action_permission, true);
				})
				->make(true);
	}



	public static function invoiceData($invoices, $request)
	{
		return self::of($invoices)
				->addColumn('checkbox', function($invoice)
				{
					return $invoice->checkbox_html;
				})
				->editColumn('number', function($invoice)
				{
					return $invoice->number_html;
				})
				->addColumn('account', function($invoice)
				{
					return $invoice->account->account_name;
				})
				->editColumn('status', function($invoice)
				{
					return $invoice->status_html;
				})
				->addColumn('total', function($invoice)
				{
					return $invoice->amountHtml('grand_total');
				})
				->editColumn('invoice_date', function($invoice)
				{
					return $invoice->readableDateHtml('invoice_date');
				})
				->editColumn('date_pay_before', function($invoice)
				{
					return $invoice->readableDateHtml('date_pay_before');
				})
				->addColumn('sale_agent', function($invoice)
				{
					return $invoice->saleagent->name_link;
				})
				->addColumn('action', function($invoice)
				{
					$action_permission = ['edit' => permit('sale.invoice.edit'), 'delete' => permit('sale.invoice.delete')];
					return $invoice->getCompactActionHtml('Invoice', 'admin.sale-invoice.edit', 'admin.sale-invoice.destroy', $action_permission);
				})
				->make(true);
	}



	public static function connectedInvoiceData($invoices, $request)
	{
		return self::of($invoices)
				->editColumn('number', function($invoice)
				{
					return $invoice->number_html;
				})
				->addColumn('account', function($invoice)
				{
					return $invoice->account->account_name;
				})
				->editColumn('status', function($invoice)
				{
					return $invoice->status_html;
				})
				->addColumn('total', function($invoice)
				{
					return $invoice->amountHtml('grand_total');
				})
				->editColumn('invoice_date', function($invoice)
				{
					return $invoice->readableDateHtml('invoice_date');
				})
				->editColumn('date_pay_before', function($invoice)
				{
					return $invoice->readableDateHtml('date_pay_before');
				})
				->addColumn('sale_agent', function($invoice)
				{
					return $invoice->saleagent->name_link;
				})
				->addColumn('action', function($invoice)
				{
					$action_permission = ['edit' => permit('sale.invoice.edit'), 'delete' => permit('sale.invoice.delete')];
					return $invoice->getCompactActionHtml('Invoice', 'admin.sale-invoice.edit', 'admin.sale-invoice.destroy', $action_permission, true);
				})
				->make(true);
	}



	public static function projectData($projects, $request)
	{
		return self::of($projects)
				->addColumn('checkbox', function($project)
				{
					return $project->checkbox_html;
				})
				->editColumn('name', function($project)
				{
					return $project->name_html;
				})
				->editColumn('completion_percentage', function($project)
				{
					return $project->completion_percentage_html;
				})
				->editColumn('project_owner', function($project)
				{
					return non_property_checker($project->owner, 'name_link');
				})
				->addColumn('tasks', function($project)
				{
					return $project->task_stat_html;
				})
				->addColumn('milestones', function($project)
				{
					return $project->milestone_stat_html;
				})
				->addColumn('issues', function($project)
				{
					return $project->milestone_stat_html;
				})
				->editColumn('start_date', function($project)
				{
					return $project->readableDateHtml('start_date');
				})
				->editColumn('end_date', function($project)
				{
					return $project->readableDateHtml('end_date');
				})
				->addColumn('action', function($project)
				{
					$action_permission = ['edit' => permit('project.edit'), 'delete' => permit('project.delete')];
					return $project->getCompactActionHtml('Project', null, 'admin.project.destroy', $action_permission);
				})
				->make(true);
	}



	public static function connectedProjectData($projects, $request)
	{
		return self::of($projects)
				->editColumn('name', function($project)
				{
					return $project->name_html;
				})
				->editColumn('completion_percentage', function($project)
				{
					return $project->completion_percentage_html;
				})
				->editColumn('project_owner', function($project)
				{
					return non_property_checker($project->owner, 'name_link');
				})
				->addColumn('tasks', function($project)
				{
					return $project->task_stat_html;
				})
				->addColumn('milestones', function($project)
				{
					return $project->milestone_stat_html;
				})
				->addColumn('issues', function($project)
				{
					return $project->milestone_stat_html;
				})
				->editColumn('start_date', function($project)
				{
					return $project->readableDateHtml('start_date');
				})
				->editColumn('end_date', function($project)
				{
					return $project->readableDateHtml('end_date');
				})
				->addColumn('action', function($project)
				{
					$action_permission = ['edit' => permit('project.edit'), 'delete' => permit('project.delete')];
					return $project->getCompactActionHtml('Project', null, 'admin.project.destroy', $action_permission, true);
				})
				->make(true);
	}



	public static function projectDisplayData($projects, $request)
	{
		return self::of($projects)
				->editColumn('name', function($project)
				{
					return $project->name_html;
				})
				->editColumn('completion_percentage', function($project)
				{
					return $project->completion_percentage_html;
				})
				->editColumn('project_owner', function($project)
				{
					return $project->owner_html;
				})
				->addColumn('member', function($project)
				{
					return $project->members_html;
				})
				->addColumn('tasks', function($project)
				{
					return $project->task_stat_html;
				})
				->addColumn('milestones', function($project)
				{
					return $project->milestone_stat_html;
				})
				->addColumn('issues', function($project)
				{
					return $project->milestone_stat_html;
				})
				->addColumn('date', function($project)
				{
					return $project->getDateHtmlAttribute('left');
				})
				->make(true);
	}



	public static function taskData($tasks, $request)
	{
		return self::of($tasks)
				->addColumn('checkbox', function($task)
				{
					return $task->checkbox_html;
				})
				->editColumn('name', function($task)
				{
					return $task->name_html;
				})
				->editColumn('completion_percentage', function($task)
				{
					return $task->completion_html;
				})
				->addColumn('status', function($task)
				{
					return $task->status->name;
				})
				->editColumn('priority', function($task)
				{
					return $task->priority_html;
				})
				->editColumn('due_date', function($task)
				{
					return $task->due_date_html;
				})
				->addColumn('related_to', function($task)
				{
					return non_property_checker($task->linked, 'name_link_icon');
				})
				->editColumn('task_owner', function($task)
				{
					return $task->owner_html;
				})
				->addColumn('action', function($task)
				{
					$action_permission = ['edit' => permit('task.edit'), 'delete' => permit('task.delete')];
					return $task->getCompactActionHtml('Task', null, 'admin.task.destroy', $action_permission);
				})
				->make(true);
	}



	public static function taskDisplayData($tasks, $request)
	{
		return self::of($tasks)
				->editColumn('name', function($task)
				{
					return $task->name_html;
				})
				->editColumn('task_owner', function($task)
				{
					return $task->owner_html;
				})
				->editColumn('completion_percentage', function($task)
				{
					return $task->completion_html;
				})
				->addColumn('date', function($task)
				{
					return $task->date_html;
				})
				->make(true);
	}



	public static function connectedTaskData($tasks, $request)
	{
		return self::of($tasks)
				->editColumn('name', function($task)
				{
					return $task->name_html;
				})
				->editColumn('task_owner', function($task)
				{
					return $task->owner_html;
				})
				->editColumn('completion_percentage', function($task)
				{
					return $task->completion_html;
				})
				->editColumn('start_date', function($task)
				{
					return $task->readableDateHtml('start_date');
				})
				->editColumn('due_date', function($task)
				{
					return $task->readableDateHtml('due_date');
				})
				->addColumn('action', function($task)
				{
					$action_permission = ['edit' => permit('task.edit'), 'delete' => permit('task.delete')];
					return $task->getCompactActionHtml('Task', null, 'admin.task.destroy', $action_permission, true);
				})
				->make(true);
	}



	public static function connectedEventData($events, $request)
	{
		return self::of($events)
				->editColumn('name', function($event)
				{
					return $event->name_html;
				})
				->editColumn('event_owner', function($event)
				{
					return $event->owner_html;
				})
				->editColumn('attendee', function($event)
				{
					return $event->attendees_html;
				})
				->editColumn('start_date', function($event)
				{
					return $event->readableDateHtml('start_date', true);
				})
				->editColumn('end_date', function($event)
				{
					return $event->readableDateHtml('end_date', true);
				})
				->addColumn('action', function($event)
				{
					$action_permission = ['edit' => permit('advanced.calendar.edit_event'), 'delete' => permit('advanced.calendar.delete_event')];
					return $event->getCompactActionHtml('Event', null, 'admin.event.destroy', $action_permission, true);
				})
				->make(true);
	}



	public static function callData($calls, $request)
	{
		return self::of($calls)
				->editColumn('type', function($call)
				{
					return $call->type_html;
				})
				->addColumn('client', function($call)
				{
					return $call->client->profile_html;
				})
				->editColumn('subject', function($call)
				{
					return $call->name_html;
				})
				->addColumn('related_to', function($call)
				{
					return non_property_checker($call->related, 'name_link_icon');
				})
				->addColumn('owner', function($call)
				{
					return $call->createdByNameLink();
				})
				->addColumn('action', function($task)
				{
					$action_permission = ['edit' => permit('task.edit'), 'delete' => permit('task.delete')];
					return $task->getCompactActionHtml('Call', null, 'admin.call.destroy', $action_permission, ['modal-small' => 'medium', 'modal-title' => 'Edit Call Log']);
				})
				->make(true);
	}



	public static function paymentMethodData($payment_methods, $request)
	{
		return self::of($payment_methods)
				->addColumn('sequence', function($payment_method)
				{
					return $payment_method->drag_and_drop;
				})
				->editColumn('description', function($payment_method)
				{
					return nl2br($payment_method->description);
				})
				->editColumn('status', function($payment_method)
				{
					return $payment_method->status_html;
				})
				->addColumn('action', function($payment_method)
				{
					$action_permission = ['edit' => (permit('settings.payment_method.edit') && !$payment_method->masked), 'delete' => (permit('settings.payment_method.delete') && !$payment_method->masked && $payment_method->can_delete)];							
					return $payment_method->getCompactActionHtml('Method', null, 'admin.administration-setting-offline-payment.destroy', $action_permission);
				})
				->make(true);
	}



	public static function expenseCategoryData($expense_categories, $request)
	{
		return self::of($expense_categories)
				->addColumn('sequence', function($expense_category)
				{
					return $expense_category->drag_and_drop;
				})
				->addColumn('action', function($expense_category)
				{
					$action_permission = ['edit' => permit('custom_dropdowns.expense_category.edit'), 'delete' => (permit('custom_dropdowns.expense_category.delete') && $expense_category->can_delete)];							
					return $expense_category->getCompactActionHtml('Category', null, 'admin.administration-dropdown-expensecategory.destroy', $action_permission);
				})
				->make(true);
	}



	public static function expenseData($expenses, $request)
	{
		return self::of($expenses)
				->addColumn('checkbox', function($expense)
				{
					return $expense->checkbox_html;
				})
				->editColumn('expense_date', function($expense)
				{
					return $expense->readableDateHtml('expense_date');
				})
				->addColumn('category', function($expense)
				{						
					return $expense->category->name;
				})
				->editColumn('name', function($expense)
				{
					return $expense->name_html;
				})
				->editColumn('amount', function($expense)
				{
					return $expense->amountHtml('amount');
				})
				->editColumn('payment_method', function($expense)
				{
					return "<span class='capitalize'>$expense->payment_method</span>";
				})
				->addColumn('account', function($expense)
				{
					return non_property_checker($expense->account, 'name_link');
				})
				->addColumn('project', function($expense)
				{
					return non_property_checker($expense->project, 'name_link');
				})
				->addColumn('action', function($expense)
				{
					$action_permission = ['edit' => permit('finance.expense.edit'), 'delete' => permit('finance.expense.delete')];							
					return $expense->getCompactActionHtml('Expense', null, 'admin.finance-expense.destroy', $action_permission);
				})
				->make(true);
	}



	public static function goalData($goals, $request)
	{
		return self::of($goals)
				->addColumn('checkbox', function($goal)
				{
					return $goal->checkbox_html;
				})
				->editColumn('name', function($goal)
				{
					return $goal->name_html;
				})
				->editColumn('goal_owner', function($goal)
				{
					return non_property_checker($goal->owner, 'profile_html');
				})
				->editColumn('leads_count', function($goal)
				{
					return $goal->leads_compare;
				})
				->editColumn('accounts_count', function($goal)
				{
					return $goal->accounts_compare;
				})
				->editColumn('deals_count', function($goal)
				{
					return $goal->deals_compare;
				})
				->editColumn('sales_amount', function($goal)
				{
					return $goal->sales_compare;
				})
				->addColumn('progress', function($goal)
				{
					return $goal->overall_progress_html;
				})
				->addColumn('date', function($goal)
				{
					return $goal->date_html;
				})
				->addColumn('action', function($goal)
				{
					$action_permission = ['edit' => permit('advanced.goal.edit'), 'delete' => permit('advanced.goal.delete')];
					return $goal->getCompactActionHtml('Goal', null, 'admin.advanced-goal.destroy', $action_permission, false);
				})
				->make(true);
	}



	public static function invoicePaymentData($payments, $request)
	{
		return self::of($payments)
				->addColumn('payment_id', function($payment)
				{						
					return $payment->id_format;
				})
				->editColumn('payment_method', function($payment)
				{
					return $payment->method_html;
				})
				->editColumn('amount', function($payment)
				{
					return $payment->amountHtml('amount');
				})				
				->addColumn('action', function($payment)
				{
					$action_permission = ['edit' => permit('finance.payment.edit'), 'delete' => permit('finance.payment.delete')];							
					return $payment->getCompactActionHtml('Payment', null, 'admin.finance-payment.destroy', $action_permission);
				})
				->make(true);
	}



	public static function paymentData($payments, $request)
	{
		return self::of($payments)
				->addColumn('checkbox', function($payment)
				{
					return $payment->checkbox_html;
				})
				->editColumn('payment_date', function($payment)
				{
					return $payment->readableDateHtml('payment_date');
				})
				->addColumn('payment_id', function($payment)
				{						
					return $payment->id_html;
				})
				->editColumn('payment_method', function($payment)
				{
					return $payment->getMethodHtmlAttribute(true);
				})
				->editColumn('transaction_id', function($payment)
				{
					return $payment->transaction_id_html;
				})
				->editColumn('amount', function($payment)
				{
					return $payment->amountHtml('amount');
				})
				->addColumn('account', function($payment)
				{
					return $payment->account_html;
				})
				->addColumn('invoice', function($payment)
				{
					return $payment->invoice->getNumberHtmlAttribute('like-txt', true);
				})			
				->addColumn('action', function($payment)
				{
					$action_permission = ['edit' => permit('finance.payment.edit'), 'delete' => permit('finance.payment.delete')];							
					return $payment->getCompactActionHtml('Payment', null, 'admin.finance-payment.destroy', $action_permission);
				})
				->make(true);
	}



	public static function campaignTypeData($campaign_types, $request)
	{
		return self::of($campaign_types)
				->addColumn('sequence', function($campaign_type)
				{
					return $campaign_type->drag_and_drop;
				})
				->addColumn('action', function($campaign_type)
				{
					$action_permission = ['edit' => permit('custom_dropdowns.campaign_type.edit'), 'delete' => permit('custom_dropdowns.campaign_type.delete')];							
					return $campaign_type->getCompactActionHtml('Type', null, 'admin.administration-dropdown-campaigntype.destroy', $action_permission);
				})
				->make(true);
	}



	public static function campaignData($campaigns, $request)
	{
		return self::of($campaigns)
				->addColumn('checkbox', function($campaign)
				{
					return $campaign->checkbox_html;
				})
				->editColumn('name', function($campaign)
				{
					return $campaign->name_html;
				})
				->addColumn('type', function($campaign)
				{
					return non_property_checker($campaign->type, 'name');
				})
				->editColumn('status', function($campaign)
				{
					return $campaign->status_html;
				})
				->editColumn('start_date', function($campaign)
				{
					return $campaign->readableDateHtml('start_date');
				})
				->editColumn('end_date', function($campaign)
				{
					return $campaign->readableDateHtml('end_date');
				})
				->editColumn('campaign_owner', function($campaign)
				{
					return non_property_checker($campaign->owner, 'name_link');
				})
				->addColumn('action', function($campaign)
				{
					$action_permission = ['edit' => permit('campaign.edit'), 'delete' => permit('campaign.delete')];							
					return $campaign->getCompactActionHtml('Campaign', null, 'admin.campaign.destroy', $action_permission);
				})
				->make(true);
	}



	public static function campaignSelectData($campaigns, $request)
	{
		return self::of($campaigns)
				->addColumn('checkbox', function($campaign)
				{
					return $campaign->getCheckboxHtmlAttribute('info');
				})
				->editColumn('name', function($campaign)
				{
					return $campaign->name_html;
				})
				->addColumn('type', function($campaign)
				{
					return non_property_checker($campaign->type, 'name');
				})
				->editColumn('status', function($campaign)
				{
					return $campaign->status_html;
				})
				->editColumn('start_date', function($campaign)
				{
					return $campaign->readableDateHtml('start_date');
				})
				->editColumn('end_date', function($campaign)
				{
					return $campaign->readableDateHtml('end_date');
				})
				->editColumn('expected_revenue', function($campaign)
				{
					return $campaign->amountHtml('expected_revenue');
				})
				->make(true);
	}



	public static function dealtypeData($deal_types, $request)
	{
		return self::of($deal_types)
				->addColumn('sequence', function($deal_type)
				{
					return $deal_type->drag_and_drop;
				})
				->addColumn('action', function($deal_type)
				{
					$action_permission = ['edit' => permit('custom_dropdowns.deal_type.edit'), 'delete' => permit('custom_dropdowns.deal_type.delete')];							
					return $deal_type->getCompactActionHtml('Type', null, 'admin.administration-dropdown-dealtype.destroy', $action_permission);
				})
				->make(true);
	}



	public static function dealstageData($deal_stages, $request)
	{
		return self::of($deal_stages)
				->addColumn('sequence', function($deal_stage)
				{
					return $deal_stage->drag_and_drop;
				})
				->editColumn('category', function($deal_stage)
				{
					return $deal_stage->category_html;
				})
				->editColumn('probability', function($deal_stage)
				{
					return $deal_stage->probability . '%';
				})
				->addColumn('action', function($deal_stage)
				{
					$action_permission = ['edit' => permit('custom_dropdowns.deal_stage.edit'), 'delete' => (permit('custom_dropdowns.deal_stage.delete') && !$deal_stage->fixed)];							
					return $deal_stage->getCompactActionHtml('Stage', null, 'admin.administration-dropdown-dealstage.destroy', $action_permission);
				})
				->make(true);
	}



	public static function pipelineData($deal_pipelines, $request)
	{
		return self::of($deal_pipelines)
				->addColumn('sequence', function($deal_pipeline)
				{
					return $deal_pipeline->drag_and_drop;
				})
				->editColumn('name', function($deal_pipeline)
				{
					return $deal_pipeline->name_html;
				})
				->addColumn('total_stages', function($deal_pipeline)
				{
					return $deal_pipeline->stages->count() . ' Stages';
				})
				->editColumn('period', function($deal_pipeline)
				{
					return $deal_pipeline->period . ' days';
				})
				->addColumn('action', function($deal_pipeline)
				{
					$action_permission = ['edit' => permit('custom_dropdowns.deal_pipeline.edit'), 'delete' => (permit('custom_dropdowns.deal_pipeline.delete') && !$deal_pipeline->default && $deal_pipeline->can_delete)];							
					return $deal_pipeline->getCompactActionHtml('Pipeline', null, 'admin.administration-dropdown-dealpipeline.destroy', $action_permission);
				})
				->make(true);
	}



	public static function pipelineStageData($pipeline_stages, $request)
	{
		return self::of($pipeline_stages)
				->addColumn('sequence', function($pipeline_stage)
				{
					return $pipeline_stage->drag_and_drop;
				})
				->editColumn('name', function($pipeline_stage)
				{
					return $pipeline_stage->name;
				})
				->editColumn('category', function($pipeline_stage)
				{
					return $pipeline_stage->category_html;
				})
				->editColumn('probability', function($pipeline_stage)
				{
					return $pipeline_stage->probability . '%';
				})
				->addColumn('forecast', function($pipeline_stage)
				{
					return $pipeline_stage->forecast_html;
				})
				->addColumn('remove', function($pipeline_stage)
				{
					return $pipeline_stage->remove_html;
				})
				->make(true);
	}



	public static function dealData($deals, $request)
	{
		return self::of($deals)
				->addColumn('checkbox', function($deal)
				{
					return $deal->checkbox_html;
				})
				->editColumn('name', function($deal)
				{
					return $deal->name_html;
				})
				->editColumn('amount', function($deal)
				{
					return $deal->amountHtml('amount');
				})
				->addColumn('deal_stage', function($deal)
				{
					return $deal->stage->name_with_probability;
				})
				->editColumn('closing_date', function($deal)
				{
					return $deal->readableDateHtml('closing_date');
				})
				->addColumn('account', function($deal)
				{
					return $deal->account->name_link;
				})
				->addColumn('contact', function($deal)
				{
					return non_property_checker($deal->contact, 'name_link');
				})
				->editColumn('deal_owner', function($deal)
				{
					return $deal->owner->name_link;
				})
				->addColumn('action', function($deal)
				{
					$action_permission = ['edit' => permit('deal.edit'), 'delete' => permit('deal.delete')];							
					return $deal->getCompactActionHtml('Deal', null, 'admin.deal.destroy', $action_permission);
				})
				->make(true);
	}



	public static function connectedDealData($deals, $request)
	{
		return self::of($deals)
				->editColumn('name', function($deal)
				{
					return $deal->getNameHtmlAttribute(false, false);
				})
				->editColumn('amount', function($deal)
				{
					return $deal->amountHtml('amount');
				})
				->editColumn('closing_date', function($deal)
				{
					return $deal->readableDateHtml('closing_date');
				})
				->addColumn('pipeline', function($deal)
				{
					return $deal->pipeline->name;
				})
				->addColumn('deal_stage', function($deal)
				{
					return $deal->stage_and_probability;
				})
				->editColumn('deal_owner', function($deal)
				{
					return $deal->owner->profile_html;
				})
				->addColumn('action', function($deal)
				{
					$action_permission = ['edit' => permit('deal.edit'), 'delete' => permit('deal.delete')];							
					return $deal->getCompactActionHtml('Deal', null, 'admin.deal.destroy', $action_permission, true);
				})
				->make(true);
	}



	public static function revisionData($revisions, $request)
	{
		return self::of($revisions)
				->addColumn('checkbox', function($revision)
				{
					return $revision->checkbox_html;
				})
				->addColumn('user', function($revision)
				{
					return $revision->user->linked->profile_html;
				})
				->addColumn('description', function($revision)
				{
					return $revision->description;
				})
				->addColumn('date', function($revision)
				{
					return $revision->readableDateHtml('created_at', true);
				})
				->addColumn('action', function($revision)
				{
					$action_permission = ['edit' => false, 'delete' => permit('advanced.activity_log.delete')];							
					return $revision->getCompactActionHtml('Log', null, 'admin.advanced-activity-log.destroy', $action_permission);
				})
				->make(true);
	}



	public static function notificationData($notifications, $request)
	{
		return self::of($notifications)
				->addColumn('notification_from', function($notification)
				{
					return $notification->notification_from;
				})
				->addColumn('description', function($notification)
				{
					return $notification->title . '<br>' . $notification->additional_info;
				})
				->addColumn('date', function($notification)
				{
					return $notification->created_at->format('Y-m-d h:i:s A');
				})
				->make(true);
	}



	public static function notificationCaseData($cases, $request)
	{
		return self::of($cases)
				->editColumn('web_notification', function($case)
				{
					return $case->web_notification_html;
				})
				->editColumn('email_notification', function($case)
				{
					return $case->email_notification_html;
				})
				->editColumn('sms_notification', function($case)
				{
					return $case->sms_notification_html;
				})
				->make(true);
	}



	public static function currencyData($currencies, $request)
	{
		return self::of($currencies)
				->addColumn('sequence', function($currency)
				{
					return $currency->drag_and_drop;
				})
				->editColumn('name', function($currency)
				{
					return $currency->name_html;
				})
				->editColumn('symbol', function($currency)
				{
					return $currency->symbol_ex_html;
				})
				->editColumn('exchange_rate', function($currency)
				{
					return $currency->exchange_rate_format;
				})
				->addColumn('action', function($currency)
				{
					$action_permission = ['edit' => permit('settings.currency.edit'), 'delete' => (permit('settings.currency.delete') && !$currency->base && $currency->can_delete)];
					return $currency->getCompactActionHtml('Currency', null, 'admin.administration-setting-currency.destroy', $action_permission);
				})
				->make(true);
	}



	public static function fileData($files, $request)
	{
		return self::of($files)
				->editColumn('name', function($file)
				{
					return $file->name_html;
				})
				->addColumn('uploaded_by', function($file)
				{
					return $file->updatedBy()->linked->name_link;
				})
				->editColumn('updated_at', function($file)
				{
					return $file->readableDateHtml('updated_at');
				})
				->editColumn('size', function($file)
				{
					return $file->size_html;
				})
				->addColumn('action', function($file)
				{
					return $file->getCompactActionHtml('File', null, 'admin.file.destroy');
				})
				->make(true);
	}
}