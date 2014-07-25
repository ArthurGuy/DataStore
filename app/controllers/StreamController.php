<?php

class StreamController extends \BaseController {


    protected $layout = 'layouts.main';

    protected $streamForm;

    protected $streamDataRepository;

    public function __construct(\Data\Forms\Stream $streamForm, \Data\Repositories\StreamDataRepository $streamDataRepository)
    {
        $this->streamForm = $streamForm;

        $this->streamDataRepository = $streamDataRepository;

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
        $input = Input::only('name', 'fields');

        if (!empty($input['fields']))
        {
            $fields = explode(',',$input['fields']);
            $input['fields'] = "[";
            foreach ($fields as $field)
            {
                $input['fields'] .= "{ \"key\": \"".trim($field)."\", \"name\": \"".trim($field)."\", \"type\": \"data\" },";
            }
            $input['fields'] = substr($input['fields'], 0, -1);//Remove the last comma
            $input['fields'] .= "]";
        }

        try
        {
            $this->streamForm->validate($input);
        }
        catch (\Data\Exceptions\FormValidationException $e)
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
        $stream = Stream::findOrFail($streamId);
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
        $stream = Stream::findOrFail($streamId);

        $input = Input::only('name', 'fields', 'tags');

        try
        {
            $this->streamForm->validate($input);
        }
        catch (\Data\Exceptions\FormValidationException $e)
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
