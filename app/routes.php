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


# Authentication

Route::get('login', ['as' => 'login', 'uses' => 'SessionController@create']);
Route::get('logout', ['as' => 'logout', 'uses' => 'SessionController@destroy']);
Route::resource('session', 'SessionController', ['only' => ['create', 'store', 'destroy']]);
Route::get('register', ['as' => 'logout', 'uses' => 'AccountController@create'])->before('guest');
Route::resource('account', 'AccountController');


Route::resource('stream', 'StreamController');

Route::resource('stream.data', 'StreamDataController');

Route::resource('graph', 'GraphController');

Route::resource('info', 'InfoController');

Route::get('load-test', function() {
    print_r(Stream::all());
    print_r(Graph::all());
    print_r(User::all());
});