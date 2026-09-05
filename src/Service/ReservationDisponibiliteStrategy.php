<?php

declare(strict_types=1);

namespace App\Service;

use App\Exception\SalleIndisponibleException;
use App\Model\Salle;
use App\Repository\ReservationRepositoryInterface;
use DateTimeImmutable;

final class ReservationDisponibiliteStrategy implements DisponibiliteStrategyInterface
{
    public function __construct(
        private ReservationRepositoryInterface $reservations
    ) {
    }

    public function verifier(Salle $salle, DateTimeImmutable $dateDebut, DateTimeImmutable $dateFin): void
    {
        if ($this->reservations->rechercherConflit($salle->getId(), $dateDebut, $dateFin)) {
            throw new SalleIndisponibleException('La salle est indisponible pendant cette période.');
        }
    }
}
