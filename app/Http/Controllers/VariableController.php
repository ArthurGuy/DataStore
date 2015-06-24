<?php

namespace App\Http\Controllers;

use App\Models\Variable;
use Illuminate\Routing\Controller as BaseController;
use View;
use Input;
use Auth;
use Redirect;

class VariableController extends BaseController {

    public function __construct(\App\Data\Forms\Variable $variableForm)
    {
        $this->variableForm = $variableForm;

        $this->types = ['string'=>'String', 'boolean'=>'Boolean', 'integer'=>'Integer', 'float'=>'Float'];

        $this->beforeFilter('auth');

        View::share('variableTypes', $this->types);
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        return View::make('variable.index')
            ->withVariables(Variable::all());
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        return View::make('variable.create');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
        $input = Input::only('name', 'value', 'type');

        try
        {
            $this->variableForm->validate($input);
        }
        catch (\App\Data\Exceptions\FormValidationException $e)
        {
            return Redirect::back()->withInput()->withErrors($e->getErrors());
        }

        Variable::create($input);

        return \Redirect::route('variable.index')->withSuccess("Created");
	}



	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
        $variable = Variable::findOrFail($id);
        return View::make('variable.edit')
            ->withVariable($variable);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
        $variable = Variable::findOrFail($id);

        $input = Input::only('name', 'value', 'type');

        try
        {
            $this->variableForm->validate($input, $variable->id);
        }
        catch (\App\Data\Exceptions\FormValidationException $e)
        {
            return Redirect::back()->withInput()->withErrors($e->getErrors());
        }

        $variable->update($input);

        return \Redirect::route('variable.index')->withSuccess("Updated");
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        $record = Variable::findOrFail($id);
        $record->delete();
        return \Redirect::route('variable.index')->withSuccess("Deleted");
	}


}
