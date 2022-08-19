<?php

namespace App\Listeners;

use App\Events\TaskDeleted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class TaskDeletedListener
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
     * @param  TaskDeleted  $event
     * @return void
     */
    public function handle(TaskDeleted $event)
    {
        $task_note_info = \App\Models\NoteInfo::where('linked_type', 'task')->whereIn('linked_id', $event->task_ids)->pluck('id')->toArray();
        \App\Models\Note::whereIn('note_info_id', $task_note_info)->delete();
        \App\Models\NoteInfo::where('linked_type', 'task')->whereIn('linked_id', $event->task_ids)->delete();
        \App\Models\Note::where('linked_type', 'task')->whereIn('linked_id', $event->task_ids)->delete();
        \App\Models\Activity::where('linked_type', 'task')->whereIn('linked_id', $event->task_ids)->delete();
    }
}
