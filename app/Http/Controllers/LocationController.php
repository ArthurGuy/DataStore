<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Routing\Controller as BaseController;
use View;
use Input;
use Auth;
use Redirect;

class LocationController extends BaseController {

    protected $layout = 'layouts.main';

    public function __construct(\App\Data\Forms\Location $locationForm)
    {
        $this->locationForm = $locationForm;

        $this->locationTypes = ['building'=>'Building', 'room'=>'Room'];

        $this->beforeFilter('auth');

        View::share('locationTypes', $this->locationTypes);
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $locations = Location::where('type', 'building')->get();

        return View::make('locations.index')->with('locations', $locations);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        return View::make('locations.create');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
        $input = Input::only('name', 'type', 'postcode', 'country');

        try
        {
            $this->locationForm->validate($input);
        }
        catch (\App\Data\Exceptions\FormValidationException $e)
        {
            return Redirect::back()->withInput()->withErrors($e->getErrors());
        }

        Location::create($input);

        return \Redirect::route('locations.index')->withSuccess("Created");
	}



	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
        $location = Location::findOrFail($id);
        return View::make('locations.edit')->with('location', $location);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
        $variable = Location::findOrFail($id);

        $input = Input::only('name', 'type', 'postcode', 'country');

        try
        {
            $this->locationForm->validate($input, $variable->id);
        }
        catch (\App\Data\Exceptions\FormValidationException $e)
        {
            return Redirect::back()->withInput()->withErrors($e->getErrors());
        }

        $variable->update($input);

        return \Redirect::route('locations.index')->withSuccess("Updated");
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        $record = Location::findOrFail($id);
        $record->delete();
        return \Redirect::route('locations.index')->withSuccess("Deleted");
	}


}
