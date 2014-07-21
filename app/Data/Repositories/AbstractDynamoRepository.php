<?php namespace Data\Repositories;

use Illuminate\Support\Facades\App;
use Aws\DynamoDb\Iterator\ItemIterator;

abstract class AbstractDynamoRepository {

    protected $table;

    public function __construct()
    {
        $this->client = App::make('aws')->get('dynamodb');
    }



} 