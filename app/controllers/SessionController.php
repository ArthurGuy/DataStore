<?php

class SessionController extends \BaseController {

    protected $layout = 'layouts.main';

    protected $loginForm;

    function __construct(\Data\Forms\Login $loginForm)
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
        $this->layout->content = View::make('session.create');
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
        catch (\Data\Exceptions\FormValidationException $e)
        {
            return Redirect::back()->withInput()->withErrors($e->getErrors());
        }

        if (Auth::attempt($input))
        {
            return Redirect::intended('/');
        }

        return Redirect::back()->withInput()->withFlashMessage('Invalid login details');
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
