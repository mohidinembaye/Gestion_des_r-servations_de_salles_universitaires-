<?php

declare(strict_types=1);

namespace App\DTO;

final class CreerSalleDTO
{
    private string $nom;
    private string $batiment;
    private int $capacite;
    private string $type;
    private bool $active;

    public function __construct(
        string $nom,
        string $batiment,
        int $capacite,
        string $type,
        bool $active
    ) {
        $this->nom = $nom;
        $this->batiment = $batiment;
        $this->capacite = $capacite;
        $this->type = $type;
        $this->active = $active;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            (string) $data['nom'],
            (string) $data['batiment'],
            (int) $data['capacite'],
            (string) $data['type'],
            (bool) $data['active']
        );
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function getBatiment(): string
    {
        return $this->batiment;
    }

    public function getCapacite(): int
    {
        return $this->capacite;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function isActive(): bool
    {
        return $this->active;
    }
}
