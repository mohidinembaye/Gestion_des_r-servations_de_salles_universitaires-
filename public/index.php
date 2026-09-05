<?php

declare(strict_types=1);

use App\Container\ContainerFactory;
use FastRoute\Dispatcher;

require dirname(__DIR__) . '/vendor/autoload.php';

$container = (new ContainerFactory())->create();
$dispatcher = $container->get(Dispatcher::class);
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

$route = $dispatcher->dispatch($method, $path);

switch ($route[0]) {
    case Dispatcher::NOT_FOUND:
        http_response_code(404);
        require dirname(__DIR__) . '/templates/error/404.php';
        break;
    case Dispatcher::METHOD_NOT_ALLOWED:
        http_response_code(405);
        header('Allow: ' . implode(', ', $route[1]));
        require dirname(__DIR__) . '/templates/error/405.php';
        break;
    case Dispatcher::FOUND:
        [$controller, $action] = $route[1];
        $instance = $container->get($controller);
        $parameters = array_map(static function (string $value): int {
            return (int) $value;
        }, $route[2]);

        if (in_array($action, ['store', 'update'], true)) {
            $parameters[] = $_POST;
        }

        echo $instance->$action(...$parameters);
        break;
}
