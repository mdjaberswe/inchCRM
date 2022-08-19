<?php

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
	public function run()
	{
		Permission::truncate();

		$permissions = ['module.dashboard' => ['Dashboard Module', 'open', 'general', 'basic'],
						'module.report' => ['Report Module', 'open', 'general', 'basic'],											
						'module.lead' => ['Lead Module', 'open', 'general', 'basic'],
						'module.contact' => ['Contact Module', 'open', 'general', 'basic'],						
						'module.account' => ['Account Module', 'open', 'general', 'basic'],
						'module.project' => ['Project Module', 'open', 'general', 'basic'],
						'module.task' => ['Task Module', 'open', 'general', 'basic'],
						'module.campaign' => ['Campaign Module', 'open', 'general', 'basic'],
						'module.deal' => ['Deal Module', 'open', 'general', 'basic'],
						'module.sale' => ['Sale Module', 'open', 'general', 'basic'],
						'module.finance' => ['Finance Module', 'open', 'general', 'basic'],
						'module.advanced' => ['Advanced Module', 'open', 'general', 'basic'],						

						// Report => module.permission
						'report.campaign' => ['Manage Campaign Report', 'open', 'general', 'basic'],
						'report.lead' => ['Manage Lead Report', 'open', 'general', 'basic'],
						'report.account' => ['Manage Account Report', 'open', 'general', 'basic'],
						'report.project' => ['Manage Project Report', 'open', 'general', 'basic'],
						'report.sale' => ['Manage Sale Report', 'open', 'general', 'basic'],
						'report.expense' => ['Manage Expense Report', 'open', 'general', 'basic'],
						'report.expense_vs_income' => ['Manage Expense Vs Income', 'open', 'general', 'basic'],

						// Lead => module.permission
						'lead.view' => ['View Lead List', 'open', 'general', 'basic'],
						'lead.create' => ['Create New Lead', 'open', 'general', 'basic'],
						'lead.edit' => ['Edit Lead Record', 'open', 'general', 'basic'],
						'lead.delete' => ['Delete Lead', 'open', 'general', 'basic'],	

						// Lead => module.permission
						'contact.view' => ['View Contact List', 'open', 'general', 'basic'],
						'contact.create' => ['Create New Contact', 'open', 'general', 'basic'],
						'contact.edit' => ['Edit Contact Record', 'open', 'general', 'basic'],
						'contact.delete' => ['Delete Contact', 'open', 'general', 'basic'],						

						// Account => module.permission
						'account.view' => ['View Account List', 'open', 'general', 'basic'],
						'account.create' => ['Create New Account', 'open', 'general', 'basic'],
						'account.edit' => ['Edit Account Record', 'open', 'general', 'basic'],
						'account.delete' => ['Delete Account', 'open', 'general', 'basic'],
						
						// Project => module.permission
						'project.view' => ['View Project List', 'open', 'general', 'basic'],
						'project.create' => ['Create New Project', 'open', 'general', 'basic'],
						'project.edit' => ['Edit Project', 'open', 'general', 'basic'],
						'project.delete' => ['Delete Project', 'open', 'general', 'basic'],

						// Task => module.task
						'task.view' => ['View Task List', 'open', 'general', 'basic'],
						'task.create' => ['Create New Task', 'open', 'general', 'basic'],
						'task.edit' => ['Edit Task', 'open', 'general', 'basic'],
						'task.delete' => ['Delete Task', 'open', 'general', 'basic'],

						// Campaign => module.permission
						'campaign.view' => ['View Campaign List', 'open', 'general', 'basic'],
						'campaign.create' => ['Create New Campaign', 'open', 'general', 'basic'],
						'campaign.edit' => ['Edit Campaign Record', 'open', 'general', 'basic'],
						'campaign.delete' => ['Delete Campaign', 'open', 'general', 'basic'],

						// Deal => module.permission
						'deal.view' => ['View Deal List', 'open', 'general', 'basic'],
						'deal.create' => ['Create New Deal', 'open', 'general', 'basic'],
						'deal.edit' => ['Edit Deal Record', 'open', 'general', 'basic'],
						'deal.delete' => ['Delete Deal', 'open', 'general', 'basic'],
						
						// Sale => module.sub_module.permission						
						'sale.estimate.view' => ['View Estimate List', 'open', 'general', 'basic'],
						'sale.estimate.create' => ['Create New Estimate', 'open', 'general', 'basic'],
						'sale.estimate.edit' => ['Edit Estimate Record', 'open', 'general', 'basic'],
						'sale.estimate.delete' => ['Delete Estimate', 'open', 'general', 'basic'],

						'sale.invoice.view' => ['View Invoice List', 'open', 'general', 'basic'],
						'sale.invoice.create' => ['Create New Invoice', 'open', 'general', 'basic'],
						'sale.invoice.edit' => ['Edit Invoice', 'open', 'general', 'basic'],
						'sale.invoice.delete' => ['Delete Invoice', 'open', 'general', 'basic'],

						'sale.item.view' => ['View Item List', 'open', 'general', 'basic'],
						'sale.item.create' => ['Create New Item', 'open', 'general', 'basic'],
						'sale.item.edit' => ['Edit Item', 'open', 'general', 'basic'],
						'sale.item.delete' => ['Delete Item', 'open', 'general', 'basic'],

						'sale.sales_funnel' => ['View Sales Funnel', 'open', 'general', 'basic'],

						// Finance => module.sub_module.permission
						'finance.payment.view' => ['View Payment List', 'open', 'general', 'basic'],
						'finance.payment.create' => ['Create New Payment', 'open', 'general', 'basic'],
						'finance.payment.edit' => ['Edit Payment Record', 'open', 'general', 'basic'],
						'finance.payment.delete' => ['Delete Payment', 'open', 'general', 'basic'],

						'finance.expense.view' => ['View Expense List', 'open', 'general', 'basic'],
						'finance.expense.create' => ['Create New Expense', 'open', 'general', 'basic'],
						'finance.expense.edit' => ['Edit Expense', 'open', 'general', 'basic'],
						'finance.expense.delete' => ['Delete Expense', 'open', 'general', 'basic'],
						
						// Advanced => module.sub_module.permission	
						'advanced.goal.view' => ['View Goal List', 'open', 'general', 'basic'],
						'advanced.goal.create' => ['Create New Goal', 'open', 'general', 'basic'],
						'advanced.goal.edit' => ['Edit Goal', 'open', 'general', 'basic'],
						'advanced.goal.delete' => ['Delete Goal', 'open', 'general', 'basic'],

						'advanced.calendar.view' => ['View Calendar', 'open', 'general', 'basic'],
						'advanced.calendar.create_event' => ['Create Event', 'open', 'general', 'basic'],
						'advanced.calendar.edit_event' => ['Edit Event', 'open', 'general', 'basic'],
						'advanced.calendar.delete_event' => ['Delete Event', 'open', 'general', 'basic'],

						'advanced.activity_log.view' => ['View Activity Log', 'open', 'general', 'basic'],	
						'advanced.activity_log.delete' => ['Delete Activity Log', 'open', 'general', 'basic'],

						// Tool Modules
						'module.mass_update' => ['Mass update tool module', 'open', 'general', 'tool'],
						'module.mass_delete' => ['Mass delete tool module', 'open', 'general', 'tool'],
						'module.change_owner' => ['Change owner tool module', 'open', 'general', 'tool'],
						'module.convert' => ['Convert tool module', 'open', 'general', 'tool'],
						'module.mass_convert' => ['Mass convert tool module', 'open', 'general', 'tool'],

						// Mass Update Permissions
						'mass_update.lead' => ['Mass update leads', 'open', 'general', 'tool'],
						'mass_update.contact' => ['Mass update contacts', 'open', 'general', 'tool'],
						'mass_update.account' => ['Mass update accounts', 'open', 'general', 'tool'],
						'mass_update.project' => ['Mass update projects', 'open', 'general', 'tool'],
						'mass_update.task' => ['Mass update tasks', 'open', 'general', 'tool'],
						'mass_update.campaign' => ['Mass update campaigns', 'open', 'general', 'tool'],
						'mass_update.deal' => ['Mass update deals', 'open', 'general', 'tool'],
						'mass_update.estimate' => ['Mass update estimates', 'open', 'general', 'tool'],
						'mass_update.invoice' => ['Mass update invoices', 'open', 'general', 'tool'],
						'mass_update.item' => ['Mass update items', 'open', 'general', 'tool'],
						'mass_update.payment' => ['Mass update payments', 'open', 'general', 'tool'],
						'mass_update.expense' => ['Mass update expenses', 'open', 'general', 'tool'],
						'mass_update.goal' => ['Mass update goals', 'open', 'general', 'tool'],

						// Mass Delete Permissions
						'mass_delete.lead' => ['Mass delete leads', 'open', 'general', 'tool'],
						'mass_delete.contact' => ['Mass delete contacts', 'open', 'general', 'tool'],
						'mass_delete.account' => ['Mass delete accounts', 'open', 'general', 'tool'],
						'mass_delete.project' => ['Mass delete projects', 'open', 'general', 'tool'],
						'mass_delete.task' => ['Mass delete tasks', 'open', 'general', 'tool'],
						'mass_delete.campaign' => ['Mass delete campaigns', 'open', 'general', 'tool'],
						'mass_delete.deal' => ['Mass delete deals', 'open', 'general', 'tool'],
						'mass_delete.estimate' => ['Mass delete estimates', 'open', 'general', 'tool'],
						'mass_delete.invoice' => ['Mass delete invoices', 'open', 'general', 'tool'],
						'mass_delete.item' => ['Mass delete items', 'open', 'general', 'tool'],
						'mass_delete.payment' => ['Mass delete payments', 'open', 'general', 'tool'],
						'mass_delete.expense' => ['Mass delete expenses', 'open', 'general', 'tool'],
						'mass_delete.goal' => ['Mass delete goals', 'open', 'general', 'tool'],
						'mass_delete.activity_log' => ['Mass delete activity logs', 'open', 'general', 'tool'],
						'mass_delete.user' => ['Mass delete users', 'open', 'general', 'tool'],
						'mass_delete.role' => ['Mass delete roles', 'open', 'general', 'tool'],

						// Change Owner Permissions
						'change_owner.lead' => ['Change lead owner', 'open', 'general', 'tool'],
						'change_owner.contact' => ['Change contact owner', 'open', 'general', 'tool'],
						'change_owner.account' => ['Change account owner', 'open', 'general', 'tool'],
						'change_owner.project' => ['Change project owner', 'open', 'general', 'tool'],
						'change_owner.task' => ['Change task owner', 'open', 'general', 'tool'],
						'change_owner.campaign' => ['Change campaign owner', 'open', 'general', 'tool'],
						'change_owner.event' => ['Change event owner', 'open', 'general', 'tool'],
						'change_owner.deal' => ['Change deal owner', 'open', 'general', 'tool'],
						'change_owner.estimate' => ['Change estimate owner', 'open', 'general', 'tool'],
						'change_owner.invoice' => ['Change invoice owner', 'open', 'general', 'tool'],
						'change_owner.goal' => ['Change goal owner', 'open', 'general', 'tool'],

						// Convert Permissions
						'convert.lead' => ['Convert lead to account and contact', 'open', 'general', 'tool'],
						'convert.estimate' => ['Convert estimate to invoice', 'open', 'general', 'tool'],

						// Mass Convert Permissions
						'mass_convert.lead' => ['Mass convert leads to accounts, contacts', 'open', 'general', 'tool'],
						'mass_convert.estimate' => ['Mass convert estimates to invoices', 'open', 'general', 'tool'],

						// Import Export Modules
						'module.import' => ['Import module', 'open', 'general', 'import_export'],
						'module.export' => ['Export module', 'open', 'general', 'import_export'],

						// Import Permissions
						'import.lead' => ['Import leads', 'open', 'general', 'import_export'],
						'import.contact' => ['Import contacts', 'open', 'general', 'import_export'],
						'import.account' => ['Import accounts', 'open', 'general', 'import_export'],
						'import.campaign' => ['Import campaigns', 'open', 'general', 'import_export'],
						'import.deal' => ['Import deals', 'open', 'general', 'import_export'],	
						'import.task'	=> ['Import tasks', 'open', 'general', 'import_export'],				
						'import.event'	=> ['Import events', 'open', 'general', 'import_export'],
						'import.item' => ['Import sales items', 'open', 'general', 'import_export'],	
						'import.expense' => ['Import expenses', 'open', 'general', 'import_export'],		
						'import.goal'	=> ['Import goals', 'open', 'general', 'import_export'],

						// Export Permissions
						'export.lead' => ['Export leads', 'open', 'general', 'import_export'],
						'export.contact' => ['Export contacts', 'open', 'general', 'import_export'],
						'export.account' => ['Export accounts', 'open', 'general', 'import_export'],
						'export.project' => ['Export projects', 'open', 'general', 'import_export'],
						'export.task' => ['Export tasks', 'open', 'general', 'import_export'],
						'export.campaign' => ['Export campaigns', 'open', 'general', 'import_export'],
						'export.deal' => ['Export deals', 'open', 'general', 'import_export'],
						'export.estimate' => ['Export estimates', 'open', 'general', 'import_export'],
						'export.invoice' => ['Export invoices', 'open', 'general', 'import_export'],
						'export.item' => ['Export items', 'open', 'general', 'import_export'],	
						'export.payment' => ['Export payments', 'open', 'general', 'import_export'],					
						'export.expense' => ['Export expenses', 'open', 'general', 'import_export'],
						'export.user' => ['Export users', 'open', 'general', 'import_export'],
						'export.goal' => ['Export goals', 'open', 'general', 'import_export'],						
						'export.event'	=> ['Export events', 'open', 'general', 'import_export'],
						'export.activity_log' => ['Export activity logs', 'open', 'general', 'import_export'],
						'export.role' => ['Export roles', 'open', 'general', 'import_export'],

						// Send Email Modules
						'module.send_email' => ['Send email module', 'open', 'general', 'send_email'],
						'module.mass_email' => ['Mass send email module', 'open', 'general', 'send_email'],
						'module.delete_email' => ['Delete email records module', 'open', 'general', 'send_email'],

						// Send Email Permissions
						'send_email.lead' => ['Send email to lead', 'open', 'general', 'send_email'],
						'send_email.contact' => ['Send email to contact', 'open', 'general', 'send_email'],
						'send_email.account' => ['Send email to account', 'open', 'general', 'send_email'],
						'send_email.deal' => ['Send email to deal', 'open', 'general', 'send_email'],
						'send_email.estimate' => ['Send email to estimate', 'open', 'general', 'send_email'],
						'send_email.invoice' => ['Send email to invoice', 'open', 'general', 'send_email'],

						// Mass Email Permissions
						'mass_email.lead' => ['Send mass email to leads', 'open', 'general', 'send_email'],
						'mass_email.contact' => ['Send mass email to contacts', 'open', 'general', 'send_email'],
						'mass_email.account' => ['Send mass email to accounts', 'open', 'general', 'send_email'],
						'mass_email.deal' => ['Send mass email to deals', 'open', 'general', 'send_email'],
						'mass_email.estimate' => ['Send mass email to estimates', 'open', 'general', 'send_email'],
						'mass_email.invoice' => ['Send mass email to invoices', 'open', 'general', 'send_email'],

						// Delete Email Permissions
						'delete_email.lead' => ['Delete email records from lead', 'open', 'general', 'send_email'],
						'delete_email.contact' => ['Delete email records from contact', 'open', 'general', 'send_email'],
						'delete_email.account' => ['Delete email records from account', 'open', 'general', 'send_email'],
						'delete_email.deal' => ['Delete email records from deal', 'open', 'general', 'send_email'],
						'delete_email.estimate' => ['Delete email records from estimate', 'open', 'general', 'send_email'],
						'delete_email.invoice' => ['Delete email records from invoice', 'open', 'general', 'send_email'],

						// Send SMS Modules
						'module.send_SMS' => ['Send SMS module', 'open', 'general', 'send_SMS'],
						'module.mass_SMS' => ['Mass send SMS module', 'open', 'general', 'send_SMS'],
						'module.delete_SMS' => ['Delete SMS records module', 'open', 'general', 'send_SMS'],

						// Send SMS Permissions
						'send_SMS.lead' => ['Send SMS to lead', 'open', 'general', 'send_SMS'],
						'send_SMS.contact' => ['Send SMS to contact', 'open', 'general', 'send_SMS'],
						'send_SMS.account' => ['Send SMS to account', 'open', 'general', 'send_SMS'],
						'send_SMS.deal' => ['Send SMS to deal', 'open', 'general', 'send_SMS'],
						'send_SMS.estimate' => ['Send SMS to estimate', 'open', 'general', 'send_SMS'],
						'send_SMS.invoice' => ['Send SMS to invoice', 'open', 'general', 'send_SMS'],

						// Mass SMS Permissions
						'mass_SMS.lead' => ['Send mass SMS to leads', 'open', 'general', 'send_SMS'],
						'mass_SMS.contact' => ['Send mass SMS to contacts', 'open', 'general', 'send_SMS'],
						'mass_SMS.account' => ['Send mass SMS to accounts', 'open', 'general', 'send_SMS'],
						'mass_SMS.deal' => ['Send mass SMS to deals', 'open', 'general', 'send_SMS'],
						'mass_SMS.estimate' => ['Send mass SMS to estimates', 'open', 'general', 'send_SMS'],
						'mass_SMS.invoice' => ['Send mass SMS to invoices', 'open', 'general', 'send_SMS'],

						// Delete SMS Permissions
						'delete_SMS.lead' => ['Delete SMS records from lead', 'open', 'general', 'send_SMS'],
						'delete_SMS.contact' => ['Delete SMS records from contact', 'open', 'general', 'send_SMS'],
						'delete_SMS.account' => ['Delete SMS records from account', 'open', 'general', 'send_SMS'],
						'delete_SMS.deal' => ['Delete SMS records from deal', 'open', 'general', 'send_SMS'],
						'delete_SMS.estimate' => ['Delete SMS records from estimate', 'open', 'general', 'send_SMS'],
						'delete_SMS.invoice' => ['Delete SMS records from invoice', 'open', 'general', 'send_SMS'],

						// Admin Level Modules
						'module.administration' => ['Administration Module', 'open', 'general', 'admin_level'],
						'module.settings' => ['Settings Module', 'open', 'general', 'admin_level'],
						'module.custom_dropdowns' => ['Dropdown Module', 'open', 'general', 'admin_level'],
						'module.user' => ['User Module', 'open', 'general', 'admin_level'],
						'module.role' => ['Role Module', 'open', 'general', 'admin_level'],

						// Administration => module.permission
						'administration.manage_media' => ['Manage Media', 'open', 'general', 'basic'],
						'administration.import' => ['Import', 'open', 'general', 'basic'],
						'administration.export' => ['Export', 'open', 'general', 'basic'],
						'administration.database_backup' => ['Database Backup', 'open', 'general', 'basic'],
						
						// Settings => module.permission
						'settings.general' => ['General Setting', 'open', 'general', 'admin_level'],
						'settings.company' => ['Company Setting', 'open', 'general', 'admin_level'],
						'settings.email' => ['Email Setting', 'open', 'general', 'admin_level'],
						'settings.SMS' => ['SMS Setting', 'open', 'general', 'admin_level'],
						'settings.currency.view' => ['View Currency', 'open', 'general', 'admin_level'],
						'settings.currency.create' => ['Create New Currency', 'open', 'general', 'admin_level'],
						'settings.currency.edit' => ['Edit Currency', 'open', 'general', 'admin_level'],
						'settings.currency.delete' => ['Delete Currency', 'open', 'general', 'admin_level'],	
						'settings.payment_gateway' => ['Payment Gateway Setting', 'open', 'general', 'admin_level'],
						'settings.payment_method.view' => ['View Payment Method List', 'open', 'general', 'admin_level'],
						'settings.payment_method.create' => ['Create New Payment Method', 'open', 'general', 'admin_level'],
						'settings.payment_method.edit' => ['Edit Payment Method', 'open', 'general', 'admin_level'],
						'settings.payment_method.delete' => ['Delete Payment Method', 'open', 'general', 'admin_level'],						
						'settings.lead_scoring_rule.view' => ['View Lead Scoring Rule', 'open', 'general', 'admin_level'],
						'settings.lead_scoring_rule.create' => ['Create New Lead Scoring Rule', 'open', 'general', 'admin_level'],
						'settings.lead_scoring_rule.edit' => ['Edit Lead Scoring Rule', 'open', 'general', 'admin_level'],
						'settings.lead_scoring_rule.delete' => ['Delete Lead Scoring Rule', 'open', 'general', 'admin_level'],
						'settings.notification' => ['Notification Setting', 'open', 'general', 'admin_level'],
						'settings.cron_job' => ['Cron Job Setting', 'open', 'general', 'admin_level'],
						
						// Custom Dropdowns => module.sub_module.permission
						'custom_dropdowns.lead_stage.view' => ['View Lead Stage List', 'open', 'general', 'admin_level'],
						'custom_dropdowns.lead_stage.create' => ['Create New Lead Stage', 'open', 'general', 'admin_level'],
						'custom_dropdowns.lead_stage.edit' => ['Edit Lead Stage', 'open', 'general', 'admin_level'],
						'custom_dropdowns.lead_stage.delete' => ['Delete Lead Stage', 'open', 'general', 'admin_level'],	
						
						'custom_dropdowns.source.view' => ['View Source List', 'open', 'general', 'admin_level'],
						'custom_dropdowns.source.create' => ['Create New Source', 'open', 'general', 'admin_level'],
						'custom_dropdowns.source.edit' => ['Edit Source', 'open', 'general', 'admin_level'],
						'custom_dropdowns.source.delete' => ['Delete Source', 'open', 'general', 'admin_level'],						

						'custom_dropdowns.contact_type.view' => ['View Contact Type List', 'open', 'general', 'admin_level'],
						'custom_dropdowns.contact_type.create' => ['Create New Contact Type', 'open', 'general', 'admin_level'],
						'custom_dropdowns.contact_type.edit' => ['Edit Contact Type', 'open', 'general', 'admin_level'],
						'custom_dropdowns.contact_type.delete' => ['Delete Contact Type', 'open', 'general', 'admin_level'],

						'custom_dropdowns.account_type.view' => ['View Account Type List', 'open', 'general', 'admin_level'],
						'custom_dropdowns.account_type.create' => ['Create New Account Type', 'open', 'general', 'admin_level'],
						'custom_dropdowns.account_type.edit' => ['Edit Account Type', 'open', 'general', 'admin_level'],
						'custom_dropdowns.account_type.delete' => ['Delete Account Type', 'open', 'general', 'admin_level'],

						'custom_dropdowns.industry_type.view' => ['View Industry Type List', 'open', 'general', 'admin_level'],
						'custom_dropdowns.industry_type.create' => ['Create New Industry Type', 'open', 'general', 'admin_level'],
						'custom_dropdowns.industry_type.edit' => ['Edit Industry Type', 'open', 'general', 'admin_level'],
						'custom_dropdowns.industry_type.delete' => ['Delete Industry Type', 'open', 'general', 'admin_level'],

						'custom_dropdowns.campaign_type.view' => ['View Campaign Type List', 'open', 'general', 'admin_level'],
						'custom_dropdowns.campaign_type.create' => ['Create New Campaign Type', 'open', 'general', 'admin_level'],
						'custom_dropdowns.campaign_type.edit' => ['Edit Campaign Type', 'open', 'general', 'admin_level'],
						'custom_dropdowns.campaign_type.delete' => ['Delete Campaign Type', 'open', 'general', 'admin_level'],	

						'custom_dropdowns.deal_type.view' => ['View Deal Type List', 'open', 'general', 'admin_level'],
						'custom_dropdowns.deal_type.create' => ['Create New Deal Type', 'open', 'general', 'admin_level'],
						'custom_dropdowns.deal_type.edit' => ['Edit Deal Type', 'open', 'general', 'admin_level'],
						'custom_dropdowns.deal_type.delete' => ['Delete Deal Type', 'open', 'general', 'admin_level'],

						'custom_dropdowns.deal_stage.view' => ['View Deal Stage List', 'open', 'general', 'admin_level'],
						'custom_dropdowns.deal_stage.create' => ['Create New Deal Stage', 'open', 'general', 'admin_level'],
						'custom_dropdowns.deal_stage.edit' => ['Edit Deal Stage', 'open', 'general', 'admin_level'],
						'custom_dropdowns.deal_stage.delete' => ['Delete Deal Stage', 'open', 'general', 'admin_level'],

						'custom_dropdowns.deal_pipeline.view' => ['View Deal Pipeline List', 'open', 'general', 'admin_level'],
						'custom_dropdowns.deal_pipeline.create' => ['Create New Deal Pipeline', 'open', 'general', 'admin_level'],
						'custom_dropdowns.deal_pipeline.edit' => ['Edit Deal Pipeline', 'open', 'general', 'admin_level'],
						'custom_dropdowns.deal_pipeline.delete' => ['Delete Deal Pipeline', 'open', 'general', 'admin_level'],

						'custom_dropdowns.task_status.view' => ['View Task Status List', 'open', 'general', 'admin_level'],
						'custom_dropdowns.task_status.create' => ['Create New Task Status', 'open', 'general', 'admin_level'],
						'custom_dropdowns.task_status.edit' => ['Edit Task Status', 'open', 'general', 'admin_level'],
						'custom_dropdowns.task_status.delete' => ['Delete Task Status', 'open', 'general', 'admin_level'],

						'custom_dropdowns.expense_category.view' => ['View Expense Category List', 'open', 'general', 'admin_level'],
						'custom_dropdowns.expense_category.create' => ['Create New Expense Category', 'open', 'general', 'admin_level'],
						'custom_dropdowns.expense_category.edit' => ['Edit Expense Category', 'open', 'general', 'admin_level'],
						'custom_dropdowns.expense_category.delete' => ['Delete Expense Category', 'open', 'general', 'admin_level'],

						// User => module.permission
						'user.view' => ['View User List', 'open', 'general', 'admin_level'],
						'user.create' => ['Create New User', 'preserve', 'general', 'admin_level'],
						'user.edit' => ['Edit User except login credentials & role', 'semi_preserve', 'general', 'admin_level'],
						'user.delete' => ['Delete User', 'preserve', 'general', 'admin_level'],

						// Role => module.permission
						'role.view' => ['View Role List', 'open', 'general', 'admin_level'],
						'role.create' => ['Create New Role', 'preserve', 'general', 'admin_level'],
						'role.edit' => ['Edit Role', 'preserve', 'general', 'admin_level'],
						'role.delete' => ['Delete Role', 'preserve', 'general', 'admin_level'],

						// Client portal permissions
						'client.deal.view' => ['Client deal view own only', 'open', 'client', null],
						'client.deal.view_all' => ['Client deal view all', 'open', 'client', null],
						'client.project.view' => ['Client project view own only', 'open', 'client', null],
						'client.project.view_all' => ['Client project view all', 'open', 'client', null],
						'client.estimate.view' => ['Client estimate view own only', 'open', 'client', null],
						'client.estimate.view_all' => ['Client estimate view all', 'open', 'client', null],
						'client.invoice.view' => ['Client invoice view own only', 'open', 'client', null],
						'client.invoice.view_all' => ['Client invoice view all', 'open', 'client', null]];


		foreach($permissions as $permission => $info) :
			if($last_pos = strrpos($permission, '.')) :
				$display_name = substr($permission, $last_pos+1);
				$display_name = str_replace('_', ' ', $display_name);
				$display_name = ucfirst($display_name);
			else :
				$display_name = ucfirst($permission);
			endif;

			$new_permission = new Permission;
			$new_permission->name = $permission;
			$new_permission->display_name = $display_name;
			$new_permission->description = $info[0];
			$new_permission->type = $info[1];
			$new_permission->label = $info[2];
			$new_permission->group = $info[3];
			$new_permission->save();
		endforeach;	
	}
}