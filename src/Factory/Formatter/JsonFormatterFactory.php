<?php

declare(strict_types=1);

namespace Arp\LaminasMonolog\Factory\Formatter;

use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Monolog\Formatter\JsonFormatter;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\LaminasMonolog\Factory\Formatter
 */
final class JsonFormatterFactory extends AbstractNormalizerFormatterFactory
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array<mixed>|null  $options
     *
     * @return JsonFormatter
     *
     * @throws ServiceNotCreatedException
     * @throws ContainerExceptionInterface
     */
    public function __invoke(ContainerInterface $container, string $requestedName, array $options = null): JsonFormatter
    {
        $options = $options ?? $this->getServiceOptions($container, $requestedName);

        $formatter = new JsonFormatter(
            $options['batch_mode'] ?? JsonFormatter::BATCH_MODE_JSON,
            $options['append_new_line'] ?? true,
            $options['ignore_empty_context_and_extra'] ?? false,
            $options['include_stack_traces'] ?? false,
        );

        $this->configureNormalizerFormatter($formatter, $options);

        return $formatter;
    }
}
