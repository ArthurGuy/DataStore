<?php

namespace App\Http\Controllers;

use App\Data\Weather\Helper;
use App\Models\Location;
use Forecast\Forecast;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class ForecastController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @param $locationId
     * @return Response
     */
    public function getLocation($locationId)
    {
        $location = Location::findOrFail($locationId);

        $this->confirmLocationDataExists($location);

        return $this->get($location->latitude, $location->longitude);
    }

    /**
     * @param $latitude
     * @param $longitude
     * @return Response
     */
    public function get($latitude, $longitude)
    {
        $forecast = new Forecast(getenv('FORECAST_API_KEY'));

        //Fetch the current forecast from the forecast.io api
        $locationForecast = $forecast->get($latitude, $longitude);

        //Find the general outside temperature
        $outsideWeather                = [];
        $outsideWeather['temperature'] = Helper::convertFtoC($locationForecast->currently->temperature);
        $outsideWeather['humidity']    = $locationForecast->currently->humidity * 100;
        $outsideWeather['duePoint']    = Helper::calculateDuePoint($outsideWeather['temperature'],
            $outsideWeather['humidity']);
        $outsideWeather['condition']   = Helper::weatherCondition($outsideWeather['duePoint'],
            $outsideWeather['temperature']);

        $futureForecast = $this->nearFutureForecast($locationForecast);

        $outsideWeather['futureForecast'] = $futureForecast;

        //Weather overview for today
        $todaySummary                    = $locationForecast->daily->data[0];
        $dayWeather                      = [];
        $dayWeather['dayMaxTemperature'] = round(Helper::convertFtoC($todaySummary->temperatureMax));
        $dayWeather['dayMinTemperature'] = round(Helper::convertFtoC($todaySummary->temperatureMin));
        //$daySummary = $locationForecast->hourly->summary;
        $dayWeather['daySummary'] = $todaySummary->summary;

        $outsideWeather['dayWeather'] = $dayWeather;

        return $outsideWeather;
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
     * @param Location $location
     * @return mixed
     */
    private function confirmLocationDataExists($location)
    {
        if (empty($location->latitude) || empty($location->longitude)) {
            return Response::make('No Location set', 400);
        }
    }

}
