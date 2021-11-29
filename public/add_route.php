<?php
require __DIR__.'/../vendor/autoload.php';
date_default_timezone_set('UTC');

if (empty($_REQUEST["productCode"]) || empty($_REQUEST["userId"])) {
    APIResponse(["message" => "Invalid request"]); die();
}

$status = ((new BasketController())->add($_REQUEST["userId"], $_REQUEST["productCode"]));

APIResponse([
    "message" => ($status) ? "Successfully added product!" : "Failed to add product.",
]);
