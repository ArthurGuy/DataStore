<?php

namespace App\Data\Repositories;

use App\Data\SimpleDB;
use Carbon\Carbon;
use App\Data\Exceptions\DatabaseException;

class StreamDataRepository {

    private $simpleDbClient;

    private $nextToken;

    public function __construct()
    {

        $this->simpleDbClient = new SimpleDB(env('AWS_KEY'), env('AWS_SECRET'));

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

        $results = $this->simpleDbClient->select(null, $simpleDbSelect, $this->nextToken);

        $resultSet = $this->parseSimpleDbResults($results);

        $this->nextToken = $this->simpleDbClient->getNextToken();

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
            //$iterator = $this->simpleDbClient->getSelectIterator($query);
            $results = $this->simpleDbClient->select(null, $simpleDbSelect, $this->nextToken);

            //$iterator->setLimit(2500);


            //Convert the simpleDB results into a simple array
            $resultSet = array_merge($resultSet, $this->parseSimpleDbResults($results));

            //$nextToken = $iterator->getNextToken();
            $this->nextToken = $this->simpleDbClient->getNextToken();
            if (empty($nextToken))
            {
                $complete = true;
            }

        }

        return $resultSet;

    }


    public function get($streamId, $itemId)
    {
        return $this->simpleDbClient->getAttributes($this->domainName($streamId), $itemId);
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
                $attributes[$key] = ['value' => $value];
            }

            //Time stamp data for retrieval and sorting
            $attributes['date'] = ['value' => date('Y-m-d H:i:s')];

            $itemId = str_random(50); //uuid()

            $this->simpleDbClient->putAttributes($this->domainName($streamId), $itemId, $attributes);

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
        $this->simpleDbClient->createDomain($this->domainName($streamId));
    }


    /**
     * Delete a domain
     * @param $streamId
     */
    public function deleteDomain($streamId)
    {
        $this->simpleDbClient->deleteDomain($this->domainName($streamId));
    }

    /**
     * Delete an item of data in a domain
     * @param $streamId
     * @param $itemId
     */
    public function delete($streamId, $itemId)
    {
        $this->simpleDbClient->deleteAttributes($this->domainName($streamId), $itemId);
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
            foreach($result['Attributes'] as $key => $value)
            {
                $resultSet[$result['Name']][$key] = $value;
            }
        }
        return $resultSet;
    }
} 