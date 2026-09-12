<?php

declare(strict_types=1);

namespace App\Repository;

use App\DTO\CreerSalleDTO;
use App\Model\Salle;
use Illuminate\Database\Eloquent\Collection;

final class JsonSalleRepository implements SalleRepositoryInterface
{
    private string $file;

    public function __construct(string $file)
    {
        $this->file = $file;
    }

    public function lister(): Collection
    {
        $data = $this->readAll();
        $salles = [];

        foreach ($data as $item) {
            $salle = new Salle(
                (string) ($item['nom'] ?? ''),
                (string) ($item['batiment'] ?? ''),
                isset($item['capacite']) ? (int) $item['capacite'] : null,
                (string) ($item['type'] ?? ''),
                isset($item['active']) ? (bool) $item['active'] : null
            );
            $salle->setAttribute('id', (int) ($item['id'] ?? 0));
            $salle->setAttribute('nom', (string) ($item['nom'] ?? ''));
            $salle->setAttribute('batiment', (string) ($item['batiment'] ?? ''));
            $salle->setAttribute('capacite', isset($item['capacite']) ? (int) $item['capacite'] : null);
            $salle->setAttribute('type', (string) ($item['type'] ?? ''));
            $salle->setAttribute('active', isset($item['active']) ? (bool) $item['active'] : null);
            $salles[] = $salle;
        }

        return new Collection($salles);
    }

    public function trouver(int $id): ?Salle
    {
        $data = $this->readAll();

        foreach ($data as $item) {
            if ((int) ($item['id'] ?? 0) === $id) {
                $salle = new Salle(
                    (string) ($item['nom'] ?? ''),
                    (string) ($item['batiment'] ?? ''),
                    isset($item['capacite']) ? (int) $item['capacite'] : null,
                    (string) ($item['type'] ?? ''),
                    isset($item['active']) ? (bool) $item['active'] : null
                );
                $salle->setAttribute('id', $id);

                return $salle;
            }
        }

        return null;
    }

    public function enregistrer(CreerSalleDTO $dto): Salle
    {
        $data = $this->readAll();
        $id = $this->nextId($data);

        $item = [
            'id' => $id,
            'nom' => $dto->getNom(),
            'batiment' => $dto->getBatiment(),
            'capacite' => $dto->getCapacite(),
            'type' => $dto->getType(),
            'active' => $dto->isActive(),
        ];

        $data[] = $item;
        $this->writeAll($data);

        $salle = new Salle(
            $dto->getNom(),
            $dto->getBatiment(),
            $dto->getCapacite(),
            $dto->getType(),
            $dto->isActive()
        );
        $salle->setAttribute('id', $id);

        return $salle;
    }

    public function modifier(int $id, CreerSalleDTO $dto): Salle
    {
        $data = $this->readAll();
        $found = false;

        foreach ($data as &$item) {
            if ((int) ($item['id'] ?? 0) === $id) {
                $item['nom'] = $dto->getNom();
                $item['batiment'] = $dto->getBatiment();
                $item['capacite'] = $dto->getCapacite();
                $item['type'] = $dto->getType();
                $item['active'] = $dto->isActive();
                $found = true;
                break;
            }
        }

        if (!$found) {
            throw new \RuntimeException('Salle introuvable.');
        }

        $this->writeAll($data);

        $salle = new Salle(
            $dto->getNom(),
            $dto->getBatiment(),
            $dto->getCapacite(),
            $dto->getType(),
            $dto->isActive()
        );
        $salle->setAttribute('id', $id);

        return $salle;
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
