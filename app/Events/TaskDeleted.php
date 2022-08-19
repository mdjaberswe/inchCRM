<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TaskDeleted extends Event
{
    use SerializesModels;

    public $task_ids;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($task_ids)
    {
        $this->task_ids = $task_ids;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
