<?php

declare(strict_types=1);

namespace Arp\LaminasMonolog\Factory\Processor;

use Arp\LaminasFactory\AbstractFactory;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Monolog\Processor\TagProcessor;
use Psr\Container\ContainerInterface;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\LaminasMonolog\Factory\Processor
 */
final class TagProcessorFactory extends AbstractFactory
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array<mixed>|null  $options
     *
     * @return TagProcessor
     *
     * @throws ServiceNotCreatedException
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
