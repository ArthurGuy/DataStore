<?php

namespace App\Providers;

use App\Devices\UpdateLocationAutoLighting;
use App\Listeners\CheckAutoLighting;
use App\Listeners\UpdateLocationHomeState;
use App\Listeners\UpdateRemoteDeviceState;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\DeviceStateChanged' => [
            UpdateRemoteDeviceState::class
        ],
        'App\Events\LocationHomeStateChanged' => [
            //CheckAutoLighting::class,
            UpdateLocationAutoLighting::class
        ],
        'App\Events\LocationLastMovementChanged' => [
            UpdateLocationAutoLighting::class
        ],
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        //
    }
}
