<?php namespace Data\Repositories;

use Aws\DynamoDb\Exception\ValidationException;
use Aws\DynamoDb\Iterator\ItemIterator;
use Data\Exceptions\DatabaseException;

class StreamRepository extends AbstractDynamoRepository {



    public function __construct()
    {
        parent::__construct();
        if (\App::environment() == 'production')
        {
            $this->table = 'streams';
        }
        else
        {
            $this->table = \App::environment().'-streams';
        }

        $this->createRules = array(
            'name' => array('required', 'min:1'),
            'fields' => array('required', 'min:1')
        );
        $this->updateRules = array(
            'name' => array('required', 'min:1'),
            'fields' => array('required', 'min:1')
        );

        $this->keyName = 'id';
        $this->keyType = 'S';
        $this->fields = [
            'time_updated' => 'N',
            'fields' => 'S',
            'name' => 'S',
            'tags' => 'SS'
        ];
        $this->fieldsCreateOnly = [
            'time_created' => 'N'
        ];
    }

    public function get($id)
    {
        try {
            $iterator = new ItemIterator($this->client->getItem(array(
                'ConsistentRead' => true,
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
        $stream = $iterator->getFirst()->toArray();

        if (!isset($stream['tags']))
        {
            $stream['tags'] = [];
        }
        if (!isset($stream['fields']))
        {
            $stream['fields'] = [];
        }
        else
        {
            $stream['fields'] = json_decode($stream['fields'], true);
        }
        return $stream;
    }


    public function getAll()
    {
        $iterator = new ItemIterator($this->client->getIterator('Scan', array(
            'TableName' => $this->table
        )));
        $results = [];
        foreach ($iterator as $item)
        {
            $itemData = $item->toArray();
            if (!isset($itemData['fields']))
            {
                $itemData['fields'] = [];
            }
            else
            {
                $itemData['fields'] = json_decode($itemData['fields'], true);
            }
            $results[] = $itemData;
        }
        return $results;
    }

    public function create(array $data)
    {
        $streamId = str_random(10);
        $data['time_created'] = time();

        //Tidy up fields
        if (is_array($data['fields']))
        {
            $data['fields'] = json_encode($data['fields']);
        }

        //Validation
        $validator = \Validator::make($data,
            $this->createRules
        );
        if ($validator->fails())
        {
            $this->errors = $validator->messages();
            throw new \Data\Exceptions\ValidationException();
        }

        $this->rawCreate($streamId, $data);

        return $streamId;
    }

    public function update($streamId, array $data)
    {
        //Tidy up fields
        if (is_array($data['fields']))
        {
            $data['fields'] = json_encode($data['fields']);
        }
        if (!is_array($data['tags']) && !empty($data['tags']))
        {
            $data['tags'] = explode(',',$data['tags']);
        }

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

        $this->rawUpdate($streamId, $data);

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