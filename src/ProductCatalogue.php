<?php

namespace ProductCatalogue;
use Model\DynamoDbBase;

class ProductCatalogue extends DynamoDbBase
{
    protected string $table_name = PRODUCT_CATALOGUE_TABLE;

    public function getProduct($code) : array
    {
        return $this->getItem(['code' => $code]);
    }
}