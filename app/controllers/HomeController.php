<?php

use Forecast\Forecast;

class HomeController extends BaseController {


    protected $layout = 'layouts.main';

    public function __construct()
    {

    }

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function index()
	{
        $forecast = new Forecast(getenv('FORECAST_API_KEY'));

        $location = \Location::where('name', 'Home')->first();
        $rooms = $location->rooms();

        $locationForcast = $forecast->get($location->latitude, $location->longitude);

        $outTemperature = round(($locationForcast->currently->temperature - 32) / 1.8, 1);

        //var_dump($locationForcast);

        return View::make('home')->with('forecast', $locationForcast->currently)->with('outTemperature', $outTemperature)->with('rooms', $rooms);
	}



}
