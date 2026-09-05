<?php

declare(strict_types=1);

use FastRoute\Dispatcher;
use function FastRoute\simpleDispatcher;

require dirname(__DIR__) . '/vendor/autoload.php';

$dispatcher = simpleDispatcher(require dirname(__DIR__) . '/routes/web.php');
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
        http_response_code(501);
        echo 'Route trouvée : ' . htmlspecialchars($controller . '::' . $action, ENT_QUOTES, 'UTF-8');
        break;
}
