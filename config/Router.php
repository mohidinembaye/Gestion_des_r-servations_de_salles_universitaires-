<?php

declare(strict_types=1);

namespace Config;

use App\View\ViewRenderer;
use DI\Container;
use FastRoute\Dispatcher;

final class Router
{
    public function __construct(
        private Dispatcher $dispatcher,
        private Container $container,
        private ViewRenderer $view
    ) {
    }

    public function run(string $method, string $uri): void
    {
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';
        $route = $this->dispatcher->dispatch($method, $path);

        if ($route[0] === Dispatcher::NOT_FOUND) {
            http_response_code(404);
            echo $this->view->render('error/404', [
                'title' => 'Page non trouvée',
                'errors' => ['message' => 'La page demandée est introuvable.']
            ]);
            return;
        }

        if ($route[0] === Dispatcher::METHOD_NOT_ALLOWED) {
            http_response_code(405);
            echo $this->view->render('error/405', [
                'title' => 'Méthode non autorisée',
                'errors' => ['message' => 'La méthode HTTP utilisée n\'est pas autorisée pour cette ressource.']
            ]);
            return;
        }

        [$controller, $action] = $route[1];
        $instance = $this->container->get($controller);
        $parameters = $this->parameters($route[2]);

        if ($method === 'POST') {
            $parameters[] = $_POST;
        }

        echo $instance->$action(...$parameters);
    }

    private function parameters(array $parameters): array
    {
        return array_values(array_map(static function (string $value): int|string {
            return ctype_digit($value) ? (int) $value : $value;
        }, $parameters));
    }
}
