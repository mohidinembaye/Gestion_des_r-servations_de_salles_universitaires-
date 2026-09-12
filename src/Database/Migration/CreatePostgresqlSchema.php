<?php

declare(strict_types=1);

namespace App\Database\Migration;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;

final class CreatePostgresqlSchema extends Migration
{
    public function up(Builder $schema): void
    {
        if ($schema->hasTable('responsables')) {
            echo "Table 'responsables' existe déjà.\n";
            return;
        }

        $schema->create('responsables', static function (Blueprint $table): void {
            $table->id();
            $table->string('nom', 100);
            $table->string('email', 255)->unique();
            $table->string('password', 255);
            $table->timestamps();
        });

        $schema->create('salles', static function (Blueprint $table): void {
            $table->id();
            $table->string('nom', 100);
            $table->string('batiment', 100);
            $table->integer('capacite');
            $table->string('type', 30);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

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

        echo "Schéma PostgreSQL créé avec succès.\n";
    }

    public function down(Builder $schema): void
    {
        if ($schema->hasTable('reservations')) {
            $schema->drop('reservations');
        }

        if ($schema->hasTable('salles')) {
            $schema->drop('salles');
        }

        if ($schema->hasTable('responsables')) {
            $schema->drop('responsables');
        }

        echo "Schéma PostgreSQL supprimé.\n";
    }
}