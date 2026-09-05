<?php

declare(strict_types=1);

use App\Container\ContainerFactory;

require dirname(__DIR__) . '/vendor/autoload.php';
require dirname(__DIR__) . '/config/Router.php';

$container = (new ContainerFactory())->create();
$router = new Config\Router(
    $container->get(FastRoute\Dispatcher::class),
    $container
);
$router->run(
    $_SERVER['REQUEST_METHOD'] ?? 'GET',
    $_SERVER['REQUEST_URI'] ?? '/'
);
