<?php

declare(strict_types=1);

namespace App\View;

final class ViewRenderer
{
    public function render(string $template, array $data = []): string
    {
        $path = dirname(__DIR__, 2) . '/templates/' . $template . '.php';

        if (!is_file($path)) {
            throw new \RuntimeException('Vue introuvable : ' . $template);
        }

        extract($data, EXTR_SKIP);
        ob_start();
        require $path;

        return (string) ob_get_clean();
    }
}
