<?php

class StreamController extends \BaseController {


    protected $layout = 'layouts.main';

    public function __construct(\Data\Repositories\StreamRepository $streamRepository)
    {
        $this->streamRepository = $streamRepository;

    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $results = $this->streamRepository->getAll();

        $this->layout->content = View::make('stream.index')->withStreams($results);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        $this->layout->content = View::make('stream.create');
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
            $this->streamRepository->create($data);
        }
        catch (\Exception $e)
        {
            return \Redirect::route('stream.create')->withErrors($e->getMessage());
        }
        return \Redirect::route('stream.index')->withSuccess("Created");
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  string  $streamId
	 * @return Response
	 */
	public function show($streamId)
	{
        $stream = $this->streamRepository->get($streamId);
        $this->layout->content = View::make('stream.show')->withStream($stream);
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  string  $streamId
	 * @return Response
	 */
	public function edit($streamId)
	{
        $stream = $this->streamRepository->get($streamId);
        $this->layout->content = View::make('stream.edit')->withStream($stream);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  string  $streamId
	 * @return Response
	 */
	public function update($streamId)
	{
        $data = Input::get();
        try {
            $this->streamRepository->update($streamId, $data);
        }
        catch (\Exception $e)
        {
            return \Redirect::route('stream.edit', $streamId)->withError($e->getMessage());
        }
        return \Redirect::route('stream.show', $streamId)->withSuccess("Updated");
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  string  $streamId
	 * @return Response
	 */
	public function destroy($streamId)
	{
        $this->streamRepository->delete($streamId);
        return \Redirect::route('stream.index')->withSuccess("Deleted");
	}


}
