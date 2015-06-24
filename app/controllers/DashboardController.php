<?php

use Data\Weather\Helper;
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

        $this->confirmLocationDataExists($location);


        //Fetch the current forecast from the forecast.io api
        $locationForecast = $forecast->get($location->latitude, $location->longitude);

        //Find the general outside temperature
        $outsideWeather = [];
        $outsideWeather['temperature'] = Helper::convertFtoC($locationForecast->currently->temperature);
        $outsideWeather['humidity'] = $locationForecast->currently->humidity * 100;
        $outsideWeather['duePoint'] = Helper::calculateDuePoint($outsideWeather['temperature'], $outsideWeather['humidity']);
        $outsideWeather['condition'] = Helper::weatherCondition($outsideWeather['duePoint']);

        //The main forecast to display - whats happening soon
        $futureForecast = $this->nearFutureForecast($locationForecast);


        //Weather overview for today
        $todaySummary = $locationForecast->daily->data[0];
        $dayWeather = [];
        $dayWeather['dayMaxTemperature'] = round(Helper::convertFtoC($todaySummary->temperatureMax));
        $dayWeather['dayMinTemperature'] = round(Helper::convertFtoC($todaySummary->temperatureMin));
        //$daySummary = $locationForecast->hourly->summary;
        $dayWeather['daySummary'] = $todaySummary->summary;



        //return json_encode($futureForecast);
        //return json_encode($locationForecast);

        return View::make('dashboard.view')
            ->with('forecast', $locationForecast->currently)
            ->with('outsideWeather', $outsideWeather)
            ->with('rooms', $rooms)
            ->with('location', $location)
            ->with('futureForecast', $futureForecast)
            ->with('dayWeather', $dayWeather);
	}



    /**
     * Return a forecast for the near future - basically the next hour
     *
     * @param $locationForecast
     * @return mixed
     */
    private function nearFutureForecast($locationForecast)
    {
        if (\Carbon\Carbon::createFromTimestamp($locationForecast->hourly->data[0]->time)->lt(\Carbon\Carbon::now()->subMinutes(30))) {
            return $locationForecast->hourly->data[1];
        } else {
            return $locationForecast->hourly->data[0];
        }
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
