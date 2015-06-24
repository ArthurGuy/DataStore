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
Route::get('dashboard', array('as' => 'dashboard', 'uses' => 'DashboardController@index'));
Route::get('dashboard/{locationId}', array('as' => 'dashboard.view', 'uses' => 'DashboardController@view'));


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
Route::resource('apiresponse', 'APIResponseController');

Route::resource('info', 'InfoController');


Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');