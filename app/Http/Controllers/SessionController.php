<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use View;
use Input;
use Auth;
use Redirect;

class SessionController extends BaseController {

    protected $loginForm;

    function __construct(\App\Data\Forms\Login $loginForm)
    {
        $this->loginForm = $loginForm;
    }


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        return View::make('session.create');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
        $input = Input::only('username', 'password');

        try
        {
            $this->loginForm->validate($input);
        }
        catch (\App\Data\Exceptions\FormValidationException $e)
        {
            return Redirect::back()->withInput()->withErrors($e->getErrors());
        }

        if (Auth::attempt($input, true))
        {
            return Redirect::intended('/');
        }

        return Redirect::back()->withInput()->withErrors('Invalid login details');
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id=null)
	{
        Auth::logout();

        return Redirect::home();
	}


}
