<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\SyncDevices::class,
        \App\Console\Commands\ManageLocationAutoState::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('devices:sync')
            ->everyMinute()
            ->thenPing('http://beats.envoyer.io/heartbeat/rUvTAoCyZfuvD1o');

        $schedule->command('location:manage-auto-state')
            ->everyMinute()
            ->thenPing('http://beats.envoyer.io/heartbeat/mTKr7ltLo0DRhxB');
    }
}
