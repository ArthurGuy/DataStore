<?php

class InfoController extends \BaseController {

    protected $layout = 'layouts.main';

    public function __construct()
    {

        $this->simpleDbClient = \App::make('aws')->get('SimpleDb');
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        //$this->simpleDbClient->deleteDomain(array('DomainName' => 'ag-auth'));


        $simpleDbDomains = $this->simpleDbClient->getIterator('ListDomains')->toArray();
        foreach ($simpleDbDomains as $i =>$domain)
        {
            unset($simpleDbDomains[$i]);
            $simpleDbDomains[$domain] = $this->simpleDbClient->domainMetadata(array('DomainName' => $domain));
        }
        return View::make('info.index')->with('simpleDbDomains', $simpleDbDomains);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
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
