<?php

declare(strict_types=1);

use App\Config\EnvironnementConfig;
use App\Repository\EloquentReservationRepository;
use App\Repository\EloquentSalleRepository;
use App\Repository\JsonReservationRepository;
use App\Repository\JsonSalleRepository;
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
    EnvironnementConfig::class => factory(static fn (): EnvironnementConfig => EnvironnementConfig::depuisEnv()),
    Manager::class => factory(static function (): Manager {
        $configureDatabase = require dirname(__DIR__, 2) . '/config/database.php';

        return $configureDatabase();
    }),
    Dispatcher::class => factory(static function (): Dispatcher {
        return FastRoute\simpleDispatcher(require dirname(__DIR__, 2) . '/routes/routes.php');
    }),
    SalleRepositoryInterface::class => factory(static function (Manager $database): SalleRepositoryInterface {
        $format = $_ENV['FORMAT_SORTIE'] ?? 'html';

        if ($format === 'json') {
            return new JsonSalleRepository(dirname(__DIR__, 2) . '/storage/data/salles.json');
        }

        return new EloquentSalleRepository($database);
    }),
    ReservationRepositoryInterface::class => factory(static function (Manager $database, SalleRepositoryInterface $salles): ReservationRepositoryInterface {
        $format = $_ENV['FORMAT_SORTIE'] ?? 'html';

        if ($format === 'json') {
            return new JsonReservationRepository(dirname(__DIR__, 2) . '/storage/data/reservations.json', $salles);
        }

        return new EloquentReservationRepository($database);
    }),
    DisponibiliteStrategyInterface::class => autowire(ReservationDisponibiliteStrategy::class),
    SalleValidator::class => autowire(),
    ReservationValidator::class => autowire(),
];