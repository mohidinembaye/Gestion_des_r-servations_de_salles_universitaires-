<?php

declare(strict_types=1);

namespace App\View;

use App\Config\EnvironnementConfig;
use App\Http\HtmlResponseStrategy;
use App\Http\JsonResponseStrategy;
use App\Http\ResponseFormatContext;

final class ViewRenderer
{
    public function __construct(
        private readonly EnvironnementConfig $environnement,
        private readonly ResponseFormatContext $formatContext
    ) {
    }

    public function render(string $template, array $data = [], bool $withLayout = true): string
    {
        $format = $this->formatDemande();

        if ($format === 'json') {
            return $this->formatContext->render($format, $data);
        }

        $content = $this->formatContext->render($format, $data + ['_template' => $template]);

        if ($template === 'layout/base' || !$withLayout) {
            return $content;
        }

        return $this->formatContext->render($format, $data + [
            '_template' => 'layout/base',
            'content' => $content,
            'title' => $data['title'] ?? 'Réservations'
        ]);
    }

    private function formatDemande(): string
    {
        return $_GET['format'] ?? $this->environnement->formatSortie();
    }
}