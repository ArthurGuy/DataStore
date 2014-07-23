<?php

class StreamController extends \BaseController {


    protected $layout = 'layouts.main';

    protected $streamForm;

    public function __construct(\Data\Forms\Stream $streamForm)
    {
        $this->streamForm = $streamForm;
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        //$results = $this->streamRepository->getAll();
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

        return \Redirect::route('stream.edit', $stream->id);


        /*
        $data = Input::get();
        if (!empty($data['fields']))
        {
            $fields = explode(',',$data['fields']);
            $data['fields'] = "[";
            foreach ($fields as $field)
            {
                $data['fields'] .= "{ \"key\": \"".trim($field)."\", \"name\": \"".trim($field)."\", \"type\": \"data\" },";
            }
            $data['fields'] = substr($data['fields'], 0, -1);//Remove the last comma
            $data['fields'] .= "]";
        }
        try {
            $streamId = $this->streamRepository->create($data);
        }
        catch (\Data\Exceptions\ValidationException $e)
        {
            return \Redirect::route('stream.create')->withErrors($this->streamRepository->getErrors());
        }
        catch (\Data\Exceptions\DatabaseException $e)
        {
            return \Redirect::route('stream.create')->withErrors($e->getMessage());
        }
        return \Redirect::route('stream.edit', $streamId)->withSuccess("Created");
        */
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  string  $streamId
	 * @return Response
	 */
	public function show($streamId)
	{
        /*
        try {
            $stream = $this->streamRepository->get($streamId);
        }
        catch (\Data\Exceptions\NotFoundException $e)
        {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
        }
        */
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
        //$stream = $this->streamRepository->get($streamId);
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

        /*
        try {
            $this->streamRepository->update($streamId, $data);
        }
        catch (\Data\Exceptions\ValidationException $e)
        {
            return \Redirect::route('stream.edit', $streamId)->withErrors($this->streamRepository->getErrors());
        }
        catch (\Data\Exceptions\DatabaseException $e)
        {
            return \Redirect::route('stream.edit', $streamId)->withErrors($e->getMessage());
        }
        return \Redirect::route('stream.show', $streamId)->withSuccess("Updated");
        */
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  string  $streamId
	 * @return Response
	 */
	public function destroy($streamId)
	{
        //$this->streamRepository->delete($streamId);
        $stream = Stream::findOrFail($streamId);
        $stream->delete();
        return \Redirect::route('stream.index')->withSuccess("Deleted");
	}


}
