<?php

declare(strict_types=1);

namespace App\Validation;

final class ValidationResult
{
    private bool $valid;
    private array $errors;
    private array $data;

    public function __construct(array $errors, array $data)
    {
        $this->errors = $errors;
        $this->data = $data;
        $this->valid = $errors === [];
    }

    public function isValid(): bool
    {
        return $this->valid;
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function data(): array
    {
        return $this->data;
    }
}
