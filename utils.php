<?php
//-- fallback in place for local env
define("BASKET_TABLE", !empty( getenv("BASKET_TABLE")) ? getenv("BASKET_TABLE") : "basket-dev");
define("PRODUCT_CATALOGUE_TABLE", !empty( getenv("PRODUCT_CATALOGUE_TABLE")) ? getenv("PRODUCT_CATALOGUE_TABLE") : "product-catalogue-dev");
define("CONFIGURATION_TABLE", !empty( getenv("CONFIGURATION_TABLE")) ? getenv("CONFIGURATION_TABLE") : "configuration-dev");

function APIResponse($body, $status = 200)
{
    echo json_encode([
        "statusCode"=>$status,
        "body"=>$body
    ]);
}