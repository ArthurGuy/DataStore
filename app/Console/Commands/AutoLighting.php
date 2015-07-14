<?php

namespace App\Console\Commands;

use App\Models\Location;
use Illuminate\Console\Command;

class AutoLighting extends Command
{
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

            if ($location->lighting) {
                if ($location->occupied()) {
                    $this->info('At Home - Lighting On');
                    $location->lighting->update(['on'=>true]);
                } else {
                    $this->info('Away - Lighting Off');
                    $location->lighting->update(['on'=>false]);
                }
            }
        }
    }
}
