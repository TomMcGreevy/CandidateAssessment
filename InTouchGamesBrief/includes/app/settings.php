<?php
define('ROOT_URL', '/InTouchGamesBrief/public_php/index.php');
$settings = [
    "settings" => [
        'determineRouteBeforeAppMiddleware' => false,
        'displayErrorDetails' => true,
        'addContentLengthHeader' => false,
        'mode' => 'development',
        'debug' => true
    ]
];

return $settings;