<?php

namespace App\Console\Commands;

use App\Models\Device;
use Illuminate\Console\Command;
use Log;

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
        /** @var Device[] $devices */
        $devices = Device::all();
        foreach ($devices as $device) {

            $client = new \GuzzleHttp\Client();

            if ($device->value_type == 'binary') {

                if ($device->on) {

                    $this->info('Turning ' . $device->name . ' on');

                    try {
                        $response = $client->post($device->post_url_on);
                        if ($response->getStatusCode() === 200) {
                            //Done
                        }
                    } catch (\Exception $e) {
                        Log::error($e);
                    }

                } else {

                    $this->info('Turning ' . $device->name . ' off');

                    try {
                        $response = $client->post($device->post_url_off);
                        if ($response->getStatusCode() === 200) {
                            //Done
                        }
                    } catch (\Exception $e) {
                        Log::error($e);
                    }

                }
            } elseif ($device->type == 'light') {

                $this->info('Updating ' . $device->connection_type . ' lighting');

                if ($device->connection_type == 'spark') {

                    $command = '000,000,000';
                    if ($device->on) {
                        $command = $device->value;
                    }

                    try {
                        $response = $client->post($device->post_update_url, ['form_params' => [
                            'args' => $command
                        ]]);
                    } catch (\Exception $e) {
                        \Log::error($e);
                    }

                } else {
                    $this->error('Unable to update ' . $device->name . '. Unhandled type ' . $device->value_type);
                }


            } else {

                $this->error('Unable to update ' . $device->name . '. Unhandled type ' . $device->value_type);

            }
        }
    }
}
