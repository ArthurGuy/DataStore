<?php namespace Data\Repositories;

use Aws\DynamoDb\Iterator\ItemIterator;

class StreamDataRepository extends AbstractDynamoRepository {

    public function __construct()
    {
        parent::__construct();
        if (\App::environment() == 'production')
        {
            $this->table = 'stream-data';
        }
        else
        {
            $this->table = \App::environment().'-stream-data';
        }
    }

    public function getAll($streamId)
    {
        $iterator = new ItemIterator($this->client->getIterator("Query", array(
            'TableName'     => $this->table,
            'KeyConditions' => array(
                'id' => array(
                    'AttributeValueList' => array(
                        array('S' => $streamId)
                    ),
                    'ComparisonOperator' => 'EQ'
                ),
            //    'time' => array(
            //        'AttributeValueList' => array(
            //            array('N' => strtotime("-60 minutes"))
            //        ),
            //        'ComparisonOperator' => 'GT'
            //    )
            )
        )));
        $results = [];
        foreach ($iterator as $item)
        {
            $results[] = $item->toArray();
        }
        return $results;
    }

    public function get($id)
    {

    }

    public function create($streamId, array $data)
    {
        $data['id'] = $streamId;
        $data['time'] = time();

        $result = $this->client->putItem(array(
            'TableName' => $this->table,
            'Item' => $this->client->formatAttributes($data),
            'ReturnConsumedCapacity' => 'TOTAL'
        ));
    }

    public function update($id, array $data)
    {

    }

    public function delete($streamId, $id)
    {
        $this->client->deleteItem(array(
            'TableName' => $this->table,
            'Key' => array(
                'id'   => array('S' => $streamId),
                'time'   => array('N' => $id)
            )
        ));
    }
} 