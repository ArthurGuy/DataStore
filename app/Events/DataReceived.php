<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class DataReceived extends Event implements ShouldBroadcast
{
    use SerializesModels;

    /**
     * @var array
     */
    public $data;
    /**
     * @var
     */
    private $streamId;

    /**
     * Create a new event instance.
     *
     * @param array $data
     */
    public function __construct($streamId, array $data)
    {
        $this->data = $data;
        $this->streamId = $streamId;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return ['stream.' . $this->streamId];
    }
}
