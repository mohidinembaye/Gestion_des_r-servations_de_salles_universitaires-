<?php

declare(strict_types=1);

namespace App\Validation;

final class SalleValidator implements ValidatorInterface
{
    public function validate(array $data): ValidationResult
    {
        $errors = [];
        $accepted = [];

        $this->validateText('nom', $data['nom'] ?? null, 2, 100, $errors, $accepted);
        $this->validateText('batiment', $data['batiment'] ?? null, 2, 100, $errors, $accepted);

        $capacite = $data['capacite'] ?? null;
        if (filter_var($capacite, FILTER_VALIDATE_INT) === false || (int) $capacite < 1 || (int) $capacite > 1000) {
            $errors['capacite'] = 'La capacité doit être un entier compris entre 1 et 1000.';
        } else {
            $accepted['capacite'] = (int) $capacite;
        }

        $types = ['cours', 'informatique', 'laboratoire', 'amphitheatre', 'reunion'];
        if (!is_string($data['type'] ?? null) || !in_array($data['type'], $types, true)) {
            $errors['type'] = 'Le type de salle est invalide.';
        } else {
            $accepted['type'] = $data['type'];
        }

        if (!is_bool($data['active'] ?? null)) {
            $errors['active'] = 'Le statut actif doit être un booléen.';
        } else {
            $accepted['active'] = $data['active'];
        }

        return new ValidationResult($errors, $accepted);
    }

    private function validateText(
        string $field,
        mixed $value,
        int $minimum,
        int $maximum,
        array &$errors,
        array &$accepted
    ): void {
        if (!is_string($value) || strlen(trim($value)) < $minimum || strlen($value) > $maximum) {
            $errors[$field] = "Le champ $field doit contenir entre $minimum et $maximum caractères.";
            return;
        }

        $accepted[$field] = trim($value);
    }
}
