<?php

namespace App\Data\Repositories;

use Aws\SimpleDb\SimpleDbClient;
use Carbon\Carbon;
use App\Data\Exceptions\DatabaseException;

class StreamDataRepository {

    private $simpleDbClient;

    private $nextToken;

    public function __construct()
    {
        $this->simpleDbClient = $client = SimpleDbClient::factory(array(
            'key' => env('AWS_KEY'),
            'secret' => env('AWS_SECRET'),
            'region'  => 'eu-west-1'
        ));
    }


    # Public Methods


    /**
     * Fetch a chunk of data based on some filters
     * @param $streamId
     * @param null $location
     * @return array
     */
    public function getAll($streamId, $location=null)
    {
        $simpleDbSelect = "select * from `".$this->domainName($streamId)."` where date != '' order by date desc";
        if ($location)
        {
            $simpleDbSelect .= " where location = '{$location}'";
        }

        $iterator = $this->simpleDbClient->getSelectIterator([
            'SelectExpression' => $simpleDbSelect,
            'NextToken' => $this->nextToken
        ]);

        $iterator->setLimit(1000);
        //$iterator->setPageSize(10000);


        //Convert the simpleDB results into a simple array
        $resultSet = $this->parseSimpleDbResults($iterator);

        $this->nextToken = $iterator->getNextToken();

        return $resultSet;
    }


    /**
     * Fetch a range of data based on a start and end date and some optional filters
     * This performs multiple data requests to retrieve all the data requested
     * @param $streamId
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @param array $filter
     * @return array
     */
    public function getRange($streamId, Carbon $startDate, Carbon $endDate, $filter=[])
    {
        $complete = false;
        $resultSet = [];
        $nextToken = null;

        while (!$complete)
        {
            $simpleDbSelect = "select * from `".$this->domainName($streamId)."` where date > '".$startDate."' and date < '".$endDate."' ";
            foreach ($filter as $key => $value)
            {
                if ($key && $value)
                    $simpleDbSelect .= "and ".$key." = '".$value."' ";
            }
            $simpleDbSelect .= "order by date desc";

            $query = ['SelectExpression' => $simpleDbSelect];
            if ($nextToken)
            {
                $query['NextToken'] = $nextToken;
            }
            $iterator = $this->simpleDbClient->getSelectIterator($query);

            $iterator->setLimit(2500);


            //Convert the simpleDB results into a simple array
            $resultSet = array_merge($resultSet, $this->parseSimpleDbResults($iterator));

            $nextToken = $iterator->getNextToken();
            if (empty($nextToken))
            {
                $complete = true;
            }

        }

        return $resultSet;

    }


    public function get($streamId, $itemId)
    {
        $result = $this->simpleDbClient->getAttributes(array(
            'DomainName' => $this->domainName($streamId),
            'ItemName'   => $itemId,
            'Attributes' => array(
                'a', 'b'
            ),
            'ConsistentRead' => true
        ));
    }


    /**
     * Create a new data entry on the specific stream
     * @param $streamId
     * @param array $data
     * @throws \Data\Exceptions\DatabaseException
     * @return string the id/name its been created under
     */
    public function create($streamId, array $data)
    {
        try {
            $attributes = [];

            foreach ($data as $key => $value)
            {
                $attributes[] = array('Name' => $key, 'Value' => $value);
            }

            //Time stamp data for retrieval and sorting
            $attributes[] = array('Name' => 'date', 'Value' => date('Y-m-d H:i:s'));

            $itemId = str_random(50); //uuid()
            $this->simpleDbClient->putAttributes(array(
                'DomainName' => $this->domainName($streamId),
                'ItemName'   => $itemId,
                'Attributes' => $attributes
            ));
        } catch (\Exception $e) {
            throw new DatabaseException($e->getMessage());
        }

        return $itemId;
    }

    public function update($streamId, $itemId, array $data)
    {

    }


    /**
     * Create a new domain
     * @param $streamId
     */
    public function createDomain($streamId)
    {
        $this->simpleDbClient->createDomain(array('DomainName' => $this->domainName($streamId)));
    }


    /**
     * Delete a domain
     * @param $streamId
     */
    public function deleteDomain($streamId)
    {
        $this->simpleDbClient->deleteDomain(array('DomainName' => $this->domainName($streamId)));
    }

    /**
     * Delete an item of data in a domain
     * @param $streamId
     * @param $itemId
     */
    public function delete($streamId, $itemId)
    {
        $this->simpleDbClient->deleteAttributes(array(
            'DomainName' => $this->domainName($streamId),
            'ItemName'   => $itemId
        ));
    }



    # Getters and Setters

    public function setNextToken($nextToken)
    {
        $this->nextToken = $nextToken;
    }

    public function getNextToken()
    {
        return $this->nextToken;
    }


    # Private Methods

    /**
     * Get a formatted domain name for simpleDB
     * @param $streamId
     * @return string
     */
    private function domainName($streamId)
    {
        return 'data-'.$streamId;
    }

    /**
     * Parse a result set into a simple array
     * @param $iterator
     * @return array
     */
    private function parseSimpleDbResults($iterator)
    {
        //Convert the simpleDB results into a simple array
        $resultSet = [];
        foreach ($iterator as $result)
        {
            $resultSet[$result['Name']] = [];
            $resultSet[$result['Name']]['id'] = $result['Name'];
            foreach($result['Attributes'] as $attr)
            {
                $resultSet[$result['Name']][$attr['Name']] = $attr['Value'];
            }
        }
        return $resultSet;
    }
} 