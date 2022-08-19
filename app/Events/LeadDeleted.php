<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class LeadDeleted extends Event
{
    use SerializesModels;

    public $lead_ids;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($lead_ids)
    {
        $this->lead_ids = $lead_ids;
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
