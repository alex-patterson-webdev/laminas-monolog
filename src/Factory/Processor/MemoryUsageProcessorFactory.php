<?php

declare(strict_types=1);

namespace Arp\LaminasMonolog\Factory\Processor;

use Arp\LaminasFactory\AbstractFactory;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Monolog\Processor\MemoryUsageProcessor;
use Psr\Container\ContainerInterface;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\LaminasMonolog\Factory\Processor
 */
final class MemoryUsageProcessorFactory extends AbstractFactory
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array<mixed>|null  $options
     *
     * @return MemoryUsageProcessor
     *
     * @throws ServiceNotCreatedException
     */
    public function __invoke(
        ContainerInterface $container,
        string $requestedName,
        array $options = null
    ): MemoryUsageProcessor {
        $options = $options ?? $this->getServiceOptions($container, $requestedName);

        $realUsage = isset($options['real_usage'])
            ? (bool)$options['real_usage']
            : true;

        $useFormatting = isset($options['use_formatting'])
            ? (bool)$options['use_formatting']
            : true;

        return new MemoryUsageProcessor($realUsage, $useFormatting);
    }
}
