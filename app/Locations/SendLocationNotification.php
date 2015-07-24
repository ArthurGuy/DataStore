<?php

namespace App\Locations;

use App\Jobs\Job;
use App\Models\ClientNotification;
use App\Models\Location;
use App\Models\Notification;
use Illuminate\Contracts\Bus\SelfHandling;

class SendLocationNotification extends Job implements SelfHandling
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
        $notifications = new Notification();
        $notification  = $notifications->createNotification(
            $this->location->user_id,
            $this->location->parent_id,
            'Home Automation',
            'No data received from ' . $this->location->name,
            'https://s3-eu-west-1.amazonaws.com/static.arthurguy.co.uk/data-icons/room-sensor.jpg',
            'location-no-data'
        );

        $notification->broadcast();
    }
}
