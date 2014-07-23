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

Route::get('/', array('as' => 'home', 'uses' => 'HomeController@index'));

Route::group(array('prefix' => 'account'), function()
{
    Route::get('login', 'AccountController@login');
    Route::post('process-login', 'HomeController@processLogin');
    Route::get('create', array('as' => 'account.create', 'uses' => 'AccountController@create'));
    Route::post('store', array('as' => 'account.store', 'uses' => 'AccountController@store'));
});

Route::resource('stream', 'StreamController');

Route::resource('stream.data', 'StreamDataController');

Route::resource('graph', 'GraphController');