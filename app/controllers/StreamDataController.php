<?php

use Illuminate\Http\Response;

class StreamDataController extends \BaseController {

    protected $layout = 'layouts.main';

    public function __construct(\Data\Repositories\StreamRepository $streamRepository, \Data\Repositories\StreamDataRepository $streamDataRepository)
    {
        $this->streamRepository = $streamRepository;
        $this->streamDataRepository = $streamDataRepository;
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @param  string  $streamId
     * @return Response
	 */
	public function index($streamId)
	{
        $stream = $this->streamRepository->get($streamId);
        $data = $this->streamDataRepository->getAll($streamId);

        $this->layout->content = View::make('stream.data.index')->withStream($stream)->withData($data);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @param  string  $streamId
     * @return Response
	 */
	public function create($streamId)
	{
        $data = [
            'temperature' => 12.4,
            'humidity' => 68,
            'voltage' => 2946
        ];
        $this->streamDataRepository->create($streamId, $data);

        $this->layout->content = "";
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  string  $streamId
     * @return Response
	 */
	public function store($streamId)
	{
        $data = Input::get();
        try {
            $this->streamDataRepository->create($streamId, $data);
        }
        catch (\Exception $e)
        {
            $error = $e->getMessage();
            if (\Illuminate\Http\Request::wantsJson())
            {
                return Response::create($error, 400);
            }
            return \Redirect::route('stream.data.create', $streamId)->withErrors($error);
        }

        if (Request::wantsJson())
        {
            return Response::create('Saved', 201);
        }

        return \Redirect::route('stream.data.index', $streamId)->withSuccess("Created");
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  string  $streamId
     * @param  int  $id
	 * @return Response
	 */
	public function show($streamId, $id)
	{
		//
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  string  $streamId
     * @param  int  $id
	 * @return Response
	 */
	public function edit($streamId, $id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  string  $streamId
     * @param  int  $id
	 * @return Response
	 */
	public function update($streamId, $id)
	{
		//
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  string  $streamId
     * @param  int  $id
	 * @return Response
	 */
	public function destroy($streamId, $id)
	{
        $this->streamDataRepository->delete($streamId, $id);
        return \Redirect::route('stream.data.index', $streamId)->withSuccess("Deleted");
	}


}
