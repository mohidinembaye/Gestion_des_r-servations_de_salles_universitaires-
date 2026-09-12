<?php

declare(strict_types=1);

namespace App\Repository;

use App\DTO\CreerReservationDTO;
use App\Model\Reservation;
use App\Model\Salle;
use DateTimeImmutable;
use Illuminate\Database\Eloquent\Collection;

final class JsonReservationRepository implements ReservationRepositoryInterface
{
    private string $file;
    private SalleRepositoryInterface $salles;

    public function __construct(string $file, SalleRepositoryInterface $salles)
    {
        $this->file = $file;
        $this->salles = $salles;
    }

    public function lister(?int $salleId = null): Collection
    {
        $data = $this->readAll();
        $reservations = [];

        foreach ($data as $item) {
            if ($salleId !== null && (int) ($item['salle_id'] ?? 0) !== $salleId) {
                continue;
            }

            $reservations[] = $this->createModel($item);
        }

        return new Collection($reservations);
    }

    public function trouver(int $id): ?Reservation
    {
        $data = $this->readAll();

        foreach ($data as $item) {
            if ((int) ($item['id'] ?? 0) === $id) {
                return $this->createModel($item);
            }
        }

        return null;
    }

    public function rechercherConflit(int $salleId, DateTimeImmutable $dateDebut, DateTimeImmutable $dateFin): bool
    {
        $data = $this->readAll();

        foreach ($data as $item) {
            if ((int) ($item['salle_id'] ?? 0) !== $salleId) {
                continue;
            }

            if (($item['statut'] ?? '') !== 'confirmée') {
                continue;
            }

            $debut = new DateTimeImmutable((string) ($item['date_debut'] ?? ''));
            $fin = new DateTimeImmutable((string) ($item['date_fin'] ?? ''));

            if ($debut < $dateFin && $fin > $dateDebut) {
                return true;
            }
        }

        return false;
    }

    public function enregistrer(CreerReservationDTO $dto): Reservation
    {
        $data = $this->readAll();
        $id = $this->nextId($data);

        $item = [
            'id' => $id,
            'salle_id' => $dto->getSalleId(),
            'responsable' => $dto->getResponsable(),
            'email' => $dto->getEmail(),
            'motif' => $dto->getMotif(),
            'date_debut' => $dto->getDateDebut()->format('Y-m-d\TH:i:s'),
            'date_fin' => $dto->getDateFin()->format('Y-m-d\TH:i:s'),
            'statut' => 'confirmée',
        ];

        $data[] = $item;
        $this->writeAll($data);

        return $this->createModel($item);
    }

    public function modifier(int $id, CreerReservationDTO $dto): Reservation
    {
        $data = $this->readAll();
        $found = false;

        foreach ($data as &$item) {
            if ((int) ($item['id'] ?? 0) === $id) {
                $item['salle_id'] = $dto->getSalleId();
                $item['responsable'] = $dto->getResponsable();
                $item['email'] = $dto->getEmail();
                $item['motif'] = $dto->getMotif();
                $item['date_debut'] = $dto->getDateDebut()->format('Y-m-d\TH:i:s');
                $item['date_fin'] = $dto->getDateFin()->format('Y-m-d\TH:i:s');
                $found = true;
                break;
            }
        }

        if (!$found) {
            throw new \RuntimeException('Réservation introuvable.');
        }

        $this->writeAll($data);

        return $this->createModel($item);
    }

    public function annuler(Reservation $reservation): Reservation
    {
        $data = $this->readAll();

        foreach ($data as &$item) {
            if ((int) ($item['id'] ?? 0) === $reservation->getId()) {
                $item['statut'] = 'annulée';
                break;
            }
        }

        $this->writeAll($data);

        $reservation->setAttribute('statut', 'annulée');

        return $reservation;
    }

    private function createModel(array $item): Reservation
    {
        $salle = $this->salles->trouver((int) ($item['salle_id'] ?? 0));
        $dateDebut = isset($item['date_debut']) ? new DateTimeImmutable((string) $item['date_debut']) : null;
        $dateFin = isset($item['date_fin']) ? new DateTimeImmutable((string) $item['date_fin']) : null;

        $reservation = new Reservation(
            $salle,
            (string) ($item['responsable'] ?? ''),
            (string) ($item['email'] ?? ''),
            (string) ($item['motif'] ?? ''),
            $dateDebut,
            $dateFin,
            (string) ($item['statut'] ?? '')
        );
        $reservation->setAttribute('id', (int) ($item['id'] ?? 0));
        $reservation->setAttribute('salle_id', (int) ($item['salle_id'] ?? 0));
        $reservation->setAttribute('responsable', (string) ($item['responsable'] ?? ''));
        $reservation->setAttribute('email', (string) ($item['email'] ?? ''));
        $reservation->setAttribute('motif', (string) ($item['motif'] ?? ''));
        $reservation->setAttribute('date_debut', $dateDebut ? $dateDebut->format('Y-m-d H:i:s') : null);
        $reservation->setAttribute('date_fin', $dateFin ? $dateFin->format('Y-m-d H:i:s') : null);
        $reservation->setAttribute('statut', (string) ($item['statut'] ?? ''));

        return $reservation;
    }

    private function readAll(): array
    {
        if (!file_exists($this->file)) {
            return [];
        }

        $content = file_get_contents($this->file);
        $data = json_decode($content, true);

        return is_array($data) ? $data : [];
    }

    private function writeAll(array $data): void
    {
        $directory = dirname($this->file);

        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        file_put_contents($this->file, json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . PHP_EOL, LOCK_EX);
    }

    private function nextId(array $data): int
    {
        $ids = array_map(static fn (array $item): int => (int) ($item['id'] ?? 0), $data);

        return $ids ? max($ids) + 1 : 1;
    }
}
