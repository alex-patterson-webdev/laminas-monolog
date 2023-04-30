<?php

declare(strict_types=1);

namespace Arp\LaminasMonolog\Factory\Processor;

use Arp\LaminasFactory\AbstractFactory;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Monolog\Processor\MemoryUsageProcessor;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

final class MemoryUsageProcessorFactory extends AbstractFactory
{
    /**
     * @throws ServiceNotCreatedException
     * @throws ContainerExceptionInterface
     */
    public function __invoke(
        ContainerInterface $container,
        string $requestedName,
        array $options = null
    ): MemoryUsageProcessor {
        $options = $options ?? $this->getServiceOptions($container, $requestedName);

        return new MemoryUsageProcessor(
            !isset($options['real_usage']) || $options['real_usage'],
            !isset($options['use_formatting']) || $options['use_formatting']
        );
    }
}
