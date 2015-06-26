<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Routing\Controller as BaseController;
use View;
use Input;
use Auth;
use Redirect;
use App\Data\Weather\Helper;
use Forecast\Forecast;

class DashboardController extends BaseController {


    public function __construct()
    {
        $this->middleware('auth');
    }

	public function index()
    {
        $locations = Location::where('type', 'building')->get();
        return View::make('dashboard.index')->with('locations', $locations);
    }

	public function view($locationId)
	{
        $location = Location::findOrFail($locationId);
        $rooms = $location->rooms();

        $this->confirmLocationDataExists($location);

        return View::make('dashboard.view')
            ->with('rooms', $rooms)
            ->with('location', $location);
	}



    /**
     * Make sure we have the lat and lon for a location, if not return to the index
     *
     * @param \Location $location
     * @return mixed
     */
    private function confirmLocationDataExists($location)
    {
        if (empty($location->latitude) || empty($location->longitude)) {
            return Redirect::route('dashboard');
        }
    }



}
