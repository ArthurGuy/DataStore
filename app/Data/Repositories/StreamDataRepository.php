<?php namespace Data\Repositories;


use Carbon\Carbon;

class StreamDataRepository {

    private $simpleDbClient;

    private $nextToken;

    public function __construct()
    {
        $this->simpleDbClient = \App::make('aws')->get('SimpleDb');
    }


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

    public function setNextToken($nextToken)
    {
        $this->nextToken = $nextToken;
    }

    public function getNextToken()
    {
        return $this->nextToken;
    }

    public function get($id)
    {

    }

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

            //$this->simpleDbClient->createDomain(array('DomainName' => 'XRdO9uGzIG'));
            $itemId = str_random(50); //uuid()
            $this->simpleDbClient->putAttributes(array(
                'DomainName' => $this->domainName($streamId),
                'ItemName'   => $itemId,
                'Attributes' => $attributes
            ));
        } catch (\Exception $e) {
            echo $e->getMessage();
            exit;
        }


        return $itemId;
    }

    public function update($id, array $data)
    {

    }

    public function createDomain($streamId)
    {
        $this->simpleDbClient->createDomain(array('DomainName' => $this->domainName($streamId)));
    }

    public function deleteDomain($streamId)
    {
        $this->simpleDbClient->deleteDomain(array('DomainName' => $this->domainName($streamId)));
    }

    public function delete($streamId, $id)
    {
        $this->simpleDbClient->deleteAttributes(array(
            'DomainName' => $this->domainName($streamId),
            'ItemName'   => $id
        ));
    }

    private function domainName($streamId)
    {
        return 'data-'.$streamId;
    }
} 