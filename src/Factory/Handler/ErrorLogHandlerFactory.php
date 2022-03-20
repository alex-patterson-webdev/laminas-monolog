<?php

declare(strict_types=1);

namespace Arp\LaminasMonolog\Factory\Handler;

use Arp\LaminasFactory\AbstractFactory;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Logger;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LogLevel;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\LaminasMonolog\Factory\Handler
 *
 * @phpstan-import-type Level from \Monolog\Logger
 * @phpstan-import-type LevelName from \Monolog\Logger
 */
final class ErrorLogHandlerFactory extends AbstractFactory
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array<mixed>|null  $options
     *
     * @return ErrorLogHandler
     *
     * @throws ServiceNotCreatedException
     * @throws ContainerExceptionInterface
     */
    public function __invoke(
        ContainerInterface $container,
        string $requestedName,
        array $options = null
    ): ErrorLogHandler {
        $options = $options ?? $this->getServiceOptions($container, $requestedName);

        /** @var Level|LevelName|LogLevel::* $level */
        $level = isset($options['level']) ? (int)$options['level'] : Logger::DEBUG;

        return new ErrorLogHandler(
            isset($options['message_type']) ? (int)$options['message_type'] : ErrorLogHandler::OPERATING_SYSTEM,
            $level,
            !isset($options['bubble']) || $options['bubble'],
            isset($options['expand_new_lines']) && $options['expand_new_lines']
        );
    }
}
