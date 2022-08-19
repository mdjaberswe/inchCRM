<?php

namespace App\Events;

use App\Events\Event;
use App\Models\Staff;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UserCreated extends Event
{
    use SerializesModels;

    public $staff;
    public $data;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Staff $staff, $data)
    {
        $this->staff = $staff;
        $this->data = $data;
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
