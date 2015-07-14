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
                if ($location->target_temperature > $location->temperature) {
                    $this->info('Room to cold - turning heater on');
                    $location->heater->update(['on'=>true]);
                } else {
                    $this->info('Room to hot - turning heater off');
                    $location->heater->update(['on'=>false]);
                }
            }

            if ($location->fan) {
                if ($location->target_temperature > $location->temperature) {
                    $this->info('Room to cold - turning fan off');
                    $location->fan->update(['on'=>false]);
                } else {
                    $this->info('Room to hot - turning fan on');
                    $location->fan->update(['on'=>true]);
                }
            }

            if ($location->lighting) {
                if ($location->occupied()) {
                    $this->info('At Home - Lightin On');
                    $location->lighting->update(['on'=>true]);
                } else {
                    $this->info('Away - Lighting Off');
                    $location->lighting->update(['on'=>false]);
                }
            }
        }
    }
}
