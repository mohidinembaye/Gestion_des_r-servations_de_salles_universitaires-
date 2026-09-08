<?php

declare(strict_types=1);

namespace App\Validation;

use Respect\Validation\Validatable;
use Respect\Validation\Validator as RespectValidator;

final class ReservationValidator implements ValidatorInterface
{
    public function validate(array $data): ValidationResult
    {
        $errors = [];
        $accepted = [];

        $salleId = $data['salle_id'] ?? null;
        if (!$this->isValid($salleId, RespectValidator::intVal()->positive())) {
            $errors['salle_id'] = 'La salle est invalide.';
        } else {
            $accepted['salle_id'] = (int) $salleId;
        }

        $responsable = $this->normalizeText($data['responsable'] ?? null);
        if (!$this->isValid($responsable, RespectValidator::stringType()->length(2, 120))) {
            $errors['responsable'] = 'Le champ responsable doit contenir entre 2 et 120 caractères.';
        } else {
            $accepted['responsable'] = $responsable;
        }

        $email = $this->normalizeText($data['email'] ?? null);
        if (!$this->isValid($email, RespectValidator::stringType()->email())) {
            $errors['email'] = 'L’adresse électronique est invalide.';
        } else {
            $accepted['email'] = $email;
        }

        $motif = $this->normalizeText($data['motif'] ?? null);
        if (!$this->isValid($motif, RespectValidator::stringType()->length(5, 255))) {
            $errors['motif'] = 'Le champ motif doit contenir entre 5 et 255 caractères.';
        } else {
            $accepted['motif'] = $motif;
        }

        $dateDebut = $data['date_debut'] ?? null;
        if (!$this->isValidDate($dateDebut)) {
            $errors['date_debut'] = 'La date est invalide.';
        } else {
            $accepted['date_debut'] = $dateDebut;
        }

        $dateFin = $data['date_fin'] ?? null;
        if (!$this->isValidDate($dateFin)) {
            $errors['date_fin'] = 'La date est invalide.';
        } else {
            $accepted['date_fin'] = $dateFin;
        }

        return new ValidationResult($errors, $accepted);
    }

    private function normalizeText(mixed $value): mixed
    {
        return is_string($value) ? trim($value) : $value;
    }

    private function isValidDate(mixed $value): bool
    {
        return $this->isValid(
            $value,
            RespectValidator::anyOf(
                RespectValidator::dateTime('Y-m-d\\TH:i'),
                RespectValidator::dateTime('Y-m-d H:i:s')
            )
        );
    }

    private function isValid(mixed $value, Validatable $rule): bool
    {
        return RespectValidator::create($rule)->isValid($value);
    }
}
