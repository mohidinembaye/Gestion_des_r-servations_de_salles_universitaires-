<?php

declare(strict_types=1);

namespace App\Container;

use DI\Container;
use DI\ContainerBuilder;

final class ContainerFactory
{
    public function create(): Container
    {
        $builder = new ContainerBuilder();
        $builder->addDefinitions(__DIR__ . '/container.php');

        return $builder->build();
    }
}
