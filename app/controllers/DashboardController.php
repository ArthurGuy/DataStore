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

        $this->confirmLocationDataExists($location);


        //Fetch the current forecast from the forecast.io api
        $locationForecast = $forecast->get($location->latitude, $location->longitude);

        //Find the general outside temperature
        $outsideWeather = [];
        $outsideWeather['temperature'] = $this->convertFtoC($locationForecast->currently->temperature);
        $outsideWeather['humidity'] = $locationForecast->currently->humidity * 100;
        $outsideWeather['duePoint'] = $this->calculateDuePoint($outsideWeather['temperature'], $outsideWeather['humidity']);
        $outsideWeather['condition'] = $this->weatherCondition($outsideWeather['duePoint']);

        //The main forecast to display - whats happening soon
        $futureForecast = $this->nearFutureForecast($locationForecast);


        //Weather overview for today
        $todaySummary = $locationForecast->daily->data[0];
        $dayWeather = [];
        $dayWeather['dayMaxTemperature'] = round($this->convertFtoC($todaySummary->temperatureMax));
        $dayWeather['dayMinTemperature'] = round($this->convertFtoC($todaySummary->temperatureMin));
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
     * @param float $temp
     * @return float
     */
    private function convertFtoC($temp)
    {
        return round(($temp - 32) / 1.8, 1);
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
     * @param float   $temperature
     * @param integer $humidity
     * @return float
     */
    private function calculateDuePoint($temperature, $humidity)
    {
        return $temperature - ((100 - $humidity) / 5);
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

    /**
     * A text representation of the due point conditions
     * 
     * @param $duePoint
     * @return string
     */
    private function weatherCondition($duePoint)
    {
        if ($duePoint > 26) {
            return 'Severe';
        } elseif ($duePoint > 24) {
            return 'Extremely Uncomfortable';
        } elseif ($duePoint > 21) {
            return 'Very Humid, Uncomfortable';
        } elseif ($duePoint > 18) {
            return 'Somewhat Uncomfortable';
        } elseif ($duePoint > 16) {
            return 'OK';
        } elseif ($duePoint > 13) {
            return 'Comfortable';
        } elseif ($duePoint > 10) {
            return 'Very Comfortable';
        } else {
            return 'Dry';
        }
    }

}
