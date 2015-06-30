<?php

namespace App\Listeners;

use App\Events\LocationHomeStateChanged;
use App\Models\Location;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateLocationHomeState implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param LocationHomeStateChanged $event
     */
    public function handle(LocationHomeStateChanged $event)
    {
        if (($event->location->type == 'room') && (!empty($event->location->parent_id))) {
            $parentLocation = Location::findOrFail($event->location->parent_id);
            $home = false;
            foreach ($parentLocation->rooms() as $room) {
                //If any room has activity set the building state to home
                if ($room->home) {
                    $home = true;
                }
            }
            $parentLocation->home = $home;
            $parentLocation->save();
        }
    }
}
