<?php

declare(strict_types=1);

namespace Config;

use DI\Container;
use FastRoute\Dispatcher;

final class Router
{
    public function __construct(
        private Dispatcher $dispatcher,
        private Container $container
    ) {
    }

    public function run(string $method, string $uri): void
    {
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';
        $route = $this->dispatcher->dispatch($method, $path);

        if ($route[0] === Dispatcher::NOT_FOUND) {
            http_response_code(404);
            require dirname(__DIR__) . '/templates/error/404.php';
            return;
        }

        if ($route[0] === Dispatcher::METHOD_NOT_ALLOWED) {
            http_response_code(405);
            header('Allow: ' . implode(', ', $route[1]));
            require dirname(__DIR__) . '/templates/error/405.php';
            return;
        }

        [$controller, $action] = $route[1];
        $instance = $this->container->get($controller);
        $parameters = $this->parameters($route[2]);

        if ($method === 'POST' && in_array($action, ['store', 'update'], true)) {
            $parameters[] = $_POST;
        }

        echo $instance->$action(...$parameters);
    }

    private function parameters(array $parameters): array
    {
        return array_map(static function (string $value): int {
            return (int) $value;
        }, $parameters);
    }
}
