<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Stream;
use App\Models\Trigger;
use App\Models\Variable;
use Illuminate\Routing\Controller as BaseController;
use View;
use Input;
use Auth;
use Redirect;

class TriggerController extends BaseController
{

    protected $triggerForm;

    public function __construct(
        \App\Data\Repositories\StreamDataRepository $streamDataRepository,
        \App\Data\Forms\Trigger $triggerForm
    ) {
        $this->streamDataRepository = $streamDataRepository;
        $this->triggerForm          = $triggerForm;

        $this->timePeriods = ['hour' => '1 Hour', 'day' => '1 Day', 'week' => '1 Week'];

        $this->operators = ['=' => '=', '>' => '>', '<' => '<', '!=' => '!=', '-' => '-'];

        $this->actions = [
            'push_message' => 'Push Message',
            'variable'     => 'Set a Variable',
            'nest'         => 'Nest Update',
            'location'     => 'Update a Location'
        ];

        $this->pushWhenOptions = [
            'once'    => 'Once',
            'daily'   => 'Daily',
            'weekly'  => 'Weekly',
            'hourly'  => 'Hourly',
            '5minute' => 'Every 5 minutes'
        ];

        $this->beforeFilter('auth');

        View::share('triggerActions', $this->actions);
        View::share('timePeriods', $this->timePeriods);
        View::share('operators', $this->operators);
        View::share('pushWhenOptions', $this->pushWhenOptions);
        View::share('locations', Location::dropdown());
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return View::make('trigger.index')
            ->withStreams(Stream::all())
            ->withTriggers(Trigger::all());
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $streams        = Stream::all();
        $streamDropdown = [];
        foreach ($streams as $stream) {
            $streamDropdown[$stream['id']] = $stream['name'];
        }
        return View::make('trigger.create')
            ->with('streamDropdown', $streamDropdown)
            ->withVariables(Variable::dropdown())
            ->withStreams($streams);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $input = Input::only('name', 'streamId', 'check_field', 'check_operator', 'check_value', 'filter_value',
            'filter_field', 'action', 'push_subject', 'push_message', 'push_when', 'variable_name', 'variable_value',
            'nest_api_key', 'nest_property', 'nest_value', 'nest_structure', 'location_id');

        try {
            $this->triggerForm->validate($input);
        } catch (\App\Data\Exceptions\FormValidationException $e) {
            return Redirect::back()->withInput()->withErrors($e->getErrors());
        }

        $trigger = Trigger::create($input);

        return \Redirect::route('trigger.index')->withSuccess("Created");
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        $trigger = Trigger::findOrFail($id);

        $stream = Stream::findOrFail($trigger['streamId']);

        return View::make('trigger.show')->withTrigger($trigger)->withStream($stream);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        $trigger = Trigger::findOrFail($id);

        $streams        = Stream::all();
        $streamDropdown = [];
        foreach ($streams as $stream) {
            $streamDropdown[$stream['id']] = $stream['name'];
        }
        return View::make('trigger.edit')
            ->withTrigger($trigger)
            ->with('streamDropdown', $streamDropdown)
            ->withVariables(Variable::dropdown())
            ->withStreams($streams);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        $trigger = Trigger::findOrFail($id);

        $input = Input::only('name', 'streamId', 'check_field', 'check_operator', 'check_value', 'filter_value',
            'filter_field', 'action', 'push_subject', 'push_message', 'push_when', 'variable_name', 'variable_value',
            'nest_api_key', 'nest_property', 'nest_value', 'nest_structure', 'location_id');

        try {
            $this->triggerForm->validate($input);
        } catch (\App\Data\Exceptions\FormValidationException $e) {
            return Redirect::back()->withInput()->withErrors($e->getErrors());
        }

        $trigger->update($input);

        return \Redirect::route('trigger.show', $trigger->id)->withSuccess("Updated");
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        $record = Trigger::findOrFail($id);
        $record->delete();

        return \Redirect::route('trigger.index')->withSuccess("Deleted");
    }


}
