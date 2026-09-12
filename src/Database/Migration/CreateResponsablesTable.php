<?php

declare(strict_types=1);

namespace App\Database\Migration;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;

final class CreateResponsablesTable extends Migration
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
        echo "Table 'responsables' créée avec succès.\n";
    }

    public function down(Builder $schema): void
    {
        if ($schema->hasTable('responsables')) {
            $schema->drop('responsables');
            echo "Table 'responsables' supprimée.\n";
        }
    }
}