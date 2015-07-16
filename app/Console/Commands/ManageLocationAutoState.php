<?php

namespace App\Console\Commands;

use App\Models\Location;
use Illuminate\Console\Command;

class ManageLocationAutoState extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'location:manage-auto-state';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the location devices based on sensor readings';

    /**
     * Create a new command instance.
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
        $locations = Location::where('mode', 'auto')->get();
        foreach ($locations as $location) {

            if ($location->heater) {

                $targetTemperature = $location->target_temperature;
                if ( ! $location->occupied()) {
                    $targetTemperature = $location->away_temperature;
                }

                if ($targetTemperature > $location->temperature) {
                    $this->info('Room to cold - turning heater on');
                    $location->heater->update(['on'=>true]);
                } else {
                    $this->info('Room to hot - turning heater off');
                    $location->heater->update(['on'=>false]);
                }
            }

            /*
            if ($location->fan) {
                if ($location->target_temperature > $location->temperature) {
                    $this->info('Room to cold - turning fan off');
                    $location->fan->update(['on'=>false]);
                } else {
                    $this->info('Room to hot - turning fan on');
                    $location->fan->update(['on'=>true]);
                }
            }
            */
        }
    }
}
