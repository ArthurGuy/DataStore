<?php

namespace App\Providers;

use App\Events\LocationHomeStateChanged;
use App\Events\LocationLastMovementChanged;
use App\Models\Location;
use Carbon\Carbon;
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
        Location::saved(function ($location) {
            if ($location->original['home'] != $location->attributes['home']) { //if record has changed
                event(new LocationHomeStateChanged($location));
            }
        });

        Location::saved(function ($location) {
            //comparing dates so the carbon comparison is used
            if (Carbon::createFromTimestamp($location->original['last_movement'])->ne(Carbon::createFromTimestamp($location->attributes['last_movement']))) { //if record has changed
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
