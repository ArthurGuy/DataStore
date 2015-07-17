<?php

namespace App\Devices;

use App\Jobs\Job;
use App\Models\Location;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Class UpdateLocationAutoLighting
 *
 * Update the lighting state of a particular location,
 *   this will turn lights on and off based on the occupied and last movement data
 *
 * @package App\Devices
 */
class UpdateLocationAutoLighting extends Job implements SelfHandling
{
    /**
     * @var Location
     */
    private $location;

    /**
     * Create a new job instance.
     *
     * @param Location $location
     */
    public function __construct(Location $location)
    {
        $this->location = $location;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        if ($this->location->lighting) {
            if ($this->location->occupied()) {
                $this->location->lighting->turnOn();
            } else {
                $this->location->lighting->turnOff();
            }
        }

    }
}
