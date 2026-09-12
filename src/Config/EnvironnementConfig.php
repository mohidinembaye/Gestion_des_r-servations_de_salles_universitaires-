<?php

declare(strict_types=1);

namespace App\Config;

final class EnvironnementConfig
{
    public function __construct(
        private readonly string $formatSortie
    ) {
    }

    public function formatSortie(): string
    {
        return $this->formatSortie;
    }

    public static function depuisEnv(): self
    {
        return new self($_ENV['FORMAT_SORTIE'] ?? 'html');
    }
}