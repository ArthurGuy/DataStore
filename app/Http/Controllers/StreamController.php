<?php

namespace App\Http\Controllers;

use App\Models\APIResponse;
use App\Models\Stream;
use Illuminate\Routing\Controller as BaseController;
use View;
use Input;
use Auth;
use Redirect;

class StreamController extends BaseController {


    protected $streamForm;

    protected $streamDataRepository;

    public function __construct(\App\Data\Forms\Stream $streamForm, \App\Data\Repositories\StreamDataRepository $streamDataRepository)
    {
        $this->streamForm = $streamForm;

        $this->streamDataRepository = $streamDataRepository;

        View::share('api_responses', APIResponse::dropdown());

        $this->beforeFilter('auth');
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $results = Stream::all();

        return View::make('stream.index')->withStreams($results);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        return View::make('stream.create');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
        $input = Input::only('name', 'fields', 'filter_field', 'filter_field_names', 'response_id');

        try
        {
            $this->streamForm->validate($input);
        }
        catch (\App\Data\Exceptions\FormValidationException $e)
        {
            return Redirect::back()->withInput()->withErrors($e->getErrors());
        }

        $stream = Stream::create($input);
        $this->streamDataRepository->createDomain($stream->id);


        return \Redirect::route('stream.edit', $stream->id);
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  string  $streamId
	 * @return Response
	 */
	public function show($streamId)
	{
        $stream = Stream::findOrFail($streamId);
        return View::make('stream.show')->withStream($stream);
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  string  $streamId
	 * @return Response
	 */
	public function edit($streamId)
	{
        $stream = Stream::findOrFail($streamId);
        return View::make('stream.edit')->withStream($stream);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  string  $streamId
	 * @return Response
	 */
	public function update($streamId)
	{
        $stream = Stream::findOrFail($streamId);

        $input = Input::only('name', 'fields', 'filter_field', 'filter_field_names', 'response_id');

        try
        {
            $this->streamForm->validate($input);
        }
        catch (\App\Data\Exceptions\FormValidationException $e)
        {
            return Redirect::back()->withInput()->withErrors($e->getErrors());
        }

        $stream->update($input);

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
        $stream = Stream::findOrFail($streamId);
        $stream->delete();
        $this->streamDataRepository->deleteDomain($stream->id);
        return \Redirect::route('stream.index')->withSuccess("Deleted");
	}


}
