<?php

declare(strict_types=1);

namespace Arp\LaminasMonolog\Factory\Processor;

use Arp\LaminasFactory\AbstractFactory;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

final class UidProcessorFactory extends AbstractFactory
{
    /**
     * @throws ServiceNotCreatedException
     * @throws ContainerExceptionInterface
     */
    public function __invoke(ContainerInterface $container, string $requestedName, array $options = null): UidProcessor
    {
        $options = $options ?? $this->getServiceOptions($container, $requestedName);

        return new UidProcessor(
            isset($options['length']) ? (int)$options['length'] : 7
        );
    }
}
