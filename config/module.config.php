<?php

declare(strict_types=1);

namespace Arp\LaminasMonolog;

use Arp\LaminasMonolog\Factory\Formatter\HtmlFormatterFactory;
use Arp\LaminasMonolog\Factory\Formatter\JsonFormatterFactory;
use Arp\LaminasMonolog\Factory\Formatter\LineFormatterFactory;
use Arp\LaminasMonolog\Factory\Handler\NoopHandlerFactory;
use Arp\LaminasMonolog\Factory\Handler\NullHandlerFactory;
use Arp\LaminasMonolog\Factory\Handler\StreamHandlerFactory;
use Arp\LaminasMonolog\Factory\Processor\GitProcessorFactory;
use Arp\LaminasMonolog\Factory\Processor\MemoryUsageProcessorFactory;
use Arp\LaminasMonolog\Factory\Processor\PsrLogMessageProcessorFactory;
use Arp\LaminasMonolog\Factory\Processor\WebProcessorFactory;
use Composer\Json\JsonFormatter;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Monolog\Formatter\HtmlFormatter;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\NoopHandler;
use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Processor\GitProcessor;
use Monolog\Processor\HostnameProcessor;
use Monolog\Processor\MemoryUsageProcessor;
use Monolog\Processor\PsrLogMessageProcessor;
use Monolog\Processor\WebProcessor;

return [
    'arp' => [
        'loggers' => [],
        'services' => [],
    ],
    'service_manager' => [
        'factories' => [
            // Handlers
            StreamHandler::class => StreamHandlerFactory::class,
            NullHandler::class => NullHandlerFactory::class,
            NoopHandler::class => NoopHandlerFactory::class,

            // Processors
            MemoryUsageProcessor::class => MemoryUsageProcessorFactory::class,
            PsrLogMessageProcessor::class => PsrLogMessageProcessorFactory::class,
            GitProcessor::class => GitProcessorFactory::class,
            HostnameProcessor::class => InvokableFactory::class,
            WebProcessor::class => WebProcessorFactory::class,

            // Formatter
            LineFormatter::class => LineFormatterFactory::class,
            JsonFormatter::class => JsonFormatterFactory::class,
            HtmlFormatter::class => HtmlFormatterFactory::class,
        ],
    ],
];
