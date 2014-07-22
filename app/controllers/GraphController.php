<?php

class GraphController extends \BaseController {


    protected $layout = 'layouts.main';

    public function __construct(\Data\Repositories\StreamRepository $streamRepository, \Data\Repositories\GraphRepository $graphRepository, \Data\Repositories\StreamDataRepository $streamDataRepository)
    {
        $this->streamRepository = $streamRepository;
        $this->graphRepository = $graphRepository;
        $this->streamDataRepository = $streamDataRepository;

        $this->timePeriods = ['hour'=>'1 Hour', 'day'=>'1 Day', 'week'=>'1 Week'];
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $streams = $this->streamRepository->getAll();

        $graphs = $this->graphRepository->getAll();

        $this->layout->content = View::make('graph.index')->withStreams($streams)->withGraphs($graphs);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        $streams = $this->streamRepository->getAll();
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
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
        $graph = $this->graphRepository->get($id);

        $location = null;
        if ($graph['filter'] && $graph['filter_field'] == 'location')
        {
            $location = $graph['filter'];
        }

        $stream = $this->streamRepository->get($graph['streamId']);
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
        $graph = $this->graphRepository->get($id);

        $streams = $this->streamRepository->getAll();
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
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        $this->graphRepository->delete($id);
        return \Redirect::route('graph.index')->withSuccess("Deleted");
	}


}
