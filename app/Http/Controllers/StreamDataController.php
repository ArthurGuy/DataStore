<?php

namespace App\Http\Controllers;

use App\Models\APIResponse;
use App\Models\Stream;
use Illuminate\Routing\Controller as BaseController;
use View;
use Input;
use Auth;
use Redirect;
use Request;
use Illuminate\Http\Response;

class StreamDataController extends BaseController {

    /**
     * @var \App\Data\Triggers\NewDataTriggerHandler
     */
    private $dataTriggerHandler;

    public function __construct(\App\Data\Repositories\StreamDataRepository $streamDataRepository, \App\Data\Triggers\NewDataTriggerHandler $dataTriggerHandler)
    {
        $this->streamDataRepository = $streamDataRepository;
        $this->dataTriggerHandler = $dataTriggerHandler;

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

        return View::make('stream.data.index')
            ->withStream($stream)
            ->withData($data)
            ->with('pusherChannelName', 'stream.'.$streamId)
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
        return Response::make('', 200);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  string  $streamId
     * @return Response
	 */
	public function store(\Illuminate\Http\Request $request, $streamId)
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
            $content = null;
        }

        //$data = Request::all();

        if (empty($data))
        {
            \Log::debug("Bad json data received: ".json_encode($content));
            return response()->json('Bad Data', 400);
        }
        try {
            $this->streamDataRepository->create($streamId, $data);
            $data['date'] = date('Y-m-d H:i:s');
        }
        catch (\Exception $e)
        {
            $error = $e->getMessage();
            \Log::error($error);

            if ($request->wantsJson()) {
                return response()->json('Error:'.$error, 400);
            }

            return Redirect::route('stream.data.create', $streamId)->withErrors($error);
        }

        $stream = Stream::findOrFail($streamId);
        $stream->updateCurrentValues($data);

        //Update other things
        $this->dataTriggerHandler->handle($streamId, $data);

        //Does this endpoint have a specific response
        if ($stream->response_id)
        {
            $response = APIResponse::find($stream->response_id);
            $compiler = \App::make('Blade');
            echo $compiler::compileString($response->response);
        }

        if ($request->wantsJson()) {
            return response()->json('Saved', 201);
        }

        return Redirect::route('stream.data.index', $streamId)->withSuccess("Created");

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
        if (Auth::guest()) {
            return \Response::make('Not authorised', 400);
        }
        $this->streamDataRepository->delete($streamId, $id);
        return \Redirect::route('stream.data.index', $streamId)->withSuccess("Deleted");
	}


}
