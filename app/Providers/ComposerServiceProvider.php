<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use View;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('layouts.master', 'App\Http\Composers\AdminViewComposer');
        View::composer(['admin.user.partials.form', 'admin.user.partials.modal-message'], 'App\Http\Composers\AdminViewComposer@userForm');
        View::composer(['admin.user.show', 'admin.user.partials.tabs.*'], 'App\Http\Composers\AdminViewComposer@userInformation');
        View::composer(['admin.lead.partials.form', 'admin.lead.partials.bulk-update-form', 'admin.lead.show'], 'App\Http\Composers\AdminViewComposer@leadForm');
        View::composer(['admin.lead.show', 'admin.lead.partials.tabs.*'], 'App\Http\Composers\AdminViewComposer@leadInformation');
        View::composer('admin.lead.partials.modal-convert', 'App\Http\Composers\AdminViewComposer@convertLeadForm');
        View::composer(['partials.modals.access', 'partials.modals.common-view-form'], 'App\Http\Composers\AdminViewComposer@accessModal');
        View::composer(['admin.contact.partials.form', 'admin.contact.partials.bulk-update-form'], 'App\Http\Composers\AdminViewComposer@contactForm');
        View::composer(['admin.contact.show', 'admin.contact.partials.tabs.*'], 'App\Http\Composers\AdminViewComposer@contactInformation');
        View::composer('admin.contact.partials.modal-participant', 'App\Http\Composers\AdminViewComposer@participantContact');
        View::composer(['admin.account.partials.form', 'admin.account.show', 'admin.account.partials.tabs.*', 'admin.account.partials.bulk-update-form'], 'App\Http\Composers\AdminViewComposer@accountInformation');
        View::composer(['admin.deal.partials.form', 'admin.deal.partials.bulk-update-form', 'admin.deal.show', 'admin.deal.partials.tabs.*'], 'App\Http\Composers\AdminViewComposer@dealInfo');
        View::composer('admin.deal.kanban', 'App\Http\Composers\AdminViewComposer@dealKanban');
        View::composer('partials.modals.common-add-node', 'App\Http\Composers\AdminViewComposer@hierarchyChildTable');
        View::composer('admin.sale.estimate.partials.form', 'App\Http\Composers\AdminViewComposer@estimateForm');
        View::composer('admin.sale.invoice.partials.form', 'App\Http\Composers\AdminViewComposer@invoiceForm');
        View::composer(['admin.project.partials.form', 'admin.project.show'], 'App\Http\Composers\AdminViewComposer@projectForm');
        View::composer(['admin.task.partials.form', 'admin.task.partials.bulk-update-form', 'admin.task.show', 'admin.task.partials.tabs.*'], 'App\Http\Composers\AdminViewComposer@taskForm');
        View::composer('admin.taskstatus.partials.form', 'App\Http\Composers\AdminViewComposer@taskstatusForm');
        View::composer('admin.expense.partials.form', 'App\Http\Composers\AdminViewComposer@expenseForm');
        View::composer(['admin.sale.invoice.partials.paymentform', 'admin.payment.partials.form'], 'App\Http\Composers\AdminViewComposer@paymentForm');
        View::composer('admin.campaign.partials.form', 'App\Http\Composers\AdminViewComposer@campaignForm');
        View::composer('admin.leadstage.partials.form', 'App\Http\Composers\AdminViewComposer@leadstageForm');
        View::composer('admin.source.partials.form', 'App\Http\Composers\AdminViewComposer@sourceForm');
        View::composer('admin.contacttype.partials.form', 'App\Http\Composers\AdminViewComposer@contactTypeForm');
        View::composer('admin.accounttype.partials.form', 'App\Http\Composers\AdminViewComposer@accountTypeForm');
        View::composer('admin.industrytype.partials.form', 'App\Http\Composers\AdminViewComposer@industryTypeForm');
        View::composer('admin.campaigntype.partials.form', 'App\Http\Composers\AdminViewComposer@campaigntypeForm');
        View::composer('admin.dealtype.partials.form', 'App\Http\Composers\AdminViewComposer@dealtypeForm');
        View::composer('admin.dealstage.partials.form', 'App\Http\Composers\AdminViewComposer@dealstageForm');
        View::composer('admin.dealpipeline.partials.form', 'App\Http\Composers\AdminViewComposer@dealpipelineForm');
        View::composer('admin.paymentmethod.partials.form', 'App\Http\Composers\AdminViewComposer@paymentmethodForm');
        View::composer('admin.expensecategory.partials.form', 'App\Http\Composers\AdminViewComposer@expensecategoryForm');
        View::composer('admin.goal.partials.form', 'App\Http\Composers\AdminViewComposer@goalForm');
        View::composer('admin.event.partials.form', 'App\Http\Composers\AdminViewComposer@eventForm');
        View::composer('admin.event.partials.modal-event-attendee', 'App\Http\Composers\AdminViewComposer@eventAttendee');
        View::composer('admin.setting.general', 'App\Http\Composers\AdminViewComposer@settingGeneralForm');
        View::composer('admin.setting.currency.partials.form', 'App\Http\Composers\AdminViewComposer@currencyForm');
        View::composer('admin.setting.leadscore.partials.form', 'App\Http\Composers\AdminViewComposer@leadScoreRuleForm');
        View::composer('admin.item.partials.modal-add-item', 'App\Http\Composers\AdminViewComposer@itemTable');
        View::composer('admin.item.partials.form', 'App\Http\Composers\AdminViewComposer@itemForm');
        View::composer(['admin.campaign.partials.modal-add-campaign', 'admin.campaign.partials.modal-edit-campaign'], 'App\Http\Composers\AdminViewComposer@campaignTable');
        View::composer('admin.lead.partials.*-filter-form', 'App\Http\Composers\AdminViewComposer@leadReportFilterForm');
        View::composer('partials.tabs.*', 'App\Http\Composers\AdminViewComposer@tab');
        View::composer('admin.call.partials.form', 'App\Http\Composers\AdminViewComposer@callForm');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
