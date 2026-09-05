<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\CreerReservationDTO;
use App\Exception\SalleIndisponibleException;
use App\Model\Reservation;
use App\Repository\ReservationRepositoryInterface;
use App\Repository\SalleRepositoryInterface;
use DateTimeImmutable;
use InvalidArgumentException;

final class CreerReservationService
{
    public function __construct(
        private SalleRepositoryInterface $salles,
        private ReservationRepositoryInterface $reservations,
        private DisponibiliteStrategyInterface $disponibilite
    ) {
    }

    public function executer(CreerReservationDTO $dto): Reservation
    {
        $salle = $this->salles->trouver($dto->getSalleId());

        if ($salle === null) {
            throw new SalleIndisponibleException('La salle demandée n’existe pas.');
        }

        if (!$salle->isActive()) {
            throw new SalleIndisponibleException('Cette salle ne peut pas être réservée.');
        }

        $dateDebut = $dto->getDateDebut();
        $dateFin = $dto->getDateFin();
        $maintenant = new DateTimeImmutable();

        if ($dateDebut >= $dateFin) {
            throw new InvalidArgumentException('La date de début doit précéder la date de fin.');
        }

        if ($dateDebut < $maintenant) {
            throw new InvalidArgumentException('La réservation doit commencer dans le futur.');
        }

        if ($dateFin->getTimestamp() - $dateDebut->getTimestamp() > 4 * 60 * 60) {
            throw new InvalidArgumentException('Une réservation ne peut pas dépasser quatre heures.');
        }

        $this->disponibilite->verifier($salle, $dateDebut, $dateFin);

        $reservation = new Reservation(
            $salle,
            $dto->getResponsable(),
            $dto->getEmail(),
            $dto->getMotif(),
            $dateDebut,
            $dateFin,
            'confirmée'
        );
        $reservation->setAttribute('salle_id', $dto->getSalleId());
        $reservation->setAttribute('responsable', $dto->getResponsable());
        $reservation->setAttribute('email', $dto->getEmail());
        $reservation->setAttribute('motif', $dto->getMotif());
        $reservation->setAttribute('date_debut', $dateDebut);
        $reservation->setAttribute('date_fin', $dateFin);
        $reservation->setAttribute('statut', 'confirmée');

        return $this->reservations->enregistrer($reservation);
    }
}
