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
            $table->enum('type', ['cours', 'informatique', 'laboratoire', 'amphitheatre', 'reunion']);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
        echo "Table 'salles' créée avec succès.\n";

        return;
    }

    if ($schema->hasTable('salles')) {
        try {
            $schema->drop('salles');
            echo "Table 'salles' supprimée.\n";
        } catch (Throwable $e) {
            echo "Impossible de supprimer la table 'salles' : la table 'reservations' la référence.\n";
            echo "Supprime d'abord les réservations (php mohidine reservations_migration:rollback).\n";
        }
    }
};
