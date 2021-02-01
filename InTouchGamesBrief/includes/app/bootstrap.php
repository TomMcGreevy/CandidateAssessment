<?php

require __DIR__ . '..\..\..\vendor\autoload.php';

$settings = require __DIR__ . '\settings.php';

$container = new \Slim\Container($settings);

$container['view'] = function ($container)
{
    $view = new \Slim\Views\Twig(__DIR__ . '\templates', ['cache' => false, ]);
    $view->addExtension(new \Slim\Views\TwigExtension($container->router, $container
        ->request
        ->getUri()));
    return $view;

};

$app = new \Slim\App($container);
require __DIR__ . '/src/database.php';
$database = new Database;
$connection = $database->connect();
require __DIR__ . '../routes.php';

$app->run();
