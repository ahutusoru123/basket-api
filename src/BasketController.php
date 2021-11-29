<?php
use Basket\Basket;
use ProductCatalogue\ProductCatalogue;

class BasketController
{
    public function add($userId, $productCode) {
        $productCatalogue = new ProductCatalogue();
        $product = $productCatalogue->getProduct($productCode);

        if ($product == null) {
            return false;
        }

        $basket = new Basket();
        $basket->add($userId, $product);

        return true;
    }

    public function total($userId) {
        $basket = new Basket();
        return $basket->total($userId);
    }
}