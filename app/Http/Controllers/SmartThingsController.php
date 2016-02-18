<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use View;
use Input;
use Auth;
use Redirect;
use App\Data\Weather\Helper;
use Forecast\Forecast;

class SmartThingsController extends BaseController
{

    private $oauthClientID = '86c1f8bf-5bc6-48be-b107-948fb1bd0f35';
    private $oauthClientSecret = '42b7dcf5-8c81-41da-92a4-201987c3e80d';
    /**
     * @var \GuzzleHttp\Client
     */
    private $guzzle;


    public function __construct(\GuzzleHttp\Client $guzzle)
    {
        //$this->middleware('auth');
        $this->guzzle = $guzzle;
    }

    public function getReading(Request $request, $locationID, $type)
    {
        $location = Location::findOrFail($locationID);
        if ($type == 'temperature') {
            return $location->temperature;
        } elseif ($type == 'humidity') {
            return $location->humidity;
        }
    }

    /*
    public function locationList()
    {
        $locations = Location::all();
        return view('smartthings.location-list')->with('locations', $locations);
    }

    public function connect($locationID, $type)
    {
        return redirect('https://graph.api.smartthings.com/oauth/authorize?response_type=code&client_id='.$this->oauthClientID.'&redirect_uri='.route('smartthings.connect-return', [$locationID, $type]).'&scope=app');
    }

    public function connectReturn(Request $request, $locationID, $type)
    {
        $code = $request->get('code');
        $page = "https://graph.api.smartthings.com/oauth/token?grant_type=authorization_code&client_id=".$this->oauthClientID."&client_secret=".$this->oauthClientSecret."&redirect_uri=".route('smartthings.connect-return', [$locationID, $type])."&code=".$code."&scope=app";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $page);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        $response = json_decode(curl_exec($ch), true);
        curl_close($ch);
        if (isset($response['access_token'])) {
            $accessToken = $response['access_token'];

            $url = 'https://graph.api.smartthings.com/api/smartapps/endpoints/' . $this->oauthClientID . '?access_token=' . $accessToken;
            //return $url;
            $res = $this->guzzle->get($url);
            $response = json_decode($res->getBody(), true);
            return 'https://graph.api.smartthings.com' . $response[0]['url'] . '?access_token=' . $accessToken;

        } else {
            return "error requesting access token...";
        }

        //save the access token for the location and sensor type
    }
    */
}