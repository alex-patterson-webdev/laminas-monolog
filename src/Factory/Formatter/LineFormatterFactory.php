<?php

declare(strict_types=1);

namespace Arp\LaminasMonolog\Factory\Formatter;

use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Monolog\Formatter\LineFormatter;
use Psr\Container\ContainerInterface;

/**
 * Create a new LineFormatter instance based on configuration options
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\LaminasMonolog\Factory\Formatter
 */
final class LineFormatterFactory extends AbstractNormalizerFormatterFactory
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array<mixed>|null  $options
     *
     * @return LineFormatter
     *
     * @throws ServiceNotCreatedException
     */
    public function __invoke(ContainerInterface $container, string $requestedName, array $options = null): LineFormatter
    {
        $options = $options ?? $this->getServiceOptions($container, $requestedName);

        $formatter = new LineFormatter(
            $options['format'] ?? null,
            null,
            isset($options['allow_line_breaks'])
                ? (bool)$options['allow_line_breaks']
                : false,
            isset($options['ignore_empty_context_and_extra'])
                ? (bool)$options['ignore_empty_context_and_extra']
                : false,
        );

        $this->configureNormalizerFormatter($formatter, $options);

        return $formatter;
    }
}
