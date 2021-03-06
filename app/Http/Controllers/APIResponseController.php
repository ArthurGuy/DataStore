<?php

namespace App\Http\Controllers;

use App\Models\APIResponse;
use Illuminate\Routing\Controller as BaseController;
use View;
use Input;
use Auth;

class APIResponseController extends BaseController {

    protected $responseForm;

    public function __construct(\App\Data\Forms\APIResponse $responseForm)
    {
        $this->responseForm = $responseForm;

        $this->middleware('auth');
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        return View::make('api_response.index')->withResponses(APIResponse::all());
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        return View::make('api_response.create');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
        $input = Input::only('name', 'response');

        try
        {
            $this->responseForm->validate($input);
        }
        catch (\App\Data\Exceptions\FormValidationException $e)
        {
            return Redirect::back()->withInput()->withErrors($e->getErrors());
        }

        $response = APIResponse::create($input);

        return \Redirect::route('apiresponse.index')->withSuccess("Created");
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
        return View::make('api_response.show')->withResponse(APIResponse::findOrFail($id));
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
        return View::make('api_response.edit')->withResponse(APIResponse::findOrFail($id));
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
        $response = APIResponse::findOrFail($id);
        $input = Input::only('name', 'response');

        try
        {
            $this->responseForm->validate($input);
        }
        catch (\App\Data\Exceptions\FormValidationException $e)
        {
            return Redirect::back()->withInput()->withErrors($e->getErrors());
        }

        $response->update($input);

        return \Redirect::route('apiresponse.index')->withSuccess("Updated");
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        $graph = APIResponse::findOrFail($id);
        $graph->delete();
        return \Redirect::route('apiresponse.index')->withSuccess("Deleted");
	}


}
