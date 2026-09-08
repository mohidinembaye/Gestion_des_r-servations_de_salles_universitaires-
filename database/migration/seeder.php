<?php

declare(strict_types=1);

use App\Model\Salle;

return static function (): void {
    $sallesInitiales = [
        ['nom' => 'Amphithéâtre A', 'batiment' => 'Bâtiment principal', 'capacite' => 250, 'type' => 'amphitheatre', 'active' => true],
        ['nom' => 'Salle B12', 'batiment' => 'Bâtiment B', 'capacite' => 40, 'type' => 'cours', 'active' => true],
        ['nom' => 'Laboratoire Chimie', 'batiment' => 'Bâtiment scientifique', 'capacite' => 24, 'type' => 'laboratoire', 'active' => true],
        ['nom' => 'Salle Informatique 1', 'batiment' => 'Bâtiment informatique', 'capacite' => 30, 'type' => 'informatique', 'active' => true],
        ['nom' => 'Salle de réunion', 'batiment' => 'Bâtiment administratif', 'capacite' => 12, 'type' => 'reunion', 'active' => true],
    ];

    foreach ($sallesInitiales as $data) {
        if (!Salle::where('nom', $data['nom'])->exists()) {
            Salle::create($data);
            echo "Salle '{$data['nom']}' ajoutée.\n";
        } else {
            echo "Salle '{$data['nom']}' existe déjà (ignorée).\n";
        }
    }
    echo "Seeding terminé avec succès.\n";
};
