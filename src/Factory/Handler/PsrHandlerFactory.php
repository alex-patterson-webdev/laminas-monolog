<?php

declare(strict_types=1);

namespace Arp\LaminasMonolog\Factory\Handler;

use Arp\LaminasFactory\AbstractFactory;
use Arp\LaminasMonolog\Factory\FactoryFormatterProviderTrait;
use Arp\LaminasMonolog\Factory\FactoryLoggerProviderTrait;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Monolog\Handler\PsrHandler;
use Monolog\Logger;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LogLevel;

/**
 * @phpstan-import-type Level from \Monolog\Logger
 * @phpstan-import-type LevelName from \Monolog\Logger
 */
final class PsrHandlerFactory extends AbstractFactory
{
    use FactoryLoggerProviderTrait;
    use FactoryFormatterProviderTrait;

    /**
     * @throws ServiceNotCreatedException
     * @throws ContainerExceptionInterface
     */
    public function __invoke(ContainerInterface $container, string $requestedName, array $options = null): PsrHandler
    {
        $options = $options ?? $this->getServiceOptions($container, $requestedName);

        if (empty($options['logger'])) {
            throw new ServiceNotCreatedException(
                sprintf('The required \'logger\' configuration option is missing for service \'%s\'', $requestedName)
            );
        }

        /** @var Level|LevelName|LogLevel::* $level */
        $level = isset($options['level']) ? (int)$options['level'] : Logger::DEBUG;

        $handler = new PsrHandler(
            $this->getLogger($container, $options['logger'], $requestedName),
            $level,
            !isset($options['bubble']) || $options['bubble']
        );

        if (!empty($options['formatter'])) {
            $handler->setFormatter(
                $this->getFormatter($container, $options['formatter'], $requestedName)
            );
        }

        return $handler;
    }
}
