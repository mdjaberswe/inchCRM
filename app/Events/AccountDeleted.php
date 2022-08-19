<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class AccountDeleted extends Event
{
    use SerializesModels;

    public $account_ids;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($account_ids)
    {
        $this->account_ids = $account_ids;
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
