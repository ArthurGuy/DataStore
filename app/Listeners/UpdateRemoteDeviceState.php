<?php

namespace App\Listeners;

use App\Events\DeviceStateChanged;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Log;

class UpdateRemoteDeviceState implements ShouldQueue
{

    /**
     * Create the event listener.
     *
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  DeviceStateChanged $event
     * @return void
     */
    public function handle(DeviceStateChanged $event)
    {
        //Log::debug("Processing device state change");
        $device = $event->device;
        $client = new \GuzzleHttp\Client();
        if ($device->value_type == 'binary') {

            if ($device->on) {

                try {
                    $response = $client->post($device->post_url_on);
                    if ($response->getStatusCode() === 200) {
                        //Done
                    }
                } catch (\Exception $e) {
                    Log::error($e);
                }

            } else {

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

            if ($device->connection_type == 'spark') {

                $command = '000,000'; //off
                if ($device->on) {
                    $command = $device->value;
                }

                try {
                    $client->post($device->post_update_url, ['form_params' => [
                        'args' => $command
                    ]]);
                } catch (\Exception $e) {
                    \Log::error($e);
                }

            } else {
                $this->error('Unable to update ' . $device->name . '. Unhandled type ' . $device->value_type);
            }
        } else {


        }
    }
}
