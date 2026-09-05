<?php

declare(strict_types=1);

namespace App\Repository;

use App\DTO\CreerSalleDTO;
use App\Model\Salle;
use Illuminate\Database\Eloquent\Collection;

interface SalleRepositoryInterface
{
    public function lister(): Collection;

    public function trouver(int $id): ?Salle;

    public function enregistrer(CreerSalleDTO $dto): Salle;

    public function modifier(int $id, CreerSalleDTO $dto): Salle;
}
