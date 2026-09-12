<?php
declare(strict_types=1);

use Illuminate\Database\Capsule\Manager as Capsule;

return static function (array $overrideConfig = []): Capsule {
    $dbUrl = $overrideConfig['url'] ?? $_ENV['DATABASE_URL'] ?? getenv('DATABASE_URL') ?: null;

    if (!empty($dbUrl)) {
        $parsed = parse_url((string)$dbUrl);
        $scheme = $parsed['scheme'] ?? 'pgsql';
        $driver = ($scheme === 'postgres' || $scheme === 'postgresql') ? 'pgsql' : $scheme;
        $defaultPort = ($driver === 'pgsql') ? 5432 : 3306;

        $connection = [
            'driver'    => $driver,
            'host'      => $parsed['host'] ?? '127.0.0.1',
            'port'      => (int) ($parsed['port'] ?? $defaultPort),
            'database'  => ltrim($parsed['path'] ?? '', '/'),
            'username'  => isset($parsed['user']) ? urldecode($parsed['user']) : 'postgres',
            'password'  => isset($parsed['pass']) ? urldecode($parsed['pass']) : '',
            'prefix'    => '',
        ];
    } else {
        $driver = $overrideConfig['driver'] ?? $_ENV['DB_DRIVER'] ?? 'pgsql';
        $defaultPort = ($driver === 'pgsql') ? 5432 : 3306;

        $connection = [
            'driver'    => $driver,
            'host'      => $overrideConfig['host'] ?? $_ENV['DB_HOST'] ?? '127.0.0.1',
            'port'      => (int) ($overrideConfig['port'] ?? $_ENV['DB_PORT'] ?? $defaultPort),
            'database'  => $overrideConfig['database'] ?? $_ENV['DB_DATABASE'] ?? 'reservation_salles',
            'username'  => $overrideConfig['username'] ?? $_ENV['DB_USERNAME'] ?? 'postgres',
            'password'  => $overrideConfig['password'] ?? $_ENV['DB_PASSWORD'] ?? '',
            'prefix'    => '',
        ];
    }

    if ($connection['driver'] === 'pgsql') {
        $connection['charset'] = 'utf8';
        $connection['schema'] = 'public';
        $connection['sslmode'] = 'prefer';
    } else {
        $connection['charset'] = 'utf8mb4';
        $connection['collation'] = 'utf8mb4_unicode_ci';
        $connection['options'] = [
            \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4',
        ];
    }

    $capsule = new Capsule();
    $capsule->addConnection($connection);
    
    $capsule->setAsGlobal();
    $capsule->bootEloquent();

    return $capsule;
};
