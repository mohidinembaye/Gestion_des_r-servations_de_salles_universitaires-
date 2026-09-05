<?php

declare(strict_types=1);

use App\Repository\EloquentReservationRepository;
use App\Repository\EloquentSalleRepository;
use App\Repository\ReservationRepositoryInterface;
use App\Repository\SalleRepositoryInterface;
use App\Service\DisponibiliteStrategyInterface;
use App\Service\ReservationDisponibiliteStrategy;
use App\Validation\ReservationValidator;
use App\Validation\SalleValidator;
use Dotenv\Dotenv;
use FastRoute\Dispatcher;
use Illuminate\Database\Capsule\Manager;
use function DI\autowire;
use function DI\factory;

$root = dirname(__DIR__, 2);
Dotenv::createImmutable($root)->safeLoad();

return [
    SalleRepositoryInterface::class => autowire(EloquentSalleRepository::class),
    ReservationRepositoryInterface::class => autowire(EloquentReservationRepository::class),
    DisponibiliteStrategyInterface::class => autowire(ReservationDisponibiliteStrategy::class),
    SalleValidator::class => autowire(),
    ReservationValidator::class => autowire(),
    Manager::class => factory(static function (): Manager {
        $configureDatabase = require dirname(__DIR__, 2) . '/config/database.php';

        return $configureDatabase([
            'driver' => $_ENV['DB_DRIVER'] ?? 'mysql',
            'host' => $_ENV['DB_HOST'] ?? '127.0.0.1',
            'port' => (int) ($_ENV['DB_PORT'] ?? 3306),
            'database' => $_ENV['DB_DATABASE'] ?? 'reservation_salles',
            'username' => $_ENV['DB_USERNAME'] ?? 'root',
            'password' => $_ENV['DB_PASSWORD'] ?? '',
        ]);
    }),
    Dispatcher::class => factory(static function (): Dispatcher {
        return FastRoute\simpleDispatcher(require dirname(__DIR__, 2) . '/routes/routes.php');
    }),
];
