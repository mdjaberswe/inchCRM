<?php

namespace App\Listeners;

use App\Events\ContactDeleted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContactDeletedListener
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
     * @param  ContactDeleted  $event
     * @return void
     */
    public function handle(ContactDeleted $event)
    {
        \App\Models\Lead::whereIn('converted_contact_id', $event->contact_ids)->update(['converted_contact_id' => null]);
        \App\Models\Deal::whereIn('contact_id', $event->contact_ids)->update(['contact_id' => null]);
        \App\Models\Estimate::whereIn('contact_id', $event->contact_ids)->update(['contact_id' => null]);
        \App\Models\Invoice::whereIn('contact_id', $event->contact_ids)->update(['contact_id' => null]);

        $contact_note_info = \App\Models\NoteInfo::where('linked_type', 'contact')->whereIn('linked_id', $event->contact_ids)->pluck('id')->toArray();
        \App\Models\Note::whereIn('note_info_id', $contact_note_info)->delete();
        \App\Models\NoteInfo::where('linked_type', 'contact')->whereIn('linked_id', $event->contact_ids)->delete();
        \App\Models\Note::where('linked_type', 'contact')->whereIn('linked_id', $event->contact_ids)->delete();

        \DB::table('project_contact')->whereIn('contact_id', $event->contact_ids)->delete();
        \DB::table('campaign_members')->where('member_type', 'contact')->whereIn('member_id', $event->contact_ids)->delete();
        \DB::table('event_attendees')->where('linked_type', 'contact')->whereIn('linked_id', $event->contact_ids)->delete();

        $db_event_ids = \App\Models\Event::where('linked_type', 'contact')->whereIn('linked_id', $event->contact_ids)->pluck('id')->toArray();
        \App\Models\Activity::where('linked_type', 'event')->whereIn('linked_id', $db_event_ids)->delete();
        \App\Models\Event::whereIn('id', $db_event_ids)->delete();
        
        $task_ids = \App\Models\Task::where('linked_type', 'contact')->whereIn('linked_id', $event->contact_ids)->pluck('id')->toArray();
        \App\Models\Activity::where('linked_type', 'task')->whereIn('linked_id', $task_ids)->delete();
        \App\Models\Task::whereIn('id', $task_ids)->delete();

        $call_ids = \App\Models\Call::where('client_type', 'contact')->whereIn('client_id', $event->contact_ids)->pluck('id')->toArray();
        \App\Models\Activity::where('linked_type', 'call')->whereIn('linked_id', $call_ids)->delete();
        \App\Models\Call::whereIn('id', $call_ids)->delete();              

        if($event->follow_parent_hierarchy) :
            $child_contacts = \App\Models\Contact::whereIn('parent_id', $event->contact_ids)->get();
            if($child_contacts->count()) :
                foreach($child_contacts as $child_contact) :
                    $child_contact->update(['parent_id' => $child_contact->closest_parent_id]);
                endforeach; 
            endif;
        endif;      
    }
}
