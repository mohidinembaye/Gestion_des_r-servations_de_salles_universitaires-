<?php

declare(strict_types=1);

namespace App\Model;

use DateTimeImmutable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

final class Reservation extends Model
{
    protected $table = 'reservations';

    private ?int $id = null;
    private ?int $salleId = null;
    private ?string $responsable = null;
    private ?string $email = null;
    private ?string $motif = null;
    private ?DateTimeImmutable $dateDebut = null;
    private ?DateTimeImmutable $dateFin = null;
    private ?string $statut = null;

    public function __construct(
        ?int $salleId = null,
        ?string $responsable = null,
        ?string $email = null,
        ?string $motif = null,
        ?DateTimeImmutable $dateDebut = null,
        ?DateTimeImmutable $dateFin = null,
        ?string $statut = null
    ) {
        parent::__construct();

        $this->salleId = $salleId;
        $this->responsable = $responsable;
        $this->email = $email;
        $this->motif = $motif;
        $this->dateDebut = $dateDebut;
        $this->dateFin = $dateFin;
        $this->statut = $statut;
    }

    public function getId(): ?int
    {
        return $this->id ?? $this->getAttribute('id');
    }

    public function getSalleId(): ?int
    {
        return $this->salleId ?? $this->getAttribute('salle_id');
    }

    public function getResponsable(): ?string
    {
        return $this->responsable ?? $this->getAttribute('responsable');
    }

    public function getEmail(): ?string
    {
        return $this->email ?? $this->getAttribute('email');
    }

    public function getMotif(): ?string
    {
        return $this->motif ?? $this->getAttribute('motif');
    }

    public function getDateDebut(): ?DateTimeImmutable
    {
        return $this->dateDebut ?? $this->getAttribute('date_debut');
    }

    public function getDateFin(): ?DateTimeImmutable
    {
        return $this->dateFin ?? $this->getAttribute('date_fin');
    }

    public function getStatut(): ?string
    {
        return $this->statut ?? $this->getAttribute('statut');
    }

    /** @return BelongsTo<Salle, $this> */
    public function salle(): BelongsTo
    {
        return $this->belongsTo(Salle::class, 'salle_id');
    }
}
