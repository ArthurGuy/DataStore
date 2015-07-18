<?php

namespace App\Providers;

use App\Events\LocationHomeStateChanged;
use App\Events\LocationLastMovementChanged;
use App\Models\Device;
use App\Models\Location;
use Illuminate\Support\ServiceProvider;

class LocationObserverServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        /*
        Location::saved(function ($location) {
            if ($location->original['home'] != $location->attributes['home']) { //if record has changed
                event(new LocationHomeStateChanged($location));
            }
        });

        Location::saved(function ($location) {
            //comparing dates so the carbon comparison is used
            if ($location->original['last_movement']->ne($location->attributes['last_movement'])) { //if record has changed
                event(new LocationLastMovementChanged($location));
            }
        });
        */
        Device::saved(function() {

        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
