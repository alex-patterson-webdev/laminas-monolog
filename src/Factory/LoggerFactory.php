<?php

declare(strict_types=1);

namespace Arp\LaminasMonolog\Factory;

use Arp\LaminasFactory\AbstractFactory;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Monolog\Handler\HandlerInterface;
use Monolog\Logger;
use Monolog\Processor\ProcessorInterface;
use Psr\Container\ContainerInterface;

/**
 * Factory class used to create a new instance of a Monolog\Logger based on configuration options
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\LaminasMonolog\Factory
 */
class LoggerFactory extends AbstractFactory
{
    /**
     * @param ServiceLocatorInterface&ContainerInterface $container
     * @param string                                     $requestedName
     * @param array<mixed>|null                          $options
     *
     * @return Logger
     *
     * @throws ServiceNotCreatedException
     */
    public function __invoke(ContainerInterface $container, string $requestedName, array $options = null): Logger
    {
        $options = $options ?? $this->getServiceOptions($container, $requestedName, 'loggers');

        $name = $options['name'] ?? $requestedName;

        return new Logger(
            $name,
            $this->getHandlers($container, $options['handlers'] ?? [], $requestedName),
            $this->getProcessors($container, $options['processors'] ?? [], $requestedName),
            $options['timezone'] ?? null
        );
    }

    /**
     * @param ServiceLocatorInterface&ContainerInterface $container
     * @param array<string|array|HandlerInterface>       $handlerConfigs
     * @param string                                     $serviceName
     *
     * @return array<HandlerInterface>
     *
     * @throws ServiceNotCreatedException
     */
    private function getHandlers(
        ServiceLocatorInterface $container,
        array $handlerConfigs,
        string $serviceName
    ): array {
        $handlers = [];
        foreach ($handlerConfigs as $handlerName => $handler) {
            if (is_string($handler)) {
                $handlerName = $handler;
                $handler = $this->getService($container, $handlerName, $serviceName);
            }

            if (is_array($handler)) {
                $handler = $this->buildService($container, $handlerName, $handler, $serviceName);
            }

            if (!$handler instanceof HandlerInterface) {
                throw new ServiceNotCreatedException(
                    sprintf(
                        'The log handler \'%s\' must be an object of type \'%s\'; '
                        . '\'%s\' provided for service \'%s\'',
                        is_string($handlerName) ? $handlerName : gettype($handler),
                        HandlerInterface::class,
                        is_object($handler) ? get_class($handler) : gettype($handler),
                        $serviceName,
                    )
                );
            }

            $handlers[] = $handler;
        }

        return $handlers;
    }

    /**
     * @param ServiceLocatorInterface&ContainerInterface $container
     * @param array<string|array|ProcessorInterface>     $processorConfigs
     * @param string                                     $serviceName
     *
     * @return array<ProcessorInterface>
     *
     * @throws ServiceNotCreatedException
     */
    private function getProcessors(
        ServiceLocatorInterface $container,
        array $processorConfigs,
        string $serviceName
    ): array {
        $processors = [];
        foreach ($processorConfigs as $processorName => $processor) {
            if (is_string($processor)) {
                $processor = $this->getService($container, $processor, $serviceName);
            }

            if (is_array($processor)) {
                $processor = $this->buildService($container, $processorName, $processor, $serviceName);
            }

            if (!$processor instanceof ProcessorInterface) {
                throw new ServiceNotCreatedException(
                    sprintf(
                        'The log processor \'%s\' must be an object of type \'%s\'; '
                        . '\'%s\' provided for service \'%s\'',
                        $processorName,
                        ProcessorInterface::class,
                        is_object($processor) ? get_class($processor) : gettype($processor),
                        $serviceName,
                    )
                );
            }

            $processors[] = $processor;
        }

        return $processors;
    }
}
