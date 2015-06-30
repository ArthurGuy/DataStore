<?php

namespace App\Providers;

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
            UpdateLocationHomeState::class
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
