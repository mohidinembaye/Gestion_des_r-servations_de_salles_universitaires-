<?php

declare(strict_types=1);

namespace App\Service;

use App\Exception\ReservationIntrouvableException;
use App\Model\Reservation;
use App\Repository\ReservationRepositoryInterface;

final class AnnulerReservationService
{
    public function __construct(
        private ReservationRepositoryInterface $reservations
    ) {
    }

    public function executer(int $id): Reservation
    {
        $reservation = $this->reservations->trouver($id);

        if ($reservation === null) {
            throw new ReservationIntrouvableException('La réservation demandée est introuvable.');
        }

        return $this->reservations->annuler($reservation);
    }
}
