<?php

declare(strict_types=1);

namespace App\Repository;

use App\DTO\CreerSalleDTO;
use App\Model\Salle;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Eloquent\Collection;

final class EloquentSalleRepository implements SalleRepositoryInterface
{
    public function __construct(
        private Manager $database
    ) {
    }

    public function lister(): Collection
    {
        return Salle::query()->orderBy('nom')->get();
    }

    public function trouver(int $id): ?Salle
    {
        return Salle::query()->find($id);
    }

    public function enregistrer(CreerSalleDTO $dto): Salle
    {
        $salle = new Salle(
            $dto->getNom(),
            $dto->getBatiment(),
            $dto->getCapacite(),
            $dto->getType(),
            $dto->isActive()
        );
        $salle->setAttribute('nom', $dto->getNom());
        $salle->setAttribute('batiment', $dto->getBatiment());
        $salle->setAttribute('capacite', $dto->getCapacite());
        $salle->setAttribute('type', $dto->getType());
        $salle->setAttribute('active', $dto->isActive());
        $salle->save();

        return $salle;
    }

    public function modifier(int $id, CreerSalleDTO $dto): Salle
    {
        $salle = $this->trouver($id);

        if ($salle === null) {
            throw new \RuntimeException('Salle introuvable.');
        }

        $salle->setAttribute('nom', $dto->getNom());
        $salle->setAttribute('batiment', $dto->getBatiment());
        $salle->setAttribute('capacite', $dto->getCapacite());
        $salle->setAttribute('type', $dto->getType());
        $salle->setAttribute('active', $dto->isActive());
        $salle->save();

        return $salle;
    }
}
