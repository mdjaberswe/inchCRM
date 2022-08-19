<?php

namespace App\Listeners;

use App\Events\AccountDeleted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AccountDeletedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  AccountDeleted  $event
     * @return void
     */
    public function handle(AccountDeleted $event)
    {
        \App\Models\Lead::whereIn('converted_account_id', $event->account_ids)->update(['converted_account_id' => null]);

        $contact_ids = \App\Models\Contact::whereIn('account_id', $event->account_ids)->pluck('id')->toArray();
        \App\Models\User::where('linked_type', 'contact')->whereIn('linked_id', $contact_ids)->update(['status' => 0]);
        \App\Models\User::where('linked_type', 'contact')->whereIn('linked_id', $contact_ids)->delete();
        \App\Models\Contact::whereIn('id', $contact_ids)->delete();
        event(new \App\Events\ContactDeleted($contact_ids, false));

        \App\Models\Deal::whereIn('account_id', $event->account_ids)->delete();
        \App\Models\Project::whereIn('account_id', $event->account_ids)->delete();
        \App\Models\Estimate::whereIn('account_id', $event->account_ids)->delete();
        \App\Models\Invoice::whereIn('account_id', $event->account_ids)->delete();
        \App\Models\Expense::whereIn('account_id', $event->account_ids)->delete();

        $account_note_info = \App\Models\NoteInfo::where('linked_type', 'account')->whereIn('linked_id', $event->account_ids)->pluck('id')->toArray();
        \App\Models\Note::whereIn('note_info_id', $account_note_info)->delete();
        \App\Models\NoteInfo::where('linked_type', 'account')->whereIn('linked_id', $event->account_ids)->delete();
        \App\Models\Note::where('linked_type', 'account')->whereIn('linked_id', $event->account_ids)->delete();

        $db_event_ids = \App\Models\Event::where('linked_type', 'account')->whereIn('linked_id', $event->account_ids)->pluck('id')->toArray();
        \App\Models\Activity::where('linked_type', 'event')->whereIn('linked_id', $db_event_ids)->delete();
        \App\Models\Event::whereIn('id', $db_event_ids)->delete();
        
        $task_ids = \App\Models\Task::where('linked_type', 'account')->whereIn('linked_id', $event->account_ids)->pluck('id')->toArray();
        \App\Models\Activity::where('linked_type', 'task')->whereIn('linked_id', $task_ids)->delete();
        \App\Models\Task::whereIn('id', $task_ids)->delete();                
        
        \App\Models\Call::where('related_type', 'account')->whereIn('related_id', $event->account_ids)->update(['related_type' => null, 'related_id' => null]);  

        $sub_accounts = \App\Models\Account::whereIn('parent_id', $event->account_ids)->get();
        if($sub_accounts->count()) :
            foreach($sub_accounts as $sub_account) :            
                $sub_account->update(['parent_id' => $sub_account->closest_parent_id]);
            endforeach; 
        endif;
    }
}
