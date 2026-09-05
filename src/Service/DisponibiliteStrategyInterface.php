<?php

declare(strict_types=1);

namespace App\Service;

use App\Model\Salle;
use DateTimeImmutable;

interface DisponibiliteStrategyInterface
{
    public function verifier(Salle $salle, DateTimeImmutable $dateDebut, DateTimeImmutable $dateFin): void;
}
