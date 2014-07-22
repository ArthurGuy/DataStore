<?php namespace Data\Repositories;

use Illuminate\Support\Facades\App;
use Aws\DynamoDb\Iterator\ItemIterator;

abstract class AbstractDynamoRepository {

    protected $table;

    protected $errors;

    protected $client;

    protected $fields;
    protected $fieldsCreateOnly;
    protected $keyName;
    protected $keyType;
    protected $secondrykeyName;
    protected $secondrykeyType;

    public function __construct()
    {
        $this->client = App::make('aws')->get('dynamodb');
    }

    public function getErrors()
    {
        return $this->errors;
    }

    protected function rawUpdate($key, $data)
    {
        try {
            $updateData = [
                'TableName' => $this->table,
                'Key' => [
                    $this->keyName => [
                        $this->keyType => $key,
                    ],
                ],
                'AttributeUpdates' => []
            ];

            foreach ($this->fields as $fieldName => $fieldType)
            {
                if (isset($data[$fieldName]) && !empty($data[$fieldName]))
                {
                    $updateData['AttributeUpdates'][$fieldName] = ['Value' => [$fieldType => $data[$fieldName]], 'Action' => 'PUT'];
                }
                else
                {
                    $updateData['AttributeUpdates'][$fieldName] = ['Action' => 'DELETE'];
                }
            }

            $this->client->updateItem($updateData);
        }
        catch (ValidationException $e)
        {
            throw new \Data\Exceptions\DatabaseException($e->getMessage());
        }
        catch (\Exception $e)
        {
            throw new \Data\Exceptions\DatabaseException($e->getMessage());
        }
    }

    protected function rawCreate($key, $data)
    {
        try {
            $createData = [
                'TableName' => $this->table,
                'Item' => []
            ];
            if (is_array($key))
            {
                $createData['Item'][$this->keyName] = [$this->keyType => $key[0]];
                $createData['Item'][$this->secondrykeyName] = [$this->secondrykeyType => $key[1]];
            }
            else
            {
                $createData['Item'][$this->keyName] = [$this->keyType => $key];
            }

            foreach ($this->fields as $fieldName => $fieldType)
            {
                if (isset($data[$fieldName]) && !empty($data[$fieldName]))
                {
                    $createData['Item'][$fieldName] = [$fieldType => $data[$fieldName]];
                }
            }
            foreach ($this->fieldsCreateOnly as $fieldName => $fieldType)
            {
                $createData['Item'][$fieldName] = [$fieldType => $data[$fieldName]];
            }
            $this->client->putItem($createData);
        }
        catch (ValidationException $e)
        {
            throw new \Data\Exceptions\DatabaseException($e->getMessage());
        }
    }
} 