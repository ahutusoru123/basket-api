<?php

namespace Basket;
use Delivery\DeliveryCosts;
use Model\DynamoDbBase;
use SpecialOffer\SpecialOffers;

class Basket extends DynamoDbBase
{
    protected string $table_name = BASKET_TABLE;
    private bool $logEnabled    = false;

    public function add($userId, $product)
    {
        //-- if an active basket exists for the user, add to it
        if (($basket = $this->getByUserId($userId)) != null) {
            $basket['products'][] = $product;
        } else { //-- otherwise create new basket
            $basket = [
                "userId" => $userId,
                "products" => [$product]
            ];
        }

        return $this->putItem($basket);
    }

    public function total($userId)
    {
        $totalPrice = 0.0;
        $basket = $this->getByUserId($userId);

        if ($basket == null or empty($basket["products"])) return $totalPrice;

        $products = $basket["products"];
        foreach ($products as $product) {
            $totalPrice += $product["price"];
        }

        [$products, $totalPrice] = SpecialOffers::apply($products, $totalPrice);
        $totalPrice              = DeliveryCosts::apply($totalPrice);

        $this->logTotal($userId, $products, $totalPrice);

        return round($totalPrice, 2, PHP_ROUND_HALF_DOWN);
    }

    private function logTotal($userId, $products, $totalPrice)
    {
        if ($this->logEnabled) {
            echo "Total price calculated for basket belonging to user " . $userId . " : " . $totalPrice . "$" . PHP_EOL;
            echo "Products in basket: " . PHP_EOL;
            foreach ($products as $product) echo " * " . json_encode($product) . PHP_EOL;
        }
    }

    private function getByUserId($userId)
    {
        return $this->getItem(['userId' => $userId]);
    }
}