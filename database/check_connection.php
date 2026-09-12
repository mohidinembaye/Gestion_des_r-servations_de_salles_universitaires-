<?php

declare(strict_types=1);

use Dotenv\Dotenv;
use Illuminate\Database\Capsule\Manager;

require dirname(__DIR__) . '/vendor/autoload.php';

$root = dirname(__DIR__);
Dotenv::createImmutable($root)->safeLoad();

/** @var callable(array=): Manager $configureDatabase */
$configureDatabase = require $root . '/config/database.php';
$capsule = $configureDatabase();

$driver = $capsule->getConnection()->getDriverName();
$capsule->getConnection()->getPdo()->query('SELECT 1');

echo "Connexion réussie à la base de données ({$driver}).\n";
