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
use Arp\LaminasMonolog\Factory\Processor\MemoryPeakUsageProcessorFactory;
use Arp\LaminasMonolog\Factory\Processor\MemoryUsageProcessorFactory;
use Arp\LaminasMonolog\Factory\Processor\PsrLogMessageProcessorFactory;
use Arp\LaminasMonolog\Factory\Processor\TagProcessorFactory;
use Arp\LaminasMonolog\Factory\Processor\UidProcessorFactory;
use Arp\LaminasMonolog\Factory\Processor\WebProcessorFactory;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Monolog\Formatter\HtmlFormatter;
use Monolog\Formatter\JsonFormatter;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\NoopHandler;
use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Processor\GitProcessor;
use Monolog\Processor\HostnameProcessor;
use Monolog\Processor\MemoryPeakUsageProcessor;
use Monolog\Processor\MemoryUsageProcessor;
use Monolog\Processor\ProcessIdProcessor;
use Monolog\Processor\PsrLogMessageProcessor;
use Monolog\Processor\TagProcessor;
use Monolog\Processor\UidProcessor;
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
            GitProcessor::class => GitProcessorFactory::class,
            HostnameProcessor::class => InvokableFactory::class,
            MemoryPeakUsageProcessor::class => MemoryPeakUsageProcessorFactory::class,
            MemoryUsageProcessor::class => MemoryUsageProcessorFactory::class,
            PsrLogMessageProcessor::class => PsrLogMessageProcessorFactory::class,
            WebProcessor::class => WebProcessorFactory::class,
            ProcessIdProcessor::class => InvokableFactory::class,
            TagProcessor::class => TagProcessorFactory::class,
            UidProcessor::class => UidProcessorFactory::class,

            // Formatter
            LineFormatter::class => LineFormatterFactory::class,
            JsonFormatter::class => JsonFormatterFactory::class,
            HtmlFormatter::class => HtmlFormatterFactory::class,
        ],
    ],
];
