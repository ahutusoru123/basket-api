<?php

namespace Model;

use Aws;
use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;

abstract class DynamoDbBase
{
    protected Marshaler $marshaler;
    protected DynamoDbClient $dynamoDbClient;

    public function __construct()
    {
        $this->marshaler = new Marshaler();

        $sdk = new Aws\Sdk([
            'region' => 'eu-central-1',
            'version' => 'latest',
            'http' => ['verify' => false],
        ]);

        $this->dynamoDbClient = $sdk->createDynamoDb();
    }

    public function getItem($params)
    {
        $params = $this->prepareGetParams($params);
        try {
            $result = $this->dynamoDbClient->getItem($params);
        } catch (DynamoDbException $e) {
            echo "Unable to get item:\n";
            echo $e->getMessage() . "\n";
        }

        return (!empty($result['Item'])) ? $this->marshaler->unmarshalItem($result["Item"]) : [];
    }

    private function prepareGetParams($params) {
        return [
            'TableName' => $this->table_name,
            'Key' => $this->marshaler->marshalItem($params)
        ];
    }

    public function putItem($params)
    {
        $params = $this->preparePutParams($params);
        try {
            $this->dynamoDbClient->putItem($params);
        } catch (DynamoDbException $e) {
            echo "Unable to add new item:\n";
            echo $e->getMessage() . "\n";
            return false;
        }

        return true;
    }

    private function preparePutParams($params) {
        return [
            'TableName' => $this->table_name,
            'Item' => $this->marshaler->marshalItem($params)
        ];
    }
}
