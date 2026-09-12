<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;

return static function (Builder $schema, string $action): void {
    if ($action === 'up') {
        if ($schema->hasTable('salles')) {
            echo "Table 'salles' existe déjà.\n";
            return;
        }

        $schema->create('salles', static function (Blueprint $table): void {
            $table->id();
            $table->string('nom', 100);
            $table->string('batiment', 100);
            $table->unsignedInteger('capacite');
            $table->string('type', 50);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        echo "Table 'salles' créée avec succès.\n";
        return;
    }

    if ($action === 'down') {
        if (!$schema->hasTable('salles')) {
            echo "Table 'salles' n'existe pas.\n";
            return;
        }

        try {
            $schema->drop('salles');
            echo "Table 'salles' supprimée.\n";
        } catch (Throwable $e) {
            echo "Impossible de supprimer la table 'salles' : la table 'reservations' la référence.\n";
            echo "Supprime d'abord les réservations avant de supprimer les salles.\n";
        }

        return;
    }
};
