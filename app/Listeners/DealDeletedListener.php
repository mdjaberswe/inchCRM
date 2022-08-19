<?php

namespace App\Listeners;

use App\Events\DealDeleted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class DealDeletedListener
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
     * @param  DealDeleted  $event
     * @return void
     */
    public function handle(DealDeleted $event)
    {
        \App\Models\Project::whereIn('deal_id', $event->deal_ids)->update(['deal_id' => null]);
        \App\Models\Estimate::whereIn('deal_id', $event->deal_ids)->update(['deal_id' => null]);
        \App\Models\Invoice::whereIn('deal_id', $event->deal_ids)->update(['deal_id' => null]);

        $deal_note_info = \App\Models\NoteInfo::where('linked_type', 'deal')->whereIn('linked_id', $event->deal_ids)->pluck('id')->toArray();
        \App\Models\Note::whereIn('note_info_id', $deal_note_info)->delete();
        \App\Models\NoteInfo::where('linked_type', 'deal')->whereIn('linked_id', $event->deal_ids)->delete();
        \App\Models\Note::where('linked_type', 'deal')->whereIn('linked_id', $event->deal_ids)->delete();

        $db_event_ids = \App\Models\Event::where('linked_type', 'deal')->whereIn('linked_id', $event->deal_ids)->pluck('id')->toArray();
        \App\Models\Activity::where('linked_type', 'event')->whereIn('linked_id', $db_event_ids)->delete();
        \App\Models\Event::whereIn('id', $db_event_ids)->delete();
        
        $task_ids = \App\Models\Task::where('linked_type', 'deal')->whereIn('linked_id', $event->deal_ids)->pluck('id')->toArray();
        \App\Models\Activity::where('linked_type', 'task')->whereIn('linked_id', $task_ids)->delete();
        \App\Models\Task::whereIn('id', $task_ids)->delete();

        \App\Models\Call::where('related_type', 'deal')->whereIn('related_id', $event->deal_ids)->update(['related_type' => null, 'related_id' => null]);
    }
}
