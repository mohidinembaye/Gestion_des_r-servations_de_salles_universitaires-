<?php

declare(strict_types=1);

namespace App\DTO;

use DateTimeImmutable;
use LogicException;

final class CreerReservationDTOBuilder
{
    private ?int $salleId = null;
    private ?string $responsable = null;
    private ?string $email = null;
    private ?string $motif = null;
    private ?DateTimeImmutable $dateDebut = null;
    private ?DateTimeImmutable $dateFin = null;

    public function salleId(int $salleId): self
    {
        $this->salleId = $salleId;

        return $this;
    }

    public function responsable(string $responsable): self
    {
        $this->responsable = $responsable;

        return $this;
    }

    public function email(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function motif(string $motif): self
    {
        $this->motif = $motif;

        return $this;
    }

    public function dateDebut(DateTimeImmutable $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function dateFin(DateTimeImmutable $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function build(): CreerReservationDTO
    {
        if ($this->salleId === null || $this->responsable === null || $this->email === null || $this->motif === null || $this->dateDebut === null || $this->dateFin === null) {
            throw new LogicException('Toutes les données de la réservation sont obligatoires.');
        }

        return new CreerReservationDTO(
            $this->salleId,
            $this->responsable,
            $this->email,
            $this->motif,
            $this->dateDebut,
            $this->dateFin
        );
    }
}
