<?php

class GraphController extends \BaseController {


    protected $layout = 'layouts.main';

    public function __construct(\Data\Repositories\StreamRepository $streamRepository, \Data\Repositories\GraphRepository $graphRepository)
    {
        $this->streamRepository = $streamRepository;
        $this->graphRepository = $graphRepository;

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
        $this->layout->content = View::make('graph.create')->with('streamDropdown', $streamDropdown);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}


}
