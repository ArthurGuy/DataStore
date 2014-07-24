<?php namespace Data\Repositories;

use Aws\DynamoDb\Iterator\ItemIterator;

class StreamDataRepository extends AbstractDynamoRepository {

    private $simpleDbClient;

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

        $this->simpleDbClient = \App::make('aws')->get('SimpleDb');

        $this->keyName = 'id';
        $this->keyType = 'S';
        $this->secondrykeyName = 'time';
        $this->secondrykeyType = 'N';
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
/*
    public function getAll($streamId, $location=null)
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
                'loc' => array(
                    'AttributeValueList' => array(
                        array('S' => $location)
                    ),
                    'ComparisonOperator' => 'EQ'
                ),
            //    'time' => array(
            //        'AttributeValueList' => array(
            //            array('N' => strtotime("-60 minutes"))
            //        ),
            //        'ComparisonOperator' => 'GT'
            //    )
            ),
            'ScanIndexForward' => false //reverse the ordering - newest first
        )));

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
*/
    public function getAll($streamId, $location=null)
    {
        $scanParams = array(
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
            ),
            'ScanIndexForward' => false //reverse the ordering - newest first
        );

        if ($location)
        {
            $scanParams['ScanFilter'] = array('location' => array(
                'AttributeValueList' => array(
                        array('S' => $location)
                    ),
                'ComparisonOperator' => 'EQ')
            );
        }

        $iterator = new ItemIterator($this->client->getIterator("Scan", $scanParams));

/*
        $iterator = new ItemIterator($this->client->getIterator('Scan', array(
            'TableName' => $this->table
        )));
*/
        $results = [];
        foreach ($iterator as $item)
        {
            $results[] = $item->toArray();
        }
        usort($results, function($a, $b) {
            return $b['time'] - $a['time'];
        });
        return $results;
    }


    public function get($id)
    {

    }

    public function create($streamId, array $data)
    {
        $data['id'] = $streamId;
        $time = $data['time'] = time();

        $result = $this->client->putItem(array(
            'TableName' => $this->table,
            'Item' => $this->client->formatAttributes($data),
            'ReturnConsumedCapacity' => 'TOTAL'
        ));


        try {
            $attributes = [];

            //This isnt wanted for simple DB
            unset($data['time']);
            unset($data['id']);

            foreach ($data as $key => $value)
            {
                $attributes[] = array('Name' => $key, 'Value' => $value);
            }

            //Time stamp data for retrieval and sorting
            $attributes[] = array('Name' => 'date', 'Value' => date('Y-m-d H:i:s'));

            //$this->simpleDbClient->createDomain(array('DomainName' => 'XRdO9uGzIG'));
            $this->simpleDbClient->putAttributes(array(
                'DomainName' => $streamId,
                'ItemName'   => str_random(50),
                'Attributes' => $attributes
            ));
        } catch (\Exception $e) {
            echo $e->getMessage();
            exit;
        }


        return $time;
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