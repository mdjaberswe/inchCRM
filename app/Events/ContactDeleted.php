<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ContactDeleted extends Event
{
    use SerializesModels;

    public $contact_ids;
    public $follow_parent_hierarchy;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($contact_ids, $follow_parent_hierarchy = true)
    {
        $this->contact_ids = $contact_ids;
        $this->follow_parent_hierarchy = $follow_parent_hierarchy;
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
