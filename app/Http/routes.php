<?php

Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => ['auth', 'auth.type:staff']], function()
{
	Route::resource('lead', 'AdminLeadController', ['except' => ['create', 'show']]);
	Route::get('lead/{lead}/{infotype?}', ['as' => 'admin.lead.show', 'uses' => 'AdminLeadController@show']);
	Route::get('lead-kanban', ['as' => 'admin.lead.kanban', 'uses' => 'AdminLeadController@indexKanban']);
	Route::get('lead-report', ['as' => 'admin.lead.report', 'uses' => 'AdminLeadController@report']);
	Route::get('lead-data/{lead}/convert', ['as' => 'admin.lead.convert.data', 'uses' => 'AdminLeadController@convertData']);
	Route::post('lead-data', ['as' => 'admin.lead.data', 'uses' => 'AdminLeadController@leadData']);
	Route::post('lead-kanban-card/{leadstage}', ['as' => 'admin.lead.kanban.card', 'uses' => 'AdminLeadController@kanbanCard']);
	Route::post('convert-lead/{lead}', ['as' => 'admin.lead.convert', 'uses' => 'AdminLeadController@convert']);
	Route::post('lead/{lead}/single-update', ['as' => 'admin.lead.single.update', 'uses' => 'AdminLeadController@singleUpdate']);
	Route::post('lead-bulk-update', ['as' => 'admin.lead.bulk.update', 'uses' => 'AdminLeadController@bulkUpdate']);
	Route::post('lead-bulk-delete', ['as' => 'admin.lead.bulk.delete', 'uses' => 'AdminLeadController@bulkDestroy']);
	Route::post('lead-bulk-email', ['as' => 'admin.lead.bulk.email', 'uses' => 'AdminLeadController@bulkEmail']);
	Route::get('lead-report-filter/{type}', ['as' => 'admin.lead.report.filter', 'uses' => 'AdminLeadController@reportFilter']);
	Route::put('lead-report-filter/{type}', ['as' => 'admin.lead.post.report.filter', 'uses' => 'AdminLeadController@postReportFilter']);

	Route::resource('contact', 'AdminContactController', ['except' => ['create', 'show']]);
	Route::get('contact/{contact}/{infotype?}', ['as' => 'admin.contact.show', 'uses' => 'AdminContactController@show']);
	Route::post('contact-data', ['as' => 'admin.contact.data', 'uses' => 'AdminContactController@contactData']);
	Route::post('reporting-contact-data/{contact}', ['as' => 'admin.reporting.contact.data', 'uses' => 'AdminContactController@reportingContactData']);
	Route::post('participant-contact-data/{module_name}/{module_id}', ['as' => 'admin.participant.contact.data', 'uses' => 'AdminContactController@participantContactData']);
	Route::post('contact-info/{contact}/{infotype}', ['as' => 'admin.contact.info', 'uses' => 'AdminContactController@showInfo']);
	Route::post('update-contact-status', ['as' => 'admin.contact.update.status', 'uses' => 'AdminContactController@updateStatus']);
	Route::post('contact/{contact}/single-update', ['as' => 'admin.contact.single.update', 'uses' => 'AdminContactController@singleUpdate']);
	Route::post('contact-bulk-update', ['as' => 'admin.contact.bulk.update', 'uses' => 'AdminContactController@bulkUpdate']);
	Route::post('confirm-new-account/{contact}', ['as' => 'admin.contact.confirm.account', 'uses' => 'AdminContactController@confirmAccount']);
	Route::post('participant-select/{module_name?}/{module_id?}', ['as' => 'admin.participant.contact.select', 'uses' => 'AdminContactController@participantSelect']);
	Route::post('participant-add/{module_name}/{module_id}', ['as' => 'admin.participant.contact.add', 'uses' => 'AdminContactController@participantAdd']);
	Route::post('contact-bulk-delete', ['as' => 'admin.contact.bulk.delete', 'uses' => 'AdminContactController@bulkDestroy']);
	Route::post('contact-bulk-email', ['as' => 'admin.contact.bulk.email', 'uses' => 'AdminContactController@bulkEmail']);
	Route::delete('participant-contact-remove/{module_name}/{module_id}/{contact}', ['as' => 'admin.participant.contact.remove', 'uses' => 'AdminContactController@participantRemove']);

	Route::resource('account', 'AdminAccountController', ['except' => ['create', 'show']]);
	Route::get('account/{account}/{infotype?}', ['as' => 'admin.account.show', 'uses' => 'AdminAccountController@show']);
	Route::get('account-single-data', ['as' => 'admin.account.single.data', 'uses' => 'AdminAccountController@accountSingleData']);
	Route::get('account-hierarchy/{account}', ['as' => 'admin.account.hierarchy', 'uses' => 'AdminAccountController@hierarchy']);
	Route::get('account-project-data', ['as' => 'admin.account.project.data', 'uses' => 'AdminAccountController@projectsList']);
	Route::post('account-data', ['as' => 'admin.account.data', 'uses' => 'AdminAccountController@accountData']);	
	Route::post('account-contact-data/{account}', ['as' => 'admin.account.contact.data', 'uses' => 'AdminAccountController@contactData']);
	Route::post('sub-account-data/{account}', ['as' => 'admin.sub.account.data', 'uses' => 'AdminAccountController@subAccountData']);
	Route::post('account/{account}/single-update', ['as' => 'admin.account.single.update', 'uses' => 'AdminAccountController@singleUpdate']);
	Route::post('account-bulk-update', ['as' => 'admin.account.bulk.update', 'uses' => 'AdminAccountController@bulkUpdate']);
	Route::post('account-bulk-delete', ['as' => 'admin.account.bulk.delete', 'uses' => 'AdminAccountController@bulkDestroy']);
	Route::post('account-bulk-email', ['as' => 'admin.account.bulk.email', 'uses' => 'AdminAccountController@bulkEmail']);

	Route::get('hierarchy-edit-parent/{module_name}/{module_id}', ['as' => 'admin.hierarchy.edit.parent', 'uses' => 'AdminHierarchyController@editParent']);
	Route::put('hierarchy-update-parent/{module_name}/{module_id}', ['as' => 'admin.hierarchy.update.parent', 'uses' => 'AdminHierarchyController@updateParent']);
	Route::post('hierarchy-add-child/{module_name?}/{module_id?}', ['as' => 'admin.hierarchy.add.child', 'uses' => 'AdminHierarchyController@addChild']);
	Route::post('hierarchy-store-child/{module_name}/{module_id}', ['as' => 'admin.hierarchy.store.child', 'uses' => 'AdminHierarchyController@storeChild']);
	Route::post('hierarchy-remove-child', ['as' => 'admin.hierarchy.remove.child', 'uses' => 'AdminHierarchyController@removeChild']);

	Route::resource('deal', 'AdminDealController', ['except' => ['create', 'show']]);
	Route::get('deal/{deal}/{infotype?}', ['as' => 'admin.deal.show', 'uses' => 'AdminDealController@show']);
	Route::get('deal-kanban', ['as' => 'admin.deal.kanban', 'uses' => 'AdminDealController@indexKanban']);
	Route::get('deal-report', ['as' => 'admin.deal.report', 'uses' => 'AdminDealController@report']);
	Route::post('deal-data', ['as' => 'admin.deal.data', 'uses' => 'AdminDealController@dealData']);
	Route::post('deal-pipeline-kanban-view', ['as' => 'admin.deal.pipeline.kanban', 'uses' => 'AdminDealController@pipelineKanbanView']);
	Route::post('deal/{deal}/single-update', ['as' => 'admin.deal.single.update', 'uses' => 'AdminDealController@singleUpdate']);
	Route::post('deal-stage-history/{deal}', ['as' => 'admin.deal.stage.history', 'uses' => 'AdminDealController@stageHistory']);
	Route::post('connected-deal/{module_name}/{module_id}', ['as' => 'admin.connected.deal.data', 'uses' => 'AdminDealController@connectedDealData']);
	Route::post('deal-kanban-card/{dealpipeline}/{dealstage}', ['as' => 'admin.deal.kanban.card', 'uses' => 'AdminDealController@kanbanCard']);
	Route::post('deal-bulk-update', ['as' => 'admin.deal.bulk.update', 'uses' => 'AdminDealController@bulkUpdate']);
	Route::post('deal-bulk-delete', ['as' => 'admin.deal.bulk.delete', 'uses' => 'AdminDealController@bulkDestroy']);
	Route::post('deal-bulk-email', ['as' => 'admin.deal.bulk.email', 'uses' => 'AdminDealController@bulkEmail']);

	Route::get('activity', ['as' => 'admin.activity.index', 'uses' => 'AdminActivityController@index']);
	Route::post('activity-data', ['as' => 'admin.activity.data', 'uses' => 'AdminActivityController@data']);
	Route::post('activity-bulk-delete', ['as' => 'admin.activity.bulk.delete', 'uses' => 'AdminActivityController@bulkDestroy']);

	Route::resource('project', 'AdminProjectController', ['except' => ['create']]);
	Route::post('project-data', ['as' => 'admin.project.data', 'uses' => 'AdminProjectController@projectData']);
	Route::post('connected-project/{module_name}/{module_id}', ['as' => 'admin.connected.project.data', 'uses' => 'AdminProjectController@connectedProjectData']);
	Route::post('project-bulk-delete', ['as' => 'admin.project.bulk.delete', 'uses' => 'AdminProjectController@bulkDestroy']);

	Route::resource('sale-item', 'AdminItemController', ['except' => ['create', 'show']]);
	Route::post('sale-item-data', ['as' => 'admin.sale-item.data', 'uses' => 'AdminItemController@itemData']);
	Route::post('sale-item-bulk-delete', ['as' => 'admin.sale-item.bulk.delete', 'uses' => 'AdminItemController@bulkDestroy']);
	Route::post('item-data/{linked_type?}/{linked_id?}', ['as' => 'admin.item.data', 'uses' => 'AdminItemController@selectItemData']);
	Route::post('cart-item/{linked_type}/{linked_id}', ['as' => 'admin.cart.item.data', 'uses' => 'AdminItemController@cartItemData']);
	Route::post('cart-item-add/{linked_type}/{linked_id}', ['as' => 'admin.cart.item.add', 'uses' => 'AdminItemController@cartItemAdd']);
	Route::post('cart-item-update/{linked_type}/{linked_id}/{item}', ['as' => 'admin.cart.item.update', 'uses' => 'AdminItemController@cartItemUpdate']);
	Route::post('cart-item-remove/{linked_type}/{linked_id}/{item}', ['as' => 'admin.cart.item.remove', 'uses' => 'AdminItemController@cartItemRemove']);

	Route::resource('sale-estimate', 'AdminEstimateController');
	Route::post('estimate-data', ['as' => 'admin.sale-estimate.data', 'uses' => 'AdminEstimateController@estimateData']);
	Route::post('connected-estimate/{module_name}/{module_id}', ['as' => 'admin.connected.estimate.data', 'uses' => 'AdminEstimateController@connectedEstimateData']);
	Route::post('estimate-bulk-delete', ['as' => 'admin.sale-estimate.bulk.delete', 'uses' => 'AdminEstimateController@bulkDestroy']);
	Route::post('estimate-bulk-email', ['as' => 'admin.sale-estimate.bulk.email', 'uses' => 'AdminEstimateController@bulkEmail']);

	Route::resource('sale-invoice', 'AdminInvoiceController');
	Route::post('invoice-data', ['as' => 'admin.sale-invoice.data', 'uses' => 'AdminInvoiceController@invoiceData']);
	Route::post('connected-invoice/{module_name}/{module_id}', ['as' => 'admin.connected.invoice.data', 'uses' => 'AdminInvoiceController@connectedInvoiceData']);
	Route::post('invoice-bulk-delete', ['as' => 'admin.sale-invoice.bulk.delete', 'uses' => 'AdminInvoiceController@bulkDestroy']);
	Route::post('invoice-bulk-email', ['as' => 'admin.sale-invoice.bulk.email', 'uses' => 'AdminInvoiceController@bulkEmail']);

	Route::get('invoice-payment-data/{invoice}/{payment}/edit', ['as' => 'admin.invoice.payment.edit', 'uses' => 'AdminInvoiceController@paymentEdit']);
	Route::post('invoice-payment-data/{invoice}', ['as' => 'admin.invoice.payment.data', 'uses' => 'AdminInvoiceController@invoicePaymentData']);
	Route::post('invoice-payment-store/{invoice}', ['as' => 'admin.invoice.payment.store', 'uses' => 'AdminInvoiceController@paymentStore']);
	Route::put('invoice-payment-data/{invoice}/{payment}', ['as' => 'admin.invoice.payment.update', 'uses' => 'AdminInvoiceController@paymentUpdate']);

	Route::resource('call', 'AdminCallController', ['except' => ['index', 'create']]);
	Route::post('related-call/{module_name}/{module_id}', ['as' => 'admin.related.call.data', 'uses' => 'AdminCallController@callData']);

	Route::resource('task', 'AdminTaskController', ['except' => ['create', 'show']]);
	Route::get('task-kanban', ['as' => 'admin.task.kanban', 'uses' => 'AdminTaskController@indexKanban']);
	Route::get('task-calendar', ['as' => 'admin.task.calendar', 'uses' => 'AdminTaskController@indexCalendar']);
	Route::get('task/{task}/{infotype?}', ['as' => 'admin.task.show', 'uses' => 'AdminTaskController@show']);
	Route::post('task-data', ['as' => 'admin.task.data', 'uses' => 'AdminTaskController@taskData']);
	Route::post('task-calendar-data', ['as' => 'admin.task.calendar.data', 'uses' => 'AdminTaskController@calendarData']);
	Route::post('task-calendar-update-position', ['as' => 'admin.task.calendar.update.position', 'uses' => 'AdminTaskController@updateCalendarPosition']);
	Route::post('connected-task/{module_name}/{module_id}', ['as' => 'admin.connected.task.data', 'uses' => 'AdminTaskController@connectedTaskData']);
	Route::post('task/{task}/single-update', ['as' => 'admin.task.single.update', 'uses' => 'AdminTaskController@singleUpdate']);
	Route::post('task-closed-reopen/{task}', ['as' => 'admin.task.closed.reopen', 'uses' => 'AdminTaskController@closedOrReopen']);
	Route::post('task-kanban-card/{taskstatus}', ['as' => 'admin.task.kanban.card', 'uses' => 'AdminTaskController@kanbanCard']);
	Route::post('task-bulk-update', ['as' => 'admin.task.bulk.update', 'uses' => 'AdminTaskController@bulkUpdate']);
	Route::post('task-bulk-delete', ['as' => 'admin.task.bulk.delete', 'uses' => 'AdminTaskController@bulkDestroy']);

	Route::resource('event', 'AdminEventController', ['except' => ['create']]);
	Route::post('event-data', ['as' => 'admin.event.data', 'uses' => 'AdminEventController@eventData']);
	Route::post('connected-event/{module_name}/{module_id}', ['as' => 'admin.connected.event.data', 'uses' => 'AdminEventController@connectedEventData']);
	Route::post('event-attendee-data/{event}', ['as' => 'admin.event.attendee.data', 'uses' => 'AdminEventController@eventAttendeeData']);
	Route::post('event-update-position', ['as' => 'admin.event.update.position', 'uses' => 'AdminEventController@updatePosition']);

	Route::resource('campaign', 'AdminCampaignController', ['except' => ['create']]);
	Route::post('campaign-data', ['as' => 'admin.campaign.data', 'uses' => 'AdminCampaignController@campaignData']);
	Route::post('campaign-bulk-delete', ['as' => 'admin.campaign.bulk.delete', 'uses' => 'AdminCampaignController@bulkDestroy']);
	Route::post('campaign-data-select/{member_type?}/{member_id?}', ['as' => 'admin.campaign.data.select', 'uses' => 'AdminCampaignController@campaignSelectData']);
	Route::post('member-campaign/{member_type}/{member_id}', ['as' => 'admin.member.campaign.data', 'uses' => 'AdminCampaignController@memberCampaignData']);
	Route::post('member-campaign-add/{member_type}/{member_id}', ['as' => 'admin.member.campaign.add', 'uses' => 'AdminCampaignController@memberCampaignAdd']);
	Route::get('member-campaign-edit/{member_type}/{member_id}/{campaign}', ['as' => 'admin.member.campaign.edit', 'uses' => 'AdminCampaignController@memberCampaignEdit']);
	Route::put('member-campaign-update/{member_type}/{member_id}/{campaign}', ['as' => 'admin.member.campaign.update', 'uses' => 'AdminCampaignController@memberCampaignUpdate']);
	Route::delete('member-campaign-remove/{member_type}/{member_id}/{campaign}', ['as' => 'admin.member.campaign.remove', 'uses' => 'AdminCampaignController@memberCampaignRemove']);

	Route::resource('finance-expense', 'AdminExpenseController', ['except' => ['create', 'show']]);
	Route::post('expense-data', ['as' => 'admin.finance-expense.data', 'uses' => 'AdminExpenseController@expenseData']);
	Route::post('expense-bulk-delete', ['as' => 'admin.finance-expense.bulk.delete', 'uses' => 'AdminExpenseController@bulkDestroy']);

	Route::resource('finance-payment', 'AdminPaymentController', ['except' => ['create', 'store', 'show']]);
	Route::post('payment-data', ['as' => 'admin.finance-payment.data', 'uses' => 'AdminPaymentController@paymentData']);
	Route::post('payment-bulk-delete', ['as' => 'admin.finance-payment.bulk.delete', 'uses' => 'AdminPaymentController@bulkDestroy']);

	Route::resource('advanced-goal', 'AdminGoalController', ['except' => ['create'], 'parameters' => ['advanced-goal' => 'goal']]);
	Route::post('goal-data', ['as' => 'admin.advanced-goal.data', 'uses' => 'AdminGoalController@goalData']);
	Route::post('goal-bulk-delete', ['as' => 'admin.advanced-goal.bulk.delete', 'uses' => 'AdminGoalController@bulkDestroy']);

	Route::resource('advanced-activity-log', 'AdminRevisionController', ['only' => ['index', 'destroy'], 'parameters' => ['advanced-activity-log' => 'revision']]);
	Route::post('activity-log-data', ['as' => 'admin.advanced-activity-log.data', 'uses' => 'AdminRevisionController@revisionData']);
	Route::post('activity-log-bulk-delete', ['as' => 'admin.advanced-activity-log.bulk.delete', 'uses' => 'AdminRevisionController@bulkDestroy']);

	Route::get('note-data/{type}', ['as' => 'admin.note.data', 'uses' => 'AdminNoteController@getData']);
	Route::post('note-store', ['as' => 'admin.note.store', 'uses' => 'AdminNoteController@store']);
	Route::get('note-edit/{note}', ['as' => 'admin.note.edit', 'uses' => 'AdminNoteController@edit']);
	Route::post('note-update/{note}', ['as' => 'admin.note.update', 'uses' => 'AdminNoteController@update']);
	Route::post('note-pin/{note}', ['as' => 'admin.note.pin', 'uses' => 'AdminNoteController@pin']);
	Route::delete('note-destroy/{note}', ['as' => 'admin.note.destroy', 'uses' => 'AdminNoteController@destroy']);

	Route::get('import-csv', ['as' => 'admin.import.csv', 'uses' => 'AdminImportController@getCsv']);
	Route::post('import-map', ['as' => 'admin.import.map', 'uses' => 'AdminImportController@map']);
	Route::post('import-post', ['as' => 'admin.import.post', 'uses' => 'AdminImportController@import']);

	Route::get('file-show/{attachfile}/{filename}/{download?}', ['as' => 'admin.file.show', 'uses' => 'AdminFileController@show']);
	Route::post('file-data/{linked_type}/{linked_id}', ['as' => 'admin.file.data', 'uses' => 'AdminFileController@fileData']);
	Route::post('file-upload', ['as' => 'admin.file.upload', 'uses' => 'AdminFileController@upload']);
	Route::post('avatar-upload', ['as' => 'admin.avatar.upload', 'uses' => 'AdminFileController@uploadAvatar']);
	Route::post('file-store', ['as' => 'admin.file.store', 'uses' => 'AdminFileController@store']);
	Route::post('link-store', ['as' => 'admin.link.store', 'uses' => 'AdminFileController@linkStore']);
	Route::post('file-remove', ['as' => 'admin.file.remove', 'uses' => 'AdminFileController@remove']);
	Route::delete('file-destroy/{attachfile}', ['as' => 'admin.file.destroy', 'uses' => 'AdminFileController@destroy']);

	Route::resource('notification', 'AdminNotificationController', ['only' => ['index']]);
	Route::post('notification-data', ['as' => 'admin.notification.data', 'uses' => 'AdminNotificationController@notificationData']);
	Route::post('notification-read', ['as' => 'admin.notification.read', 'uses' => 'AdminNotificationController@read']);
	Route::post('realtime-notification', ['as' => 'admin.notification.realtime', 'uses' => 'AdminNotificationController@realtimeNotification']);

	Route::resource('message', 'AdminMessageController', ['only' => ['index', 'store']]);
	Route::get('message/{chatroom}', ['as' => 'admin.message.chatroom', 'uses' => 'AdminMessageController@chatroom']);
	Route::post('message/chatroom/history', ['as' => 'admin.message.chatroom.history', 'uses' => 'AdminMessageController@chatroomHistory']);
	Route::post('message-read', ['as' => 'admin.message.read', 'uses' => 'AdminMessageController@read']);	

	Route::resource('administration-dropdown-leadstage', 'AdminLeadStageController', ['except' => ['create', 'show'], 'parameters' => ['administration-dropdown-leadstage' => 'leadstage']]);
	Route::post('leadstage-data', ['as' => 'admin.leadstage.data', 'uses' => 'AdminLeadStageController@leadStageData']);

	Route::resource('administration-dropdown-source', 'AdminSourceController', ['except' => ['create', 'show'], 'parameters' => ['administration-dropdown-source' => 'source']]);
	Route::post('source-data', ['as' => 'admin.source.data', 'uses' => 'AdminSourceController@sourceData']);

	Route::resource('administration-dropdown-contacttype', 'AdminContactTypeController', ['except' => ['create', 'show'], 'parameters' => ['administration-dropdown-contacttype' => 'contacttype']]);
	Route::post('contacttype-data', ['as' => 'admin.contacttype.data', 'uses' => 'AdminContactTypeController@contactTypeData']);

	Route::resource('administration-dropdown-accounttype', 'AdminAccountTypeController', ['except' => ['create', 'show'], 'parameters' => ['administration-dropdown-accounttype' => 'accounttype']]);
	Route::post('accounttype-data', ['as' => 'admin.accounttype.data', 'uses' => 'AdminAccountTypeController@accountTypeData']);

	Route::resource('administration-dropdown-industrytype', 'AdminIndustryTypeController', ['except' => ['create', 'show'], 'parameters' => ['administration-dropdown-industrytype' => 'industrytype']]);
	Route::post('industrytype-data', ['as' => 'admin.industrytype.data', 'uses' => 'AdminIndustryTypeController@industryTypeData']);

	Route::resource('administration-dropdown-campaigntype', 'AdminCampaignTypeController', ['except' => ['create', 'show'], 'parameters' => ['administration-dropdown-campaigntype' => 'campaigntype']]);
	Route::post('campaigntype-data', ['as' => 'admin.campaigntype.data', 'uses' => 'AdminCampaignTypeController@campaignTypeData']);

	Route::resource('administration-dropdown-dealtype', 'AdminDealTypeController', ['except' => ['create', 'show'], 'parameters' => ['administration-dropdown-dealtype' => 'dealtype']]);
	Route::post('dealtype-data', ['as' => 'admin.dealtype.data', 'uses' => 'AdminDealTypeController@dealtypeData']);

	Route::resource('administration-dropdown-dealstage', 'AdminDealStageController', ['except' => ['create', 'show'], 'parameters' => ['administration-dropdown-dealstage' => 'dealstage']]);
	Route::post('dealstage-data', ['as' => 'admin.dealstage.data', 'uses' => 'AdminDealStageController@dealstageData']);

	Route::resource('administration-dropdown-dealpipeline', 'AdminDealPipelineController', ['except' => ['create', 'show'], 'parameters' => ['administration-dropdown-dealpipeline' => 'dealpipeline']]);
	Route::get('dealpipeline-stage-dropdown/{pipeline_id?}', ['as' => 'admin.dealpipeline.stage.dropdown', 'uses' => 'AdminDealPipelineController@pipelineStageDropdown']);
	Route::post('dealpipeline-data', ['as' => 'admin.dealpipeline.data', 'uses' => 'AdminDealPipelineController@pipelineData']);
	Route::post('dealpipeline-stage-data/{pipeline_id?}/{stage_ids?}', ['as' => 'admin.dealpipeline.stage.data', 'uses' => 'AdminDealPipelineController@pipelineStageData']);

	Route::resource('administration-dropdown-taskstatus', 'AdminTaskStatusController', ['except' => ['create', 'show'], 'parameters' => ['administration-dropdown-taskstatus' => 'taskstatus']]);
	Route::post('taskstatus-data', ['as' => 'admin.taskstatus.data', 'uses' => 'AdminTaskStatusController@taskstatusData']);

	Route::resource('administration-dropdown-expensecategory', 'AdminExpenseCategoryController', ['except' => ['create', 'show'], 'parameters' => ['administration-dropdown-expensecategory' => 'expensecategory']]);
	Route::post('expensecategory-data', ['as' => 'admin.expensecategory.data', 'uses' => 'AdminExpenseCategoryController@expenseCategoryData']);

	Route::get('administration-setting-general', ['as' => 'admin.administration-setting.general', 'uses' => 'AdminSettingController@index']);
	Route::post('setting-general-post', ['as' => 'setting.general.post', 'uses' => 'AdminSettingController@postGeneral']);

	Route::get('administration-setting-company', ['as' => 'admin.administration-setting.company', 'uses' => 'AdminSettingController@company']);
	Route::post('setting-company-post', ['as' => 'setting.company.post', 'uses' => 'AdminSettingController@postCompany']);

	Route::get('administration-setting-email', ['as' => 'admin.administration-setting.email', 'uses' => 'AdminSettingController@email']);
	Route::post('setting-email-post', ['as' => 'setting.email.post', 'uses' => 'AdminSettingController@postEmail']);

	Route::get('administration-setting-sms', ['as' => 'admin.administration-setting.sms', 'uses' => 'AdminSettingController@sms']);
	Route::post('setting-sms-post', ['as' => 'setting.sms.post', 'uses' => 'AdminSettingController@postSms']);

	Route::resource('administration-setting-currency', 'AdminCurrencyController', ['except' => ['create', 'show'], 'parameters' => ['administration-setting-currency' => 'currency']]);
	Route::post('currency-data', ['as' => 'admin.currency.data', 'uses' => 'AdminCurrencyController@currencyData']);
	Route::post('update-base-currency/{currency}', ['as' => 'admin.currency.base.update', 'uses' => 'AdminCurrencyController@updateBase']);

	Route::get('administration-setting-payment', ['as' => 'admin.administration-setting.payment', 'uses' => 'AdminSettingController@payment']);
	Route::post('setting-payment/{method}', ['as' => 'admin.setting.payment.info', 'uses' => 'AdminSettingController@paymentMethod']);
	Route::post('setting-payment-update/{method}', ['as' => 'admin.setting.payment.update', 'uses' => 'AdminSettingController@updatePaymentMethod']);

	Route::resource('administration-setting-offline-payment', 'AdminPaymentMethodController', ['except' => ['create', 'show'], 'parameters' => ['administration-setting-offline-payment' => 'paymentmethod']]);
	Route::post('paymentmethod-data', ['as' => 'admin.paymentmethod.data', 'uses' => 'AdminPaymentMethodController@paymentMethodData']);
	Route::post('update-paymentmethod-status', ['as' => 'admin.update.paymentmethod.status', 'uses' => 'AdminPaymentMethodController@updateStatus']);

	Route::resource('administration-setting-lead-scoring-rule', 'AdminLeadScoreRuleController', ['except' => ['create', 'show', 'destroy'], 'parameters' => ['administration-setting-lead-scoring-rule' => 'lead-score-rule']]);
	Route::get('setting-classify-lead-score', ['as' => 'admin.classify.lead.score', 'uses' => 'AdminLeadScoreRuleController@classifyLeadScore']);
	Route::post('setting-classify-lead-score', ['as' => 'admin.post.classify.lead.score', 'uses' => 'AdminLeadScoreRuleController@postClassifyLeadScore']);
	Route::post('lead-score-rule-data', ['as' => 'admin.administration-setting-lead-scoring-rule.data', 'uses' => 'AdminLeadScoreRuleController@ruleData']);
	Route::delete('administration-setting-lead-scoring-rule/{id}', ['as' => 'admin.administration-setting-lead-scoring-rule.destroy', 'uses' => 'AdminLeadScoreRuleController@destroy']);

	Route::get('administration-setting-notification', ['as' => 'admin.administration-setting.notification', 'uses' => 'AdminSettingController@notification']);
	Route::post('setting-notification/{type}', ['as' => 'admin.setting.notification.type', 'uses' => 'AdminSettingController@notificationType']);
	Route::post('setting-notification-case', ['as' => 'admin.setting.notification.case', 'uses' => 'AdminSettingController@notificationCaseData']);
	Route::post('update-notification-case', ['as' => 'admin.update.notification.case', 'uses' => 'AdminSettingController@updateNotificationCase']);
	Route::post('bulk-update-notification-case', ['as' => 'admin.bulk.update.notification.case', 'uses' => 'AdminSettingController@bulkUpdateNotificationCase']);
	Route::post('setting-update-pusher', ['as' => 'admin.setting.update.pusher', 'uses' => 'AdminSettingController@updatePusher']);

	Route::get('administration-setting-cronjob', ['as' => 'admin.administration-setting.cronjob', 'uses' => 'AdminSettingController@cronjob']);

	Route::resource('user', 'AdminUserController', ['except' => ['create', 'show']]);
	Route::get('user/{user}/{infotype?}', ['as' => 'admin.user.show', 'uses' => 'AdminUserController@show']);
	Route::get('user-profile-card', ['as' => 'admin.user.profilecard', 'uses' => 'AdminUserController@indexProfile']);
	Route::get('allowed-user-data/{type}/{id}', ['as' => 'admin.allowed.type.data', 'uses' => 'AdminUserController@allowedTypeData']);
	Route::post('user-data', ['as' => 'admin.user.data', 'uses' => 'AdminUserController@userData']);
	Route::post('allowed-user-data', ['as' => 'admin.allowed.user.data', 'uses' => 'AdminUserController@allowedUserData']);
	Route::post('allowed-user/{type}/{id}', ['as' => 'admin.post.allowed.user', 'uses' => 'AdminUserController@postAllowedUser']);
	Route::post('user-status/{user}', ['as' => 'admin.user.status', 'uses' => 'AdminUserController@updateStatus']);
	Route::post('user-password/{user}', ['as' => 'admin.user.password', 'uses' => 'AdminUserController@updatePassword']);
	Route::post('user-image/{user}', ['as' => 'admin.user.image', 'uses' => 'AdminUserController@updateImage']);
	Route::post('user-info/{user}/{infotype}', ['as' => 'admin.user.info', 'uses' => 'AdminUserController@userInfo']);
	Route::post('user-info-update/{user}/{infotype}', ['as' => 'admin.user.info.update', 'uses' => 'AdminUserController@updateUserInfo']);
	Route::post('user-project/{user}', ['as' => 'admin.user.project.data', 'uses' => 'AdminUserController@projectData']);
	Route::post('user-task/{user}', ['as' => 'admin.user.task.data', 'uses' => 'AdminUserController@taskData']);
	Route::post('user-bulk-delete', ['as' => 'admin.user.bulk.delete', 'uses' => 'AdminUserController@bulkDestroy']);
	Route::post('user-bulk-status', ['as' => 'admin.user.bulk.status', 'uses' => 'AdminUserController@bulkStatus']);
	Route::post('user-message', ['as' => 'admin.user.message', 'uses' => 'AdminUserController@message']);

	Route::resource('role', 'AdminRoleController');
	Route::post('role-users/{role}', ['as' => 'admin.role.user.list', 'uses' => 'AdminRoleController@usersList']);
	Route::post('role-data', ['as' => 'admin.role.data', 'uses' => 'AdminRoleController@roleData']);
	Route::post('role-bulk-delete', ['as' => 'admin.role.bulk.delete', 'uses' => 'AdminRoleController@bulkDestroy']);

	Route::get('images/{img}', ['as' => 'admin.image', 'uses' => 'AdminBaseController@image']);
	Route::get('dropdown-list', ['as' => 'admin.dropdown.list', 'uses' => 'AdminBaseController@dropdownList']);
	Route::get('dropdown-append-list/{parent}/{child}', ['as' => 'admin.dropdown.append.list', 'uses' => 'AdminBaseController@dropdownAppendList']);
	Route::get('view-toggle/{module_name}', ['as' => 'admin.view.toggle', 'uses' => 'AdminBaseController@viewToggle']);
	Route::get('view-content', ['as' => 'admin.view.content', 'uses' => 'AdminBaseController@viewContent']);
	Route::post('tab/{module_name}/{module_id}/{tab}', ['as' => 'admin.tab.content', 'uses' => 'AdminBaseController@tabContent']);

	Route::get('filter-form-content/{module_name}', ['as' => 'admin.filter.form.content', 'uses' => 'AdminBaseController@filterFormContent']);
	Route::get('view/edit/{filterview}', ['as' => 'admin.view.edit', 'uses' => 'AdminBaseController@viewEdit']);
	Route::put('view/update/{filterview}', ['as' => 'admin.view.update', 'uses' => 'AdminBaseController@viewUpdate']);
	Route::post('filter-form-post/{module_name}', ['as' => 'admin.filter.form.post', 'uses' => 'AdminBaseController@filterFormPost']);
	Route::post('save-view/{module_name}', ['as' => 'admin.view.store', 'uses' => 'AdminBaseController@viewStore']);
	Route::post('dropdown-view/{filterview_id?}', ['as' => 'admin.view.dropdown', 'uses' => 'AdminBaseController@viewDropdown']);
	Route::delete('delete-view/{filterview}', ['as' => 'admin.view.destroy', 'uses' => 'AdminBaseController@viewDestroy']);

	Route::get('kanban-reorder', ['as' => 'admin.kanban.reorder', 'uses' => 'AdminBaseController@kanbanReorder']);
	Route::post('dropdown-reorder', ['as' => 'admin.dropdown.reorder', 'uses' => 'AdminBaseController@dropdownReorder']);
});

Route::group(['middleware' => 'auth'], function()
{
	Route::get('/', ['as' => 'home', 'uses' => 'HomeController@index']);	
	Route::get('set-sidenav-status', ['as' => 'sidenav.status', 'uses' => 'HomeController@setSidenavStatus']);
});

Route::group(['namespace' => 'Auth'], function()
{
	Route::get('signin', ['as' => 'auth.signin', 'uses' => 'AuthController@signin']);
	Route::post('signin', ['as' => 'auth.signin.post', 'uses' => 'AuthController@postSignin']);
	Route::get('signout', ['as' => 'auth.signout', 'uses' => 'AuthController@signout']);
});

Route::get('test', 'TestController@index');