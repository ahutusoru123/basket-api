<?php

namespace SpecialOffer;

class OfferBuyOneRedGetOneHalfPrice implements Offer
{
    private static string $productCode = "R01";

    /**
     * Apply 50% discount on each second R01 product in the list
     *
     * @param $products
     * @param $totalPrice
     * @return array
     */
    public static function apply($products, $totalPrice): array
    {
        $flag = false;
        for ($i = 0; $i < count($products); $i++) {
            if (($products[$i]["code"] == self::$productCode) && $flag) {
                $totalPrice -= $products[$i]["price"] / 2;
                $products[$i]["price"] -= $products[$i]["price"] / 2;
                $products[$i]["discount"] = "50% discount applied";
            }

            if ($products[$i]["code"] == self::$productCode) $flag = !$flag;
        }

        return [$products, $totalPrice];
    }
}