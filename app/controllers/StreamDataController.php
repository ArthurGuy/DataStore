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

        $location = Input::get('location');
        $stream = $this->streamRepository->get($streamId);
        //print_r($stream);
        //exit;
        $data = $this->streamDataRepository->getAll($streamId, $location);

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
        /*
        $data = [
            'temperature' => 12.4,
            'humidity' => 68,
            'voltage' => 2946
        ];
        $this->streamDataRepository->create($streamId, $data);
        */
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
        try {
            $this->streamDataRepository->create($streamId, $data);
        }
        catch (\Exception $e)
        {
            $error = $e->getMessage();
            \Log::error($error);
            \Log::debug($content);
            return $this->ifBrowser(function($streamId, $error) {
                return \Redirect::route('stream.data.create', $streamId)->withErrors($error);
            }, function($streamId, $error) {
                return \Response::make('Error:'.$error, 400);
            }, $streamId, $error);
        }

        //Update other things
        Pusherer::trigger('stream', 'new', array( 'data' => json_encode($data) ));

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
