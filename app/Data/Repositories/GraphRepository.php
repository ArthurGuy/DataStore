<?php namespace Data\Repositories;

use Aws\DynamoDb\Exception\ValidationException;
use Aws\DynamoDb\Iterator\ItemIterator;

class GraphRepository extends AbstractDynamoRepository {



    public function __construct()
    {
        parent::__construct();
        if (\App::environment() == 'production')
        {
            $this->table = 'graphs';
        }
        else
        {
            $this->table = \App::environment().'-graphs';
        }

        $this->createRules = array(
            'name' => array('required', 'min:1'),
            'streamId' => array('required', 'min:10'),
            'field' => array('required', 'min:1'),
            'time_period' => array('required', 'min:1'),
        );
        $this->updateRules = array(
            'name' => array('required', 'min:1'),
            'field' => array('required', 'min:1'),
            'time_period' => array('required', 'min:1'),
        );

        $this->keyName = 'id';
        $this->keyType = 'S';
        $this->fields = [
            'time_updated' => 'N',
            'streamId' => 'S',
            'field' => 'S',
            'name' => 'S',
            'time_period' => 'S'
        ];
        $this->fieldsCreateOnly = [
            'time_created' => 'N'
        ];
    }

    public function get($id)
    {
        try {
            $iterator = new ItemIterator($this->client->getItem(array(
                'TableName' => $this->table,
                'Key'       => array(
                    'id'   => array('S' => $id)
                )
            )));
        }
        catch (\Exception $e)
        {
            throw new \Data\Exceptions\DatabaseException($e->getMessage());
        }

        if ($iterator->count() == 0)
        {
            throw new \Data\Exceptions\NotFoundException();
        }

        $record = $iterator->getFirst()->toArray();

        return $record;
    }


    public function getAll()
    {
        $iterator = new ItemIterator($this->client->getIterator('Scan', array(
            'TableName' => $this->table
        )));
        $results = [];
        foreach ($iterator as $item)
        {
            $results[] = $item->toArray();
        }
        return $results;
    }

    public function create(array $data)
    {
        $id = str_random(10);
        $data['time_created'] = time();

        //Validation
        $validator = \Validator::make($data,
            $this->createRules
        );
        if ($validator->fails())
        {
            $this->errors = $validator->messages();
            throw new \Data\Exceptions\ValidationException();
        }

        $this->rawCreate($id, $data);

        return $id;
    }

    public function update($id, array $data)
    {
        $data['time_updated'] = time();

        //Validation
        $validator = \Validator::make($data,
            $this->updateRules
        );
        if ($validator->fails())
        {
            $this->errors = $validator->messages();
            throw new \Data\Exceptions\ValidationException();
        }

        $this->rawUpdate($id, $data);

        return;
    }

    public function delete($id)
    {
        $this->client->deleteItem(array(
            'TableName' => $this->table,
            'Key' => array(
                'id'   => array('S' => $id)
            )
        ));
    }
} 