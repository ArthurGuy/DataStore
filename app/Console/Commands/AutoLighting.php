<?php

namespace App\Console\Commands;

use App\Devices\UpdateLocationAutoLighting;
use App\Models\Location;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class AutoLighting extends Command
{
    use DispatchesJobs;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'location:lighting';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Turn lights on based on the at home state';

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
        /** @var Location[] $locations */
        $locations = Location::all();
        foreach ($locations as $location) {

            $this->info("Processing " . $location->name);
            $this->dispatch(new UpdateLocationAutoLighting($location));

        }
    }
}
