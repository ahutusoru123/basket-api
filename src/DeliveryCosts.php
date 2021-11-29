<?php

namespace Delivery;
use Configuration;
use Model\DynamoDbBase;

class DeliveryCosts extends DynamoDbBase
{
    public static function apply($totalPrice): float
    {
        foreach ((new Configuration())->getDeliveryConfig() as $key => $value) {
            if ($key > $totalPrice) {
                $totalPrice += $value;
                break;
            }
        }

        return $totalPrice;
    }
}