<?php

declare(strict_types=1);

namespace App\Http;

final class JsonResponseStrategy implements ResponseFormatStrategy
{
    public function render(array $data): string
    {
        header('Content-Type: application/json; charset=utf-8');

        return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    public function supports(string $format): bool
    {
        return $format === 'json';
    }
}