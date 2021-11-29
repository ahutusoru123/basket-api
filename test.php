<?php
include "vendor/autoload.php";

//-- tc1 : B01, G01 = $37.85
test(["B01", "G01"], 37.85);

//-- tc2 : R01, R01 = $54.37
test(["R01", "R01"], 54.37);

//-- tc3 : R01, G01 = $60.85
test(["R01", "G01"], 60.85);

//-- tc4 : B01, B01, R01, R01, R01 = $98.27
test(["B01", "B01", "R01", "R01", "R01"], 98.27);

function test($product_codes, $expected) {
    echo "TEST CASE".PHP_EOL;
    echo "Product codes: " . json_encode($product_codes);
    echo " Expected: ". $expected.PHP_EOL;

    $basket = new BasketController();
    $userId = "User_".rand(1,10000);
    foreach ($product_codes as $code) {
        $basket->add($userId, $code);
    }
    $total = $basket->total($userId);
    echo "Result: " . (($total == $expected) ? "Success" : "Failed. Expected ".$expected." got ".$total).PHP_EOL.PHP_EOL;
}