<?php

namespace App\Events;

use App\Events\Event;
use App\Models\Device;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class DeviceStateChanged extends Event
{
    use SerializesModels;

    /**
     * @var Device
     */
    public $device;

    /**
     * Create a new event instance.
     *
     * @param Device $device
     */
    public function __construct(Device $device)
    {
        $this->device = $device;
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
