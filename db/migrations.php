<?php
require "../vendor/autoload.php";
use Aws\DynamoDb\Exception\DynamoDbException;
use Aws\DynamoDb\Marshaler;

echo "Provisioning data... ".PHP_EOL;

$products = [
    [
        "code" => "R01",
        "name" => "Red Widget",
        "price" => 32.95
    ],
    [
        "code" => "B01",
        "name" => "Blue Widget",
        "price" => 7.95
    ],
    [
        "code" => "G01",
        "name" => "Green Widget",
        "price" => 24.95
    ],
];
foreach ($products as $product) {
    delete(PRODUCT_CATALOGUE_TABLE, ["code" => $product["code"]]);
    add(PRODUCT_CATALOGUE_TABLE, $product);
}

$offers = [
    "type" => "SpecialOffers",
    "value" => [
        [
            "class" => "SpecialOffer\OfferBuyOneRedGetOneHalfPrice",
            "active" => true
        ]
    ]
];
delete(CONFIGURATION_TABLE, ["type" => $offers["type"]]);
add(CONFIGURATION_TABLE, $offers);

$deliveryCosts = [
    "type" => "Delivery",
    "value" => [
        50 => 4.95,
        90 => 2.95
    ]
];
delete(CONFIGURATION_TABLE, ["type" => $deliveryCosts["type"]]);
add(CONFIGURATION_TABLE, $deliveryCosts);

function add($table, $data) {
    $dynamodb = (new Aws\Sdk([
        'region'   => 'eu-central-1',
        'version'  => 'latest',
        'profile'  => 'default',
        'http'    => ['verify' => false]
    ]))->createDynamoDb();
    $marshaler = new Marshaler();

    $json = json_encode($data);

    $params = [
        'TableName' => $table,
        'Item' => $marshaler->marshalJson($json)
    ];

    try {
        $dynamodb->putItem($params);
        echo "[Table: ". $table ."] Added item: ". $json .  " \n";
    } catch (DynamoDbException $e) {
        echo "[Table: ". $table ."] Unable to add item. Error:\n";
        echo $e->getMessage() . "\n";
    }
}

function delete($name, $key) {
    $dynamodb = (new Aws\Sdk([
        'region'   => 'eu-central-1',
        'version'  => 'latest',
        'profile'  => 'default',
        'http'    => ['verify' => false]
    ]))->createDynamoDb();
    $marshaler = new Marshaler();

    $json = json_encode($key);

    $params = [
        'TableName' => $name,
        'Key' => $marshaler->marshalJson($json)
    ];

    try {
        $dynamodb->deleteItem($params);
    } catch (DynamoDbException $e) {
        echo "Unable to delete item:\n";
        echo $e->getMessage() . "\n";
    }
}