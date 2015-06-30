<?php

namespace App\Listeners;

use App\Events\DeviceStateChanged;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateRemoteDeviceState
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
        $device = $event->device;
        if ($device->state_type == 'binary') {

            $client = new \GuzzleHttp\Client();
            if ($device->state) {

                $response = $client->post($device->post_url_on);
                if ($response->getStatusCode() === 200) {
                    //Done
                }

            } else {

                $response = $client->post($device->post_url_off);
                if ($response->getStatusCode() === 200) {
                    //Done
                }

            }
        } else {


        }
    }
}
