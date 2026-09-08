<?php

declare(strict_types=1);

namespace App;

use Illuminate\Database\Capsule\Manager as Capsule;

final class Application
{
    private const MIGRATIONS_PATH = __DIR__ . '/../database/migration';

    public function __construct(private readonly Capsule $capsule)
    {
    }

    public function run(array $argv): int
    {
        $commande = $argv[1] ?? '';
        $schema = $this->capsule->schema();

        $migrerSalles = require self::MIGRATIONS_PATH . '/salles_migration.php';
        $migrerReservations = require self::MIGRATIONS_PATH . '/reservations_migration.php';
        $seeder = require self::MIGRATIONS_PATH . '/seeder.php';

        switch ($commande) {
            case 'salles_migration:execute':
                echo "==> Exécution de la migration 'salles'...\n";
                $migrerSalles($schema, 'up');
                break;

            case 'salles_migration:rollback':
                echo "==> Annulation de la migration 'salles'...\n";
                $migrerSalles($schema, 'down');
                break;

            case 'reservations_migration:execute':
                echo "==> Exécution de la migration 'reservations'...\n";
                $migrerReservations($schema, 'up');
                break;

            case 'reservations_migration:rollback':
                echo "==> Annulation de la migration 'reservations'...\n";
                $migrerReservations($schema, 'down');
                break;

            case 'seeder:execute':
                echo "==> Exécution du seeder...\n";
                $seeder();
                break;

            case 'fresh':
                echo "==> Réinitialisation complète (fresh)...\n";
                $migrerReservations($schema, 'down');
                $migrerSalles($schema, 'down');
                $migrerSalles($schema, 'up');
                $migrerReservations($schema, 'up');
                $seeder();
                echo "Base de données réinitialisée et peuplée avec succès.\n";
                break;

            default:
                echo "Commande non reconnue : '$commande'\n\n";
                echo "Utilisation : php mohidine [commande]\n";
                echo "Commandes disponibles :\n";
                echo "  salles_migration:execute        : Créer la table 'salles'\n";
                echo "  salles_migration:rollback       : Supprimer la table 'salles'\n";
                echo "  reservations_migration:execute  : Créer la table 'reservations'\n";
                echo "  reservations_migration:rollback : Supprimer la table 'reservations'\n";
                echo "  seeder:execute                  : Peupler la base avec les données initiales\n";
                echo "  fresh                           : Supprimer, recréer les tables et exécuter le seeder\n";
                return 1;
        }

        return 0;
    }
}
