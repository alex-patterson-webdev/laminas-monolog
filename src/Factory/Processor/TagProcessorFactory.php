<?php

declare(strict_types=1);

namespace Arp\LaminasMonolog\Factory\Processor;

use Arp\LaminasFactory\AbstractFactory;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Monolog\Processor\TagProcessor;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

final class TagProcessorFactory extends AbstractFactory
{
    /**
     * @throws ServiceNotCreatedException
     * @throws ContainerExceptionInterface
     */
    public function __invoke(ContainerInterface $container, string $requestedName, array $options = null): TagProcessor
    {
        $options = $options ?? $this->getServiceOptions($container, $requestedName);

        if (!isset($options['tags']) || !is_array($options['tags'])) {
            throw new ServiceNotCreatedException(
                sprintf('The required \'tags\' configuration option is missing for service \'%s\'', $requestedName)
            );
        }

        return new TagProcessor($options['tags']);
    }
}
