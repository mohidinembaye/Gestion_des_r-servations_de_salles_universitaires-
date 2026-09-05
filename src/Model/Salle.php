<?php

declare(strict_types=1);

namespace App\Model;

use Illuminate\Database\Eloquent\HasMany;
use Illuminate\Database\Eloquent\Model;

final class Salle extends Model
{
    protected $table = 'salles';

    private ?int $id = null;
    private ?string $nom = null;
    private ?string $batiment = null;
    private ?int $capacite = null;
    private ?string $type = null;
    private ?bool $active = null;

    public function __construct(
        ?string $nom = null,
        ?string $batiment = null,
        ?int $capacite = null,
        ?string $type = null,
        ?bool $active = null
    ) {
        parent::__construct();

        $this->nom = $nom;
        $this->batiment = $batiment;
        $this->capacite = $capacite;
        $this->type = $type;
        $this->active = $active;
    }

    public function getId(): ?int
    {
        return $this->id ?? $this->getAttribute('id');
    }

    public function getNom(): ?string
    {
        return $this->nom ?? $this->getAttribute('nom');
    }

    public function getBatiment(): ?string
    {
        return $this->batiment ?? $this->getAttribute('batiment');
    }

    public function getCapacite(): ?int
    {
        return $this->capacite ?? $this->getAttribute('capacite');
    }

    public function getType(): ?string
    {
        return $this->type ?? $this->getAttribute('type');
    }

    public function isActive(): ?bool
    {
        return $this->active ?? $this->getAttribute('active');
    }

    /** @return HasMany<Reservation, $this> */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class, 'salle_id');
    }
}
