<?php

declare(strict_types=1);

namespace Arp\LaminasMonolog\Factory\Handler;

use Arp\LaminasFactory\AbstractFactory;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Monolog\Formatter\FormatterInterface;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

final class StreamHandlerFactory extends AbstractFactory
{
    /**
     * @throws ServiceNotCreatedException
     * @throws ContainerExceptionInterface
     */
    public function __invoke(ContainerInterface $container, string $requestedName, array $options = null): StreamHandler
    {
        $options = $options ?? $this->getServiceOptions($container, $requestedName);

        $stream = $options['stream'] ?? null;
        if (empty($stream)) {
            throw new ServiceNotCreatedException(
                sprintf('The required \'stream\' configuration option is missing for service \'%s\'', $requestedName)
            );
        }

        $className = $options['class_name'] ?? StreamHandler::class;
        if (!is_a($className, StreamHandler::class, true)) {
            throw new ServiceNotCreatedException(
                sprintf(
                    'The stream handler provided via configuration option \'class_name\' is invalid: '
                    . 'The stream handler class must extend from \'%s\'; \'%s\' provided for service \'%s\'',
                    StreamHandler::class,
                    $className,
                    $requestedName
                )
            );
        }

        $streamHandler = new $className(
            $stream,
            $options['level'] ?? Logger::DEBUG,
            $options['bubble'] ?? true,
            $options['file_permission'] ?? null,
            $options['use_locking'] ?? true
        );

        $formatter = $this->getFormatter(
            $container,
            $options['formatter'] ?? LineFormatter::class,
            $requestedName
        );

        $streamHandler->setFormatter($formatter);

        return $streamHandler;
    }

    /**
     * @throws ServiceNotCreatedException
     * @throws ContainerExceptionInterface
     */
    private function getFormatter(
        ContainerInterface $container,
        string|FormatterInterface $formatter,
        string $serviceName
    ): FormatterInterface {
        if (is_string($formatter)) {
            $formatter = $this->getService($container, $formatter, $serviceName);
        }

        if (!$formatter instanceof FormatterInterface) {
            throw new ServiceNotCreatedException(
                sprintf(
                    'The Monolog formatter must be an object of type \'%s\'; \'%s\' provided for service \'%s\'',
                    FormatterInterface::class,
                    is_object($formatter) ? get_class($formatter) : gettype($formatter),
                    $serviceName,
                )
            );
        }

        return $formatter;
    }
}
