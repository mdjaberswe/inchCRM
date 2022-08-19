<?php

namespace App\Listeners;

use App\Events\LeadDeleted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LeadDeletedListener
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
     * @param  LeadDeleted  $event
     * @return void
     */
    public function handle(LeadDeleted $event)
    {
        $lead_note_info = \App\Models\NoteInfo::where('linked_type', 'lead')->whereIn('linked_id', $event->lead_ids)->pluck('id')->toArray();
        \App\Models\Note::whereIn('note_info_id', $lead_note_info)->delete();
        \App\Models\NoteInfo::where('linked_type', 'lead')->whereIn('linked_id', $event->lead_ids)->delete();
        \App\Models\Note::where('linked_type', 'lead')->whereIn('linked_id', $event->lead_ids)->delete();

        \DB::table('campaign_members')->where('member_type', 'lead')->whereIn('member_id', $event->lead_ids)->delete();
        \DB::table('event_attendees')->where('linked_type', 'lead')->whereIn('linked_id', $event->lead_ids)->delete();
    
        $db_event_ids = \App\Models\Event::where('linked_type', 'lead')->whereIn('linked_id', $event->lead_ids)->pluck('id')->toArray();
        \App\Models\Activity::where('linked_type', 'event')->whereIn('linked_id', $db_event_ids)->delete();
        \App\Models\Event::whereIn('id', $db_event_ids)->delete();
        
        $task_ids = \App\Models\Task::where('linked_type', 'lead')->whereIn('linked_id', $event->lead_ids)->pluck('id')->toArray();
        \App\Models\Activity::where('linked_type', 'task')->whereIn('linked_id', $task_ids)->delete();
        \App\Models\Task::whereIn('id', $task_ids)->delete();

        $call_ids = \App\Models\Call::where('client_type', 'lead')->whereIn('client_id', $event->lead_ids)->pluck('id')->toArray();
        \App\Models\Activity::where('linked_type', 'call')->whereIn('linked_id', $call_ids)->delete();
        \App\Models\Call::whereIn('id', $call_ids)->delete();    
    }
}
