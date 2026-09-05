<?php

declare(strict_types=1);

namespace App\Repository;

use App\DTO\CreerReservationDTO;
use App\Model\Reservation;
use DateTimeImmutable;
use Illuminate\Database\Eloquent\Collection;

interface ReservationRepositoryInterface
{
    public function lister(?int $salleId = null): Collection;

    public function trouver(int $id): ?Reservation;

    public function rechercherConflit(int $salleId, DateTimeImmutable $dateDebut, DateTimeImmutable $dateFin): bool;

    public function enregistrer(CreerReservationDTO $dto): Reservation;

    public function annuler(Reservation $reservation): Reservation;
}
