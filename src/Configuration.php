<?php

use Model\DynamoDbBase;

class Configuration extends DynamoDbBase
{
    protected string $table_name = CONFIGURATION_TABLE;

    public function getDeliveryConfig() : array {
        $result = $this->getItem(['type' => "Delivery"]);

        if (empty($result)) die("Configuration missing!");

        return $result["value"];
    }

    public function getActiveSpecialOffers() : array {
        $result = $this->getItem(['type' => "SpecialOffers"]);

        $offers = [];
        if ($result != null) {
            foreach ($result["value"] as $offer)
                if ($offer["active"]) $offers[] = $offer["class"];
        }

        return $offers;
    }
}