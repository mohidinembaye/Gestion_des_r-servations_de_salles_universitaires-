<?php

declare(strict_types=1);

namespace App\Validation;

use Respect\Validation\Validatable;
use Respect\Validation\Validator as RespectValidator;

final class SalleValidator implements ValidatorInterface
{
    public function validate(array $data): ValidationResult
    {
        $errors = [];
        $accepted = [];

        $nom = $this->normalizeText($data['nom'] ?? null);
        if (!$this->isValid($nom, RespectValidator::stringType()->length(2, 100))) {
            $errors['nom'] = 'Le champ nom doit contenir entre 2 et 100 caractères.';
        } else {
            $accepted['nom'] = $nom;
        }

        $batiment = $this->normalizeText($data['batiment'] ?? null);
        if (!$this->isValid($batiment, RespectValidator::stringType()->length(2, 100))) {
            $errors['batiment'] = 'Le champ batiment doit contenir entre 2 et 100 caractères.';
        } else {
            $accepted['batiment'] = $batiment;
        }

        $capacite = $data['capacite'] ?? null;
        if (!$this->isValid($capacite, RespectValidator::intVal()->between(1, 1000))) {
            $errors['capacite'] = 'La capacité doit être un entier compris entre 1 et 1000.';
        } else {
            $accepted['capacite'] = (int) $capacite;
        }

        $types = ['cours', 'informatique', 'laboratoire', 'amphitheatre', 'reunion'];
        $type = $data['type'] ?? null;
        if (!$this->isValid($type, RespectValidator::stringType()->in($types, true))) {
            $errors['type'] = 'Le type de salle est invalide.';
        } else {
            $accepted['type'] = $type;
        }

        $rawActive = $data['active'] ?? '0';
        if (!$this->isValid($rawActive, RespectValidator::boolVal())) {
            $errors['active'] = 'Le statut actif est invalide.';
        } else {
            $accepted['active'] = $this->normalizeBoolean($rawActive);
        }

        return new ValidationResult($errors, $accepted);
    }

    private function normalizeText(mixed $value): mixed
    {
        return is_string($value) ? trim($value) : $value;
    }

    private function normalizeBoolean(mixed $value): bool
    {
        if (is_string($value)) {
            $value = strtolower(trim($value));
        }

        return match ($value) {
            true, 1, '1', 'true', 'on', 'yes' => true,
            default => false,
        };
    }

    private function isValid(mixed $value, Validatable $rule): bool
    {
        return RespectValidator::create($rule)->isValid($value);
    }
}
