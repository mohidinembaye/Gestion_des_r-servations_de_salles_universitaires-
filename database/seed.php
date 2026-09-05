<?php

declare(strict_types=1);

use App\Model\Salle;
use Dotenv\Dotenv;

require dirname(__DIR__) . '/vendor/autoload.php';

$root = dirname(__DIR__);
Dotenv::createImmutable($root)->safeLoad();

$config = [
    'driver' => $_ENV['DB_DRIVER'] ?? 'mysql',
    'host' => $_ENV['DB_HOST'] ?? '127.0.0.1',
    'port' => (int) ($_ENV['DB_PORT'] ?? 3306),
    'database' => $_ENV['DB_DATABASE'] ?? 'reservation_salles',
    'username' => $_ENV['DB_USERNAME'] ?? 'root',
    'password' => $_ENV['DB_PASSWORD'] ?? '',
];

$configureDatabase = require $root . '/config/database.php';
$configureDatabase($config);

$ajouterSalle = static function (
    string $nom,
    string $batiment,
    int $capacite,
    string $type
): void {
    if (Salle::where('nom', $nom)->exists()) {
        return;
    }

    $salle = new Salle($nom, $batiment, $capacite, $type, true);
    $salle->setAttribute('nom', $nom);
    $salle->setAttribute('batiment', $batiment);
    $salle->setAttribute('capacite', $capacite);
    $salle->setAttribute('type', $type);
    $salle->setAttribute('active', true);
    $salle->save();
};

$ajouterSalle('Amphithéâtre A', 'Bâtiment principal', 250, 'amphitheatre');
$ajouterSalle('Salle B12', 'Bâtiment B', 40, 'cours');
$ajouterSalle('Laboratoire Chimie', 'Bâtiment scientifique', 24, 'laboratoire');
$ajouterSalle('Salle Informatique 1', 'Bâtiment informatique', 30, 'informatique');
$ajouterSalle('Salle de réunion', 'Bâtiment administratif', 12, 'reunion');

echo "Données initiales ajoutées.\n";
