<?php

namespace SpecialOffer;
interface Offer
{
    /**
     * Interface for all offer classes
     *
     * @param $products
     * @param $totalPrice
     * @return array
     */
    public static function apply($products, $totalPrice): array;
}