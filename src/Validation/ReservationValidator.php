<?php

declare(strict_types=1);

namespace App\Validation;

use DateTimeImmutable;

final class ReservationValidator implements ValidatorInterface
{
    public function validate(array $data): ValidationResult
    {
        $errors = [];
        $accepted = [];

        $salleId = $data['salle_id'] ?? null;
        if (filter_var($salleId, FILTER_VALIDATE_INT) === false || (int) $salleId < 1) {
            $errors['salle_id'] = 'La salle est invalide.';
        } else {
            $accepted['salle_id'] = (int) $salleId;
        }

        $this->validateText('responsable', $data['responsable'] ?? null, 2, 120, $errors, $accepted);

        $email = $data['email'] ?? null;
        if (!is_string($email) || filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $errors['email'] = 'L’adresse électronique est invalide.';
        } else {
            $accepted['email'] = $email;
        }

        $this->validateText('motif', $data['motif'] ?? null, 5, 255, $errors, $accepted);
        $this->validateDate('date_debut', $data['date_debut'] ?? null, $errors, $accepted);
        $this->validateDate('date_fin', $data['date_fin'] ?? null, $errors, $accepted);

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

    private function validateDate(string $field, mixed $value, array &$errors, array &$accepted): void
    {
        if (!is_string($value)) {
            $errors[$field] = 'La date est invalide.';
            return;
        }

        $date = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $value);
        $dateErrors = DateTimeImmutable::getLastErrors();
        if ($date === false || ($dateErrors !== false && ($dateErrors['warning_count'] > 0 || $dateErrors['error_count'] > 0))) {
            $errors[$field] = 'La date est invalide.';
            return;
        }

        $accepted[$field] = $value;
    }
}
