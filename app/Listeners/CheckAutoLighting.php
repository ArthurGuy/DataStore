<?php

namespace App\Listeners;

use App\Events\Event;
use App\Devices\UpdateLocationAutoLighting;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;

class CheckAutoLighting
{
    use DispatchesJobs;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Event  $event
     * @return void
     */
    public function handle(Event $event)
    {
        $this->dispatch(new UpdateLocationAutoLighting($event->location));
    }
}
