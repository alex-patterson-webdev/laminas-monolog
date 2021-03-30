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
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\LaminasMonolog\Factory
 */
final class LoggerFactory extends AbstractFactory
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array<mixed>|null  $options
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
     * @param ContainerInterface&ServiceLocatorInterface $container
     * @param iterable<HandlerInterface|array<mixed>>    $handlerConfigs
     * @param string                                     $serviceName
     *
     * @return iterable<HandlerInterface>
     *
     * @throws ServiceNotCreatedException
     */
    private function getHandlers(
        ServiceLocatorInterface $container,
        iterable $handlerConfigs,
        string $serviceName
    ): iterable {
        $handlers = [];
        foreach ($handlerConfigs as $handlerName => $handler) {
            if (is_string($handler)) {
                $handlerName = $handler;
                $handler = [];
            }

            if (is_array($handler)) {
                $handler = empty($handler)
                    ? $this->getService($container, $handlerName, $serviceName)
                    : $this->buildService($container, $handlerName, $handler, $serviceName);
            }

            if (!$handler instanceof HandlerInterface) {
                throw new ServiceNotCreatedException(
                    sprintf(
                        'The Monolog handler \'%s\' must be an object of type \'%s\'; '
                        . '\'%s\' provided for service \'%s\'',
                        $handlerName,
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
     * @param ContainerInterface&ServiceLocatorInterface $container
     * @param iterable<ProcessorInterface>|array<mixed>  $processorConfigs
     * @param string                                     $serviceName
     *
     * @return iterable<ProcessorInterface>
     * @throws ServiceNotCreatedException
     */
    private function getProcessors(
        ContainerInterface $container,
        iterable $processorConfigs,
        string $serviceName
    ): iterable {
        $processors = [];
        foreach ($processorConfigs as $processorName => $processor) {
            if (is_string($processor)) {
                $processorName = $processor;
                $processor = [];
            }

            if (is_array($processor)) {
                $processor = empty($processor)
                    ? $this->getService($container, $processorName, $serviceName)
                    : $this->buildService($container, $processorName, $processor, $serviceName);
            }

            if (!$processor instanceof HandlerInterface) {
                throw new ServiceNotCreatedException(
                    sprintf(
                        'The Monolog processor \'%s\' must be an object of type \'%s\'; '
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
