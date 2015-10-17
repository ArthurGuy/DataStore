<?php

namespace App\Console\Commands;

use App\Locations\SendLocationNotification;
use App\Models\Location;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class CheckLocationLastSensorUpdate extends Command
{
    use DispatchesJobs;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'location:check-last-update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check the last updated time for broken sensors';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /** @var Location[] $locations */
        $locations = \App\Models\Location::roomsOnly()->get();
        foreach ($locations as $location) {

            if ($location->has_warning) {
                $this->info("Device warning on " . $location->name);
                $this->dispatch(new SendLocationNotification($location));
            }
        }
    }
}
