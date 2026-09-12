<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;

return static function (Builder $schema, string $action): void {
    if ($action === 'up') {
        if ($schema->hasTable('reservations')) {
            echo "Table 'reservations' existe déjà.\n";
            return;
        }

        $schema->create('reservations', static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('salle_id')->constrained('salles')->cascadeOnDelete();
            $table->string('responsable', 120);
            $table->string('email', 255);
            $table->string('motif', 255);
            $table->dateTime('date_debut');
            $table->dateTime('date_fin');
            $table->string('statut', 20)->default('confirmée');
            $table->timestamps();

            $table->index(['salle_id', 'date_debut', 'date_fin'], 'idx_reservations_salle_dates');
            $table->index('statut', 'idx_reservations_statut');
        });
        echo "Table 'reservations' créée avec succès.\n";

        return;
    }

    if ($action === 'down') {
        if ($schema->hasTable('reservations')) {
            $schema->drop('reservations');
            echo "Table 'reservations' supprimée.\n";
            return;
        }

        echo "Table 'reservations' n'existe pas.\n";
        return;
    }

    if ($schema->hasTable('reservations')) {
        $schema->drop('reservations');
        echo "Table 'reservations' supprimée.\n";
    }
};
