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
        /** @var Location $parentLocation */
        $parentLocation = Location::find($event->location->parent_id);
        if (($event->location->type == 'room') && $parentLocation) {
            $home = false;
            $lastMovement = false;
            foreach ($parentLocation->rooms()->get() as $room) {
                //If any room has activity set the building state to home
                if ($room->home == 1) {
                    $home = true;
                    $lastMovement = $room->last_movement;
                }
            }
            $parentLocation->home = $home;
            if ($lastMovement) {
                $parentLocation->last_movement = $lastMovement;
            }
            $parentLocation->save();
        }
    }
}
