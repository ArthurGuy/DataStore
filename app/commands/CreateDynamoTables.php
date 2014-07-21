<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class CreateDynamoTables extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'database:create-dynamo';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create the Dynamo DB Tables';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
        $client = App::make('aws')->get('dynamodb');


        $client->createTable(array(
            'TableName' => 'streams',
            'AttributeDefinitions' => array(
                array(
                    'AttributeName' => 'id',
                    'AttributeType' => 'S'
                )
            ),
            'KeySchema' => array(
                array(
                    'AttributeName' => 'id',
                    'KeyType'       => 'HASH'
                )
            ),
            'ProvisionedThroughput' => array(
                'ReadCapacityUnits'  => 1,
                'WriteCapacityUnits' => 1
            )
        ));

        $client->createTable(array(
            'TableName' => 'stream-data',
            'AttributeDefinitions' => array(
                array(
                    'AttributeName' => 'id',
                    'AttributeType' => 'S'
                ),
                array(
                    'AttributeName' => 'time',
                    'AttributeType' => 'N'
                )
            ),
            'KeySchema' => array(
                array(
                    'AttributeName' => 'id',
                    'KeyType'       => 'HASH'
                ),
                array(
                    'AttributeName' => 'time',
                    'KeyType'       => 'RANGE'
                )
            ),
            'ProvisionedThroughput' => array(
                'ReadCapacityUnits'  => 1,
                'WriteCapacityUnits' => 2
            )
        ));


	}


}
