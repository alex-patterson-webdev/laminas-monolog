<?php

declare(strict_types=1);

namespace Arp\LaminasMonolog\Factory\Processor;

use Arp\LaminasFactory\AbstractFactory;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Monolog\Processor\MemoryPeakUsageProcessor;
use Psr\Container\ContainerInterface;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\LaminasMonolog\Factory\Processor
 */
final class MemoryPeakUsageProcessorFactory extends AbstractFactory
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array<mixed>|null  $options
     *
     * @return MemoryPeakUsageProcessor
     *
     * @throws ServiceNotCreatedException
     */
    public function __invoke(
        ContainerInterface $container,
        string $requestedName,
        array $options = null
    ): MemoryPeakUsageProcessor {
        $options = $options ?? $this->getServiceOptions($container, $requestedName);

        return new MemoryPeakUsageProcessor(
            !isset($options['real_usage']) || $options['real_usage'],
            !isset($options['use_formatting']) || $options['use_formatting']
        );
    }
}
