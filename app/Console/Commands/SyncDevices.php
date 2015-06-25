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
            $client = new \GuzzleHttp\Client();
            if ($device->state) {

                $client->post($device->post_url_on);

            } else {

                $client->post($device->post_url_off);

            }
        }
    }
}
