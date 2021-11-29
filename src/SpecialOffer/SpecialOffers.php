<?php

namespace SpecialOffer;

use AsyncAws\Core\Exception\Exception;
use Configuration;

class SpecialOffers
{
    /**
     * Method to apply all offers on a set of products and return the updated product list and total price
     *
     * @param $products
     * @param $totalPrice
     * @return array
     */
    public static function apply($products, $totalPrice): array
    {
        //-- Run all active offers, active offers = offers that are enabled from the configuration
        foreach ((new Configuration())->getActiveSpecialOffers() as $offer) {
            if (class_exists($offer)) [$products, $totalPrice] = $offer::apply($products, $totalPrice);
        }

        return [$products, $totalPrice];
    }
}