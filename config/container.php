<?php

declare(strict_types=1);

use App\Config\EnvironnementConfig;
use App\Http\HtmlResponseStrategy;
use App\Http\JsonResponseStrategy;
use App\Http\ResponseFormatContext;
use App\Http\ResponseFormatStrategy;
use App\Repository\EloquentReservationRepository;
use App\Repository\EloquentSalleRepository;
use App\Repository\PdoResponsableRepository;
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
use function DI\get;
use Psr\Container\ContainerInterface;

$root = dirname(__DIR__, 1);
Dotenv::createImmutable($root)->safeLoad();

return [
    EnvironnementConfig::class => factory(static fn (): EnvironnementConfig => EnvironnementConfig::depuisEnv()),
    SalleRepositoryInterface::class => autowire(EloquentSalleRepository::class),
    ReservationRepositoryInterface::class => autowire(EloquentReservationRepository::class),
    DisponibiliteStrategyInterface::class => autowire(ReservationDisponibiliteStrategy::class),
    SalleValidator::class => autowire(),
    ReservationValidator::class => autowire(),
    JsonResponseStrategy::class => autowire(),
    HtmlResponseStrategy::class => autowire()->constructorParameter('templatesPath', dirname(__DIR__, 1) . '/templates'),
    ResponseFormatContext::class => factory(static function (ContainerInterface $container): ResponseFormatContext {
        $context = new ResponseFormatContext();
        $context->addStrategy($container->get(JsonResponseStrategy::class));
        $context->addStrategy($container->get(HtmlResponseStrategy::class));
        return $context;
    }),
    \PDO::class => factory(static function (): \PDO {
        $dsn = sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',
            $_ENV['DB_HOST'] ?? '127.0.0.1',
            (int) ($_ENV['DB_PORT'] ?? 3306),
            $_ENV['DB_DATABASE'] ?? 'reservation_salles'
        );
        $pdo = new \PDO($dsn, $_ENV['DB_USERNAME'] ?? '', $_ENV['DB_PASSWORD'] ?? '', [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES => false,
        ]);
        return $pdo;
    }),
    PdoResponsableRepository::class => autowire(),
    Manager::class => factory(static function (): Manager {
        $configureDatabase = require dirname(__DIR__, 1) . '/config/database.php';

        return $configureDatabase();
    }),
    Dispatcher::class => factory(static function (): Dispatcher {
        return FastRoute\simpleDispatcher(require dirname(__DIR__, 1) . '/routes/routes.php');
    }),
];