<?php

declare(strict_types=1);

namespace Arp\LaminasMonolog\Factory;

use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Monolog\Logger;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

trait FactoryLoggerProviderTrait
{
    protected string $loggerService = Logger::class;

    /**
     * @param LoggerInterface|string|array<mixed> $logger
     *
     * @throws ServiceNotCreatedException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getLogger(ContainerInterface $container, $logger, string $serviceName): LoggerInterface
    {
        if (is_string($logger)) {
            if (!$container->has($logger)) {
                throw new ServiceNotFoundException(
                    sprintf('The logger \'%s\' could not be found for service \'%s\'', $logger, $serviceName)
                );
            }

            $logger = $container->get($logger);
        }

        if (is_array($logger) && $container instanceof ServiceLocatorInterface) {
            $logger = $container->build($this->loggerService, $logger);
        }

        if (null === $logger) {
            $logger = new NullLogger();
        }

        if (!$logger instanceof LoggerInterface) {
            throw new ServiceNotCreatedException(
                sprintf(
                    'The resolved logger must be an object of type \'%s\'; \'%s\' provided for service \'%s\'',
                    LoggerInterface::class,
                    is_object($logger) ? get_class($logger) : gettype($logger),
                    $serviceName
                )
            );
        }

        return $logger;
    }

    public function setLoggerService(string $loggerService): void
    {
        $this->loggerService = $loggerService;
    }
}
