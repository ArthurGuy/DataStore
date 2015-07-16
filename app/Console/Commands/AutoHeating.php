<?php

namespace App\Console\Commands;

use App\Models\Location;
use Illuminate\Console\Command;

class AutoHeating extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'location:manage-auto-heating';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the location heating based on sensor readings';

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
        $rooms = Location::where('mode', 'auto')->where('type', 'room')->get();
        foreach ($rooms as $room) {

            if ($room->heater) {

                $targetTemperature = $room->target_temperature;
                if ( ! $room->buildingOccupied()) {
                    $targetTemperature = $room->away_temperature;
                }

                if ($targetTemperature > $room->temperature) {
                    $this->info('Room to cold - turning heater on');
                    $room->heater->update(['on'=>true]);
                } else {
                    $this->info('Room to hot - turning heater off');
                    $room->heater->update(['on'=>false]);
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
