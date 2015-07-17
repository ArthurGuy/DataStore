<?php

namespace App\Events;

use App\Events\Event;
use App\Models\Location;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class LocationLastMovementChanged extends Event
{
    use SerializesModels;
    /**
     * @var Location
     */
    public $location;

    /**
     * Create a new event instance.
     *
     * @param Location $location
     */
    public function __construct(Location $location)
    {
        $this->location = $location;
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
