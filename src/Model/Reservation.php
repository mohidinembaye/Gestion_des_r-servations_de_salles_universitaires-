<?php

declare(strict_types=1);

namespace App\Model;

use DateTimeImmutable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

final class Reservation extends Model
{
    protected $table = 'reservations';

    protected $fillable = [
        'salle_id',
        'responsable',
        'email',
        'motif',
        'date_debut',
        'date_fin',
        'statut',
    ];

    private ?int $id = null;
    private ?Salle $salle = null;
    private ?string $responsable = null;
    private ?string $email = null;
    private ?string $motif = null;
    private ?string $statut = null;
    private ?DateTimeImmutable $createdAt = null;
    private ?DateTimeImmutable $updatedAt = null;

    public function __construct(
        ?Salle $salle = null,
        ?string $responsable = null,
        ?string $email = null,
        ?string $motif = null,
        ?DateTimeImmutable $dateDebut = null,
        ?DateTimeImmutable $dateFin = null,
        ?string $statut = null,
        ?DateTimeImmutable $createdAt = null,
        ?DateTimeImmutable $updatedAt = null
    ) {
        parent::__construct();

        $this->salle = $salle;
        $this->responsable = $responsable;
        $this->email = $email;
        $this->motif = $motif;
        $this->statut = $statut;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public function getId(): ?int
    {
        return $this->id ?? $this->getAttribute('id');
    }

    public function getSalleId(): ?int
    {
        return $this->salle?->getId() ?? $this->getAttribute('salle_id');
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
        return $this->toImmutableDate($this->getAttribute('date_debut'));
    }

    public function getDateFin(): ?DateTimeImmutable
    {
        return $this->toImmutableDate($this->getAttribute('date_fin'));
    }

    public function getStatut(): ?string
    {
        return $this->statut ?? $this->getAttribute('statut');
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->toImmutableDate($this->createdAt ?? $this->getAttribute('created_at'));
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->toImmutableDate($this->updatedAt ?? $this->getAttribute('updated_at'));
    }

    private function toImmutableDate(mixed $value): ?DateTimeImmutable
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof DateTimeImmutable) {
            return $value;
        }

        if ($value instanceof DateTimeInterface) {
            return DateTimeImmutable::createFromInterface($value);
        }

        return new DateTimeImmutable((string) $value);
    }

    public function getSalle(): ?Salle
    {
        if ($this->salle !== null) {
            return $this->salle;
        }

        return $this->relationLoaded('salle') ? $this->getRelationValue('salle') : null;
    }

    public function salle(): BelongsTo
    {
        return $this->belongsTo(Salle::class, 'salle_id');
    }
}
