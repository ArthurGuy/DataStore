<?php

namespace App\Console\Commands;

use App\Models\Device;
use Illuminate\Console\Command;

class SyncDevices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'devices:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ensure the devices state matches the system state';

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
        $devices = Device::all();
        foreach ($devices as $device) {

            if ($device->state_type == 'binary') {

                $client = new \GuzzleHttp\Client();
                if ($device->state) {

                    $this->info('Turning ' . $device->name . ' on');

                    $response = $client->post($device->post_url_on);
                    if ($response->getStatusCode() === 200) {
                        //Done
                    }

                } else {

                    $this->info('Turning ' . $device->name . ' off');

                    $response = $client->post($device->post_url_off);
                    if ($response->getStatusCode() === 200) {
                        //Done
                    }

                }
            } else {

                $this->error('Unable to update ' . $device->name . '. Unhandled type ' . $device->state_type);

            }
        }
    }
}
