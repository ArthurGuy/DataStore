<?php

use Forecast\Forecast;

class DashboardController extends BaseController {


    protected $layout = 'layouts.main';

    public function __construct()
    {

    }

	public function index()
    {
        $locations = \Location::where('type', 'building')->get();
        return View::make('dashboard.index')->with('locations', $locations);
    }

	public function view($locationId)
	{
        $forecast = new Forecast(getenv('FORECAST_API_KEY'));

        $location = \Location::findOrFail($locationId);
        $rooms = $location->rooms();

        if (empty($location->latitude) || empty($location->longitude)) {
            return Redirect::route('dashboard');
        }
        $locationForcast = $forecast->get($location->latitude, $location->longitude);

        $outTemperature = round(($locationForcast->currently->temperature - 32) / 1.8, 1);

        if (\Carbon\Carbon::createFromTimestamp($locationForcast->hourly->data[0]->time)->lt(\Carbon\Carbon::now()->subMinutes(30))) {
            $futureForecast = $locationForcast->hourly->data[1];
        } else {
            $futureForecast = $locationForcast->hourly->data[0];
        }

        $daySummary = $locationForcast->hourly->summary;

        //return json_encode($futureForecast);
        //return json_encode($locationForcast);

        return View::make('dashboard.view')
            ->with('forecast', $locationForcast->currently)
            ->with('outTemperature', $outTemperature)
            ->with('rooms', $rooms)
            ->with('location', $location)
            ->with('futureForecast', $futureForecast)
            ->with('daySummary', $daySummary);
	}



}
