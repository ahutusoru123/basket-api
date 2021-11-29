<?php
require '../vendor/autoload.php';
date_default_timezone_set('UTC');

if (empty($_REQUEST["userId"])) {
    APIResponse(["message" => "Invalid request"]); die();
}

$total = (new BasketController())->total($_REQUEST["userId"]);

APIResponse([
    "totalAmount" => $total,
    "message" => "Total amount calculated including special offers and delivery."
]);