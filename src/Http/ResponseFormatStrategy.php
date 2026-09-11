<?php

declare(strict_types=1);

namespace App\Http;

interface ResponseFormatStrategy
{
    public function render(array $data): string;

    public function supports(string $format): bool;
}