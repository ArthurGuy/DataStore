<?php

namespace App\Providers;

use App\Events\LocationHomeStateChanged;
use App\Events\LocationLastMovementChanged;
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
        \App\Models\Location::saved(function ($location) {
            if ($location->original['home'] != $location->attributes['home']) { //if record has changed
                event(new LocationHomeStateChanged($location));
            }
        });

        \App\Models\Location::saved(function ($location) {
            //comparing dates so the carbon comparison is used
            if ($location->original['last_movement']->ne($location->attributes['last_movement'])) { //if record has changed
                event(new LocationLastMovementChanged($location));
            }
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
