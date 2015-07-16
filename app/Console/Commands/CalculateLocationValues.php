<?php

namespace App\Console\Commands;

use App\Models\Location;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CalculateLocationValues extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'location:calculate-values';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate the location values based on collected data';
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
        $locations = Location::where('type', 'room')->get();
        foreach ($locations as $room) {
            $this->processLocation($room);
        }
    }

    /**
     * @param $room
     */
    private function processLocation($room)
    {
        $atHome           = false;
        $lastMovement     = false;
        $temperatureArray = [];
        $humidityArray    = [];
        $dataItemsChecked = 0;

        $this->info("Processing " . $room->name);
        foreach ($room->sensors as $sensor) {
            $this->comment("  Fetching sensor data " . $sensor['stream'] . ':' . $sensor['location']);
            $data = $this->streamDataRepository->getRange($sensor['stream'], Carbon::now()->subMinutes(30), Carbon::now(), ['location' => $sensor['location']]);

            //Flip the array so the older values are first
            uasort($data, [$this, 'dataSort']);

            //Display the data in a table
            if (is_array(reset($data))) {
                $this->table(array_keys(reset($data)), $data);
            }

            //If there was any at_home values or any movement set at home to true
            foreach ($data as $value) {
                if (isset($value['at_home']) && $value['at_home']) {
                    $atHome = true;
                }

                if (isset($value['movement']) && $value['movement']) {
                    $atHome = true;
                    $lastMovement = $value['date'];
                }
                $dataItemsChecked ++;
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

        if ($temperatureArray) {
            $temperature           = array_sum($temperatureArray) / count($temperatureArray);
            $room->temperature = $temperature;
            $this->info("  Temperature: " . $temperature);
        }
        if ($humidityArray) {
            $humidity           = array_sum($humidityArray) / count($humidityArray);
            $room->humidity = $humidity;
            $this->info("  Humidity: " . $humidity);
        }
        if ($dataItemsChecked > 0) {
            $this->info("  " . $room->name . " At Home: " . $atHome);
            $room->home = $atHome;

            if ($lastMovement) {
                $room->last_movement = $lastMovement;
            }
            $room->save();
        }
    }

    private function dataSort($a, $b) {
        return ($a['date'] > $b['date']);
    }
}
