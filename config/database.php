<?php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager as Capsule;

return static function (array $config): Capsule {
    $capsule = new Capsule();
    $capsule->addConnection([
        'driver' => $config['driver'],
        'host' => $config['host'],
        'port' => $config['port'],
        'database' => $config['database'],
        'username' => $config['username'],
        'password' => $config['password'],
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
    ]);
    $capsule->setAsGlobal();
    $capsule->bootEloquent();

    return $capsule;
};
