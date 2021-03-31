<?php

declare(strict_types=1);

namespace Arp\LaminasMonolog\Factory\Handler;

use Arp\LaminasFactory\AbstractFactory;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Logger;
use Psr\Container\ContainerInterface;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\LaminasMonolog\Factory\Handler
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
     */
    public function __invoke(
        ContainerInterface $container,
        string $requestedName,
        array $options = null
    ): ErrorLogHandler {
        $options = $options ?? $this->getServiceOptions($container, $requestedName);

        return new ErrorLogHandler(
            isset($options['message_type']) ? (int)$options['message_type'] : ErrorLogHandler::OPERATING_SYSTEM,
            isset($options['level']) ? (int)$options['level'] : Logger::DEBUG,
            isset($options['bubble']) ? (bool)$options['bubble'] : true,
            isset($options['expand_new_lines']) ? (bool)$options['expand_new_lines'] : false
        );
    }
}
