<?php

declare(strict_types=1);

namespace Arp\LaminasMonolog;

use Arp\LaminasMonolog\Factory\Handler\NoopHandlerFactory;
use Arp\LaminasMonolog\Factory\Handler\NullHandlerFactory;
use Arp\LaminasMonolog\Factory\Handler\StreamHandlerFactory;
use Monolog\Handler\NoopHandler;
use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;

return [
    'arp' => [
        'loggers' => [],
        'services' => [],
    ],
    'service_manager' => [
        'factories' => [
            StreamHandler::class => StreamHandlerFactory::class,
            NullHandler::class => NullHandlerFactory::class,
            NoopHandler::class => NoopHandlerFactory::class,
        ],
    ],
];
