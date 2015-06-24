<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use View;
use Input;
use Auth;

class HomeController extends BaseController {


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
        return View::make('home');
	}



}
