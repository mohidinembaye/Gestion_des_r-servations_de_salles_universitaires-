<?php

declare(strict_types=1);

namespace App\Http;

use RuntimeException;

final class HtmlResponseStrategy implements ResponseFormatStrategy
{
    public function __construct(
        private readonly string $templatesPath
    ) {
    }

    public function render(array $data): string
    {
        $template = $data['_template'] ?? '';
        
        if (empty($template)) {
            throw new RuntimeException('Aucun template fourni pour le format HTML.');
        }

        unset($data['_template']);

        $path = $this->templatesPath . '/' . $template . '.php';

        if (!is_file($path)) {
            throw new RuntimeException('Vue introuvable : ' . $template);
        }

        return $this->renderTemplate($path, $data);
    }

    public function supports(string $format): bool
    {
        return $format === 'html';
    }

    private function renderTemplate(string $path, array $data): string
    {
        extract($data, EXTR_SKIP);
        ob_start();
        require $path;

        return (string) ob_get_clean();
    }
}