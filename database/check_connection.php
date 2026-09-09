<?php

declare(strict_types=1);

use Dotenv\Dotenv;
use Illuminate\Database\Capsule\Manager;

require dirname(__DIR__) . '/vendor/autoload.php';

$root = dirname(__DIR__);
Dotenv::createImmutable($root)->load();

/** @var callable(): Manager $configureDatabase */
$configureDatabase = require $root . '/config/database.php';
$capsule = $configureDatabase();
$capsule->getConnection()->getPdo()->query('SELECT 1');

echo "Connexion MySQL reussie.\n";