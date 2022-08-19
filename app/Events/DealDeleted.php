<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class DealDeleted extends Event
{
    use SerializesModels;

    public $deal_ids;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($deal_ids)
    {
        $this->deal_ids = $deal_ids;
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
