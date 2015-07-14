<?php

namespace App\Console\Commands;

use App\Models\Location;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CalculateParentLocationValues extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'location:calculate-home-values';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate the home values based on collected data';
    /**
     * @var \App\Data\Repositories\StreamDataRepository
     */
    private $streamDataRepository;

    /**
     * Create a new command instance.
     *
     */
    public function __construct(\App\Data\Repositories\StreamDataRepository $streamDataRepository)
    {
        parent::__construct();
        $this->streamDataRepository = $streamDataRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /** @var Location[] $locations */
        $locations = Location::where('type', 'building')->get();
        foreach ($locations as $location) {
            $this->processLocation($location);
        }
    }

    /**
     * @param $location
     */
    private function processLocation($location)
    {
        $atHome           = false;
        $lastMovement     = false;
        $temperatureArray = [];
        $humidityArray    = [];

        $this->info("Processing Location: " . $location->name);
        foreach ($location->rooms()->get() as $room) {
            $this->info("  Checking " . $room->name);
            foreach ($room->sensors as $sensor) {
                $this->comment("    Fetching sensor data " . $sensor['stream'] . ':' . $sensor['location']);
                $data = $this->streamDataRepository->getRange($sensor['stream'], Carbon::now()->subMinutes(30), Carbon::now(), ['location' => $sensor['location']]);

                //Flip the array so the older values are first
                uasort($data, [$this, 'dataSort']);

                //Display the data in a table
                if (is_array($data)) {
                    $this->table(array_keys(reset($data)), $data);
                }

                //If there was any at_home values or any movement set at home to true
                foreach ($data as $value) {
                    if (isset($value['at_home']) && $value['at_home']) {
                        $atHome = true;
                        $lastMovement = $value['date'];
                    }

                    if (isset($value['movement']) && $value['movement']) {
                        $atHome = true;
                        $lastMovement = $value['date'];
                    }
                }

                //Get the temperature and humidity data from the last array item
                // Add to an array so we can get the average
                $currentData = end($data);
                if (isset($currentData['temp'])) {
                    $temperatureArray[] = $currentData['temp'];
                }
                if (isset($currentData['humidity'])) {
                    $humidityArray[] = $currentData['humidity'];
                }
            }
        }

        if ($temperatureArray) {
            $temperature           = array_sum($temperatureArray) / count($temperatureArray);
            $location->temperature = $temperature;
            $this->info("Temperature: " . $temperature);
        }
        if ($humidityArray) {
            $humidity           = array_sum($humidityArray) / count($humidityArray);
            $location->humidity = $humidity;
            $this->info("Humidity: " . $humidity);
        }

        $this->info($location->name . " " . $atHome);
        $location->home = $atHome;

        if ($lastMovement) {
            $location->last_movement = $lastMovement;
        }
        $location->save();
    }

    private function dataSort($a, $b) {
        return ($a['date'] > $b['date']);
    }
}
