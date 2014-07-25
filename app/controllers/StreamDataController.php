<?php

use Illuminate\Http\Response;

class StreamDataController extends \BaseController {

    protected $layout = 'layouts.main';

    public function __construct(\Data\Repositories\StreamDataRepository $streamDataRepository)
    {
        $this->streamDataRepository = $streamDataRepository;

        (\App::environment() != 'production')
            ? $this->pusherChannelName = \App::environment().'-stream'
            : $this->pusherChannelName = 'stream';
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @param  string  $streamId
     * @return Response
	 */
	public function index($streamId)
	{
        $location = Input::get('location');
        $stream = Stream::findOrFail($streamId);
        $this->streamDataRepository->setNextToken(\Input::get('nextToken'));
        $data = $this->streamDataRepository->getAll($streamId, $location);
        $paginationNextToken = $this->streamDataRepository->getNextToken();
        $data = array_slice($data, 0, 1000);

        $this->layout->content = View::make('stream.data.index')
                                    ->withStream($stream)
                                    ->withData($data)
                                    ->with('pusherChannelName', $this->pusherChannelName)
                                    ->with('paginationNextToken', $paginationNextToken);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @param  string  $streamId
     * @return Response
	 */
	public function create($streamId)
	{
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
        if (\Request::isJson())
        {
            $content = \Request::getContent();
            $data = json_decode($content, true);
            if ($data == false)
            {
                $data = json_decode(urldecode($content), true);
            }
        }
        else
        {
            $data = Input::get();
        }

        if (empty($data))
        {
            return \Response::make('Bad Data', 400);
        }
        try {
            $this->streamDataRepository->create($streamId, $data);
            $data['date'] = date('Y-m-d H:i:s');
        }
        catch (\Exception $e)
        {
            $error = $e->getMessage();
            \Log::error($error);
            return $this->ifBrowser(function($streamId, $error) {
                return \Redirect::route('stream.data.create', $streamId)->withErrors($error);
            }, function($streamId, $error) {
                return \Response::make('Error:'.$error, 400);
            }, $streamId, $error);
        }

        //Update other things
        Event::fire('stream.data.store', [['streamId'=>$streamId, 'data'=>$data]]); //double array is important!


        return $this->ifBrowser(function($streamId) {
            return \Redirect::route('stream.data.index', $streamId)->withSuccess("Created");
        }, function() {
            return \Response::make('Saved', 200);
        }, $streamId);

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
