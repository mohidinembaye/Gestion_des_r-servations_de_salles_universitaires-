<?php

declare(strict_types=1);

namespace App\DTO;

use DateTimeImmutable;

final class CreerReservationDTO
{
    private int $salleId;
    private string $responsable;
    private string $email;
    private string $motif;
    private DateTimeImmutable $dateDebut;
    private DateTimeImmutable $dateFin;

    public function __construct(
        int $salleId,
        string $responsable,
        string $email,
        string $motif,
        DateTimeImmutable $dateDebut,
        DateTimeImmutable $dateFin
    ) {
        $this->salleId = $salleId;
        $this->responsable = $responsable;
        $this->email = $email;
        $this->motif = $motif;
        $this->dateDebut = $dateDebut;
        $this->dateFin = $dateFin;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            (int) ($data['salle_id'] ?? $data['salleId']),
            (string) $data['responsable'],
            (string) $data['email'],
            (string) $data['motif'],
            $data['date_debut'] instanceof DateTimeImmutable
                ? $data['date_debut']
                : new DateTimeImmutable((string) $data['date_debut']),
            $data['date_fin'] instanceof DateTimeImmutable
                ? $data['date_fin']
                : new DateTimeImmutable((string) $data['date_fin'])
        );
    }

    public function getSalleId(): int
    {
        return $this->salleId;
    }

    public function getResponsable(): string
    {
        return $this->responsable;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getMotif(): string
    {
        return $this->motif;
    }

    public function getDateDebut(): DateTimeImmutable
    {
        return $this->dateDebut;
    }

    public function getDateFin(): DateTimeImmutable
    {
        return $this->dateFin;
    }
}
