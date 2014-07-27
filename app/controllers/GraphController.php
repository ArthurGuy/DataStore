<?php

class GraphController extends \BaseController {


    protected $layout = 'layouts.main';

    protected $graphForm;

    public function __construct(\Data\Repositories\StreamDataRepository $streamDataRepository, \Data\Forms\Graph $graphForm)
    {
        $this->streamDataRepository = $streamDataRepository;
        $this->graphForm = $graphForm;

        $this->timePeriods = ['hour'=>'1 Hour', 'day'=>'1 Day', 'week'=>'1 Week'];

        $this->beforeFilter('auth');
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $this->layout->content = View::make('graph.index')->withStreams(Stream::all())->withGraphs(Graph::all());
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        $streams = Stream::all();
        $streamDropdown = [];
        foreach ($streams as $stream)
        {
            $streamDropdown[$stream['id']] = $stream['name'];
        }
        $this->layout->content = View::make('graph.create')->with('streamDropdown', $streamDropdown)->withStreams($streams)->with('timePeriods', $this->timePeriods);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
        $input = Input::only('name', 'streamId', 'field', 'time_period', 'filter', 'filter_field');

        try
        {
            $this->graphForm->validate($input);
        }
        catch (\Data\Exceptions\FormValidationException $e)
        {
            return Redirect::back()->withInput()->withErrors($e->getErrors());
        }

        if (empty($input['filter_field']))
            unset($input['filter_field']);

        $graph = Graph::create($input);

        return \Redirect::route('graph.show', $graph->id)->withSuccess("Created");
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
        $graph = Graph::findOrFail($id);
        $stream = Stream::findOrFail($graph['streamId']);

        $endDate = \Carbon\Carbon::now();
        switch($graph['time_period']) {
            case 'hour':
                $startDate = \Carbon\Carbon::now()->subHour();
                break;
            case 'day':
                $startDate = \Carbon\Carbon::now()->subDay();
                break;
            case 'week':
                $startDate = \Carbon\Carbon::now()->subWeek();
                break;
            case 'month':
                $startDate = \Carbon\Carbon::now()->subMonth();
                break;
            default:
                $startDate = \Carbon\Carbon::now()->subDay();
        }


        $data = $this->streamDataRepository->getRange($graph['streamId'], $startDate, $endDate, [$graph['filter_field'] => $graph['filter']]);

        $this->layout->content = View::make('graph.show')->withGraph($graph)->withStream($stream)->withData($data);
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
        $graph = Graph::findOrFail($id);

        $streams = Stream::all();
        $streamDropdown = [];
        foreach ($streams as $stream)
        {
            $streamDropdown[$stream['id']] = $stream['name'];
        }
        $this->layout->content = View::make('graph.edit')
                                    ->withGraph($graph)
                                    ->with('streamDropdown', $streamDropdown)
                                    ->withStreams($streams)
                                    ->with('timePeriods', $this->timePeriods);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
        $graph = Graph::findOrFail($id);
        $input = Input::only('name', 'streamId', 'field', 'time_period', 'filter', 'filter_field');

        try
        {
            $this->graphForm->validate($input);
        }
        catch (\Data\Exceptions\FormValidationException $e)
        {
            return Redirect::back()->withInput()->withErrors($e->getErrors());
        }

        $graph->update($input);

        return \Redirect::route('graph.show', $graph->id)->withSuccess("Updated");
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        $graph = Graph::findOrFail($id);
        $graph->delete();
        return \Redirect::route('graph.index')->withSuccess("Deleted");
	}


}
