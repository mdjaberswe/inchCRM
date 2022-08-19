<?php

namespace App\Providers;

use Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Relation::morphMap([
            'estimate'  => \App\Models\Estimate::class,
            'invoice'   => \App\Models\Invoice::class,
            'item'      => \App\Models\Item::class,
            'item_sheet'=> \App\Models\ItemSheet::class,
            'user'      => \App\Models\User::class,
            'account'   => \App\Models\Account::class,
            'contact'   => \App\Models\Contact::class,
            'lead'      => \App\Models\Lead::class,
            'staff'     => \App\Models\Staff::class,
            'role'      => \App\Models\Role::class,
            'rolebook'  => \App\Models\RoleBook::class,
            'lead'      => \App\Models\Lead::class,
            'milestone' => \App\Models\Milestone::class,
            'project'   => \App\Models\Project::class,
            'task'      => \App\Models\Task::class,
            'payment'   => \App\Models\Payment::class,
            'expense'   => \App\Models\Expense::class,   
            'campaign'  => \App\Models\Campaign::class, 
            'deal'      => \App\Models\Deal::class,
            'deal_type' => \App\Models\DealType::class,
            'deal_stage'=> \App\Models\DealStage::class, 
            'goal'      => \App\Models\Goal::class,
            'event'     => \App\Models\Event::class,
            'reminder'  => \App\Models\Reminder::class,
            'setting'   => \App\Models\Setting::class,
            'import'    => \App\Models\Import::class,
            'chat_room' => \App\Models\ChatRoom::class,
            'currency'  => \App\Models\Currency::class,
            'note_info' => \App\Models\NoteInfo::class,
            'note'      => \App\Models\Note::class,
            'call'      => \App\Models\Call::class,
            'activity'  => \App\Models\Activity::class,
            'task_status'   => \App\Models\TaskStatus::class,
            'attach_file'   => \App\Models\AttachFile::class,
            'industry_type' => \App\Models\IndustryType::class,
            'account_type'  => \App\Models\AccountType::class,
            'contact_type'  => \App\Models\ContactType::class,
            'chat_sender'   => \App\Models\ChatSender::class,
            'chat_receiver' => \App\Models\ChatReceiver::class,
            'notification'  => \App\Models\Notification::class,
            'event_attendee'=> \App\Models\EventAttendee::class,            
            'social_media'  => \App\Models\SocialMedia::class,
            'source'        => \App\Models\Source::class,
            'lead_stage'    => \App\Models\LeadStage::class,
            'deal_pipeline' => \App\Models\DealPipeline::class,
            'campaign_type' => \App\Models\CampaignType::class,
            'allowed_staff' => \App\Models\AllowedStaff::class,
            'filter_view'   => \App\Models\FilterView::class,
            'notification_info' => \App\Models\NotificationInfo::class, 
            'payment_method'    => \App\Models\PaymentMethod::class,            
            'expense_category'  => \App\Models\ExpenseCategory::class,  
            'chat_room_member'  => \App\Models\ChatRoomMember::class,
            'lead_score'        => \App\Models\LeadScore::class,
            'lead_score_rule'   => \App\Models\LeadScoreRule::class,
            'notification_setting' => \App\Models\NotificationSetting::class,
        ]);
        
        table_config_set('settings');

        Validator::extend('valid_domain', function($attribute, $value, $parameters, $validator)
        {
            return valid_url_or_domain($value);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
