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
        return View::make('dashboard.view');
	}

}
