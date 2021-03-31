<?php

declare(strict_types=1);

namespace Arp\LaminasMonolog\Factory\Handler;

use Arp\LaminasFactory\AbstractFactory;
use Arp\LaminasMonolog\Factory\FactoryFormatterProviderTrait;
use Arp\LaminasMonolog\Factory\FactoryLoggerProviderTrait;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Monolog\Handler\PsrHandler;
use Monolog\Logger;
use Psr\Container\ContainerInterface;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\LaminasMonolog\Factory\Handler
 */
final class PsrHandlerFactory extends AbstractFactory
{
    use FactoryLoggerProviderTrait;
    use FactoryFormatterProviderTrait;

    /**
     * @param ContainerInterface&ServiceLocatorInterface $container
     * @param string                                     $requestedName
     * @param array<mixed>|null                          $options
     *
     * @return PsrHandler
     *
     * @throws ServiceNotCreatedException
     */
    public function __invoke(ContainerInterface $container, string $requestedName, array $options = null): PsrHandler
    {
        $options = $options ?? $this->getServiceOptions($container, $requestedName);

        if (empty($options['logger'])) {
            throw new ServiceNotCreatedException(
                sprintf('The required \'logger\' configuration option is missing for service \'%s\'', $requestedName)
            );
        }

        $handler = new PsrHandler(
            $this->getLogger($container, $options['logger'], $requestedName),
            isset($options['level']) ? (int)$options['level'] : Logger::DEBUG,
            isset($options['bubble']) ? (bool)$options['bubble'] : true
        );

        if (!empty($options['formatter'])) {
            $handler->setFormatter(
                $this->getFormatter($container, $options['formatter'], $requestedName)
            );
        }

        return $handler;
    }
}
