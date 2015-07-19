<?php

namespace App\Providers;

use App\Events\LocationHomeStateChanged;
use App\Models\Location;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
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
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
