<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/


# Home

Route::get('/', array('as' => 'home', 'uses' => 'HomeController@index'));


# Dashboard
Route::get('dashboard/manifest.json', function() {
    return response()->json([
        'lang' => 'en',
        'name' => 'Home Dashboard',
        'short_name' => 'Home Dashboard',
        'display' => 'fullscreen',
        'orientation' => 'portrait',
        'theme_color' => '#5898D8',
        'scope' => '/dashboard',
        'icons' => [
            "src" => "https://s3-eu-west-1.amazonaws.com/static.arthurguy.co.uk/images/ArthurGuy.ico",
            "sizes" => "256x256",
            "type" => "image/x-icon"
        ],
        'service_worker' => [
            'src' => 'service-worker.js',
            'scope' => '/dashboard'
        ]
    ]);
});
Route::get('dashboard', array('as' => 'dashboard', 'uses' => 'DashboardController@index'));
Route::get('dashboard/{locationId}', array('as' => 'dashboard.view', 'uses' => 'DashboardController@view'));
Route::get('api/meta', function() {
    return response()->json([
        'lang' => 'en',
        'name' => 'Home Dashboard',
        'short_name' => 'Home Dashboard',
        'version' => json_decode(file_get_contents(base_path('resources/assets/versions.json')), true)['dashboard'],
    ]);
});

Route::group(['prefix' => 'api'], function () {
    Route::get('locations/{id}', 'LocationController@show');
    Route::get('forecast/{locationId}', 'ForecastController@get');

    Route::put('device/{deviceId}', 'DeviceController@update');
    Route::put('locations/{id}', 'LocationController@update');
});


# Authentication

Route::get('login', ['as' => 'login', 'uses' => 'SessionController@create']);
Route::get('logout', ['as' => 'logout', 'uses' => 'SessionController@destroy']);
Route::resource('session', 'SessionController', ['only' => ['create', 'store', 'destroy']]);
Route::get('register', ['as' => 'logout', 'uses' => 'AccountController@create'])->before('guest');
Route::resource('account', 'AccountController');


Route::resource('stream', 'StreamController');
Route::resource('stream.data', 'StreamDataController');
Route::post('save/{stream}', 'StreamDataController@store');

Route::resource('graph', 'GraphController');
Route::resource('trigger', 'TriggerController');
Route::resource('variable', 'VariableController');
Route::resource('locations', 'LocationController');


Route::resource('device', 'DeviceController');
Route::resource('apiresponse', 'APIResponseController');

Route::resource('info', 'InfoController');


Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');


