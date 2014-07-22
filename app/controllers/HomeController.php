<?php

class HomeController extends BaseController {


    protected $layout = 'layouts.main';

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

	public function showWelcome()
	{
		return View::make('hello');
	}


    public function login()
    {
        $this->layout->content = View::make('login');
    }

    public function processLogin()
    {
        if (Auth::attempt(array('username' => Input::get('username'), 'password' => Input::get('password')), true))
        {
            return Redirect::intended('stream');
        }

        return Redirect::to('login')->withErrors([\Hash::make(Input::get('password')), Input::get('password'), \Hash::check(Input::get('password'), $_ENV['PASSWORD_HASH'])]);
    }
}
