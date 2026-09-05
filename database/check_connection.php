<?php

declare(strict_types=1);

use Dotenv\Dotenv;
use Illuminate\Database\Capsule\Manager;

require dirname(__DIR__) . '/vendor/autoload.php';

$root = dirname(__DIR__);
Dotenv::createImmutable($root)->load();

$config = [
    'driver' => $_ENV['DB_DRIVER'] ?? 'mysql',
    'host' => $_ENV['DB_HOST'] ?? '127.0.0.1',
    'port' => (int) ($_ENV['DB_PORT'] ?? 3306),
    'database' => $_ENV['DB_DATABASE'] ?? 'reservation_salles',
    'username' => $_ENV['DB_USERNAME'] ?? '',
    'password' => $_ENV['DB_PASSWORD'] ?? '',
];

/** @var callable(array<string, mixed>): Manager $configureDatabase */
$configureDatabase = require $root . '/config/database.php';
$capsule = $configureDatabase($config);
$capsule->getConnection()->getPdo()->query('SELECT 1');

echo "Connexion MySQL reussie.\n";
