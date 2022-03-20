<?php

declare(strict_types=1);

namespace Arp\LaminasMonolog\Factory\Formatter;

use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Monolog\Formatter\LineFormatter;
use Psr\Container\ContainerExceptionInterface;
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
     * @throws ContainerExceptionInterface
     */
    public function __invoke(ContainerInterface $container, string $requestedName, array $options = null): LineFormatter
    {
        $options = $options ?? $this->getServiceOptions($container, $requestedName);

        $formatter = new LineFormatter(
            $options['format'] ?? null,
            $options['date_format'] ?? null,
            isset($options['allow_inline_line_breaks']) && $options['allow_inline_line_breaks'],
            isset($options['ignore_empty_context_and_extra']) && $options['ignore_empty_context_and_extra'],
            isset($options['include_stack_traces']) && $options['include_stack_traces']
        );

        $this->configureNormalizerFormatter($formatter, $options);

        return $formatter;
    }
}
