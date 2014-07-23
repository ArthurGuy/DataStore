<?php

class GraphController extends \BaseController {


    protected $layout = 'layouts.main';

    protected $graphForm;

    public function __construct(\Data\Repositories\StreamDataRepository $streamDataRepository, \Data\Forms\Graph $graphForm)
    {
        //$this->streamRepository = $streamRepository;
        //$this->graphRepository = $graphRepository;
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
        //$streams = $this->streamRepository->getAll();
        $streams = Stream::all();

        //$graphs = $this->graphRepository->getAll();

        $graphs = Graph::all();

        $this->layout->content = View::make('graph.index')->withStreams($streams)->withGraphs($graphs);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        //$streams = $this->streamRepository->getAll();
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

        /*
        $data = Input::get();
        try {
            $streamId = $this->graphRepository->create($data);
        }
        catch (\Data\Exceptions\ValidationException $e)
        {
            return \Redirect::route('graph.create')->withErrors($this->graphRepository->getErrors())->withInput();
        }
        catch (\Data\Exceptions\DatabaseException $e)
        {
            return \Redirect::route('graph.create')->withErrors($e->getMessage())->withInput();
        }
        return \Redirect::route('graph.show', $streamId)->withSuccess("Created");
        */
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
        //$graph = $this->graphRepository->get($id);
        $graph = Graph::findOrFail($id);

        $location = null;
        if (isset($graph['filter']) && $graph['filter_field'] == 'location')
        {
            $location = $graph['filter'];
        }

        //$stream = $this->streamRepository->get($graph['streamId']);
        $stream = Stream::findOrFail($graph['streamId']);
        $data = $this->streamDataRepository->getAll($graph['streamId'], $location);

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
        //$graph = $this->graphRepository->get($id);
        $graph = Graph::findOrFail($id);

        //$streams = $this->streamRepository->getAll();
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

        /*

        $data = Input::get();
        try {
            $this->graphRepository->update($id, $data);
        }
        catch (\Data\Exceptions\ValidationException $e)
        {
            return \Redirect::route('graph.create')->withErrors($this->graphRepository->getErrors())->withInput();
        }
        catch (\Data\Exceptions\DatabaseException $e)
        {
            return \Redirect::route('graph.create')->withErrors($e->getMessage())->withInput();
        }
        return \Redirect::route('graph.show', $id)->withSuccess("Updated");
        */
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        //$this->graphRepository->delete($id);
        $graph = Graph::findOrFail($id);
        $graph->delete();
        return \Redirect::route('graph.index')->withSuccess("Deleted");
	}


}
