<?php

declare(strict_types=1);

namespace App\Repository;

use App\DTO\CreerReservationDTO;
use App\Model\Reservation;
use DateTimeImmutable;
use Illuminate\Database\Eloquent\Collection;

final class EloquentReservationRepository implements ReservationRepositoryInterface
{
    public function lister(?int $salleId = null): Collection
    {
        $query = Reservation::query()->with('salle')->orderBy('date_debut');

        if ($salleId !== null) {
            $query->where('salle_id', $salleId);
        }

        return $query->get();
    }

    public function trouver(int $id): ?Reservation
    {
        return Reservation::query()->with('salle')->find($id);
    }

    public function rechercherConflit(int $salleId, DateTimeImmutable $dateDebut, DateTimeImmutable $dateFin): bool
    {
        return Reservation::query()
            ->where('salle_id', $salleId)
            ->where('statut', 'confirmée')
            ->where('date_debut', '<', $dateFin)
            ->where('date_fin', '>', $dateDebut)
            ->exists();
    }

    public function enregistrer(CreerReservationDTO $dto): Reservation
    {
        $reservation = new Reservation(
            null,
            $dto->getResponsable(),
            $dto->getEmail(),
            $dto->getMotif(),
            $dto->getDateDebut(),
            $dto->getDateFin(),
            'confirmée'
        );
        $reservation->setAttribute('salle_id', $dto->getSalleId());
        $reservation->setAttribute('responsable', $dto->getResponsable());
        $reservation->setAttribute('email', $dto->getEmail());
        $reservation->setAttribute('motif', $dto->getMotif());
        $reservation->setAttribute('date_debut', $dto->getDateDebut());
        $reservation->setAttribute('date_fin', $dto->getDateFin());
        $reservation->setAttribute('statut', 'confirmée');
        $reservation->save();

        return $reservation;
    }

    public function annuler(Reservation $reservation): Reservation
    {
        $reservation->setAttribute('statut', 'annulée');
        $reservation->save();

        return $reservation;
    }
}
