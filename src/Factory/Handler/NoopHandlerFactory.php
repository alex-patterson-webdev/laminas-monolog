<?php

declare(strict_types=1);

namespace Arp\LaminasMonolog\Factory\Handler;

use Arp\LaminasFactory\AbstractFactory;
use Monolog\Handler\NoopHandler;
use Psr\Container\ContainerInterface;

final class NoopHandlerFactory extends AbstractFactory
{
    public function __invoke(ContainerInterface $container, string $requestedName, array $options = null): NoopHandler
    {
        return new NoopHandler();
    }
}
