<?php

declare(strict_types=1);

namespace App\Http;

use InvalidArgumentException;

final class ResponseFormatContext
{
    private array $strategies = [];

    public function addStrategy(ResponseFormatStrategy $strategy): void
    {
        $this->strategies[] = $strategy;
    }

    public function render(string $format, array $data): string
    {
        $strategy = $this->getStrategy($format);
        return $strategy->render($data);
    }

    private function getStrategy(string $format): ResponseFormatStrategy
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->supports($format)) {
                return $strategy;
            }
        }

        throw new InvalidArgumentException("Format non supporté : {$format}");
    }
}