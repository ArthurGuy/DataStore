<?php

namespace App\Http\Controllers;

use Aws\SimpleDb\SimpleDbClient;
use Illuminate\Routing\Controller as BaseController;
use View;
use Input;
use Auth;

class InfoController extends BaseController {

    public function __construct()
    {
        $this->middleware('auth');

        $this->simpleDbClient = $client = SimpleDbClient::factory(array(
            'key' => env('AWS_KEY'),
            'secret' => env('AWS_SECRET'),
            'region'  => 'eu-west-1'
        ));
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $simpleDbDomains = $this->simpleDbClient->getIterator('ListDomains')->toArray();
        foreach ($simpleDbDomains as $i =>$domain)
        {
            unset($simpleDbDomains[$i]);
            $simpleDbDomains[$domain] = $this->simpleDbClient->domainMetadata(array('DomainName' => $domain));
        }
        return View::make('info.index')->with('simpleDbDomains', $simpleDbDomains);
	}

}
