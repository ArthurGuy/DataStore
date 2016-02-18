<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Location;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use View;
use Input;
use Auth;
use Redirect;


class SmartThingsController extends BaseController
{

    public function getReading(Request $request, $locationID, $type, $key)
    {
        if ($key != 'r89tpq7ncjgar') {
            return 0;
        }

        $location = Location::findOrFail($locationID);
        if ($type == 'temperature') {
            return $location->temperature;
        } elseif ($type == 'humidity') {
            return $location->humidity;
        } elseif ($type == 'motion') {
            if ($location->last_movement->gt(Carbon::now()->subMinutes(5))) {
                return 'active';
            } else {
                return 'inactive';
            }
        }
    }

}