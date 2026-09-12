<?php

declare(strict_types=1);

namespace App\View;

final class ViewRenderer
{
    public function __construct() {
    }

    public function render(string $template, array $data = []): string
    {
        $path = dirname(__DIR__, 2) . '/templates/' . $template . '.php';

        if (!is_file($path)) {
            throw new \RuntimeException('Vue introuvable : ' . $template);
        }

        $content = $this->renderTemplate($path, $data);

        if ($template === 'layout/base') {
            return $content;
        }

        return $this->renderTemplate(
            dirname(__DIR__, 2) . '/templates/layout/base.php',
            $data + ['content' => $content, 'title' => $data['title'] ?? 'Réservations']
        );
    }

    private function renderTemplate(string $path, array $data): string
    {
        extract($data, EXTR_SKIP);
        ob_start();
        require $path;

        return (string) ob_get_clean();
    }
}