<?php

declare(strict_types=1);

namespace Arp\LaminasMonolog\Factory\Handler;

use Arp\LaminasFactory\AbstractFactory;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Monolog\Handler\SyslogHandler;
use Monolog\Logger;
use Psr\Container\ContainerInterface;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\LaminasMonolog\Factory\Handler
 */
final class SyslogHandlerFactory extends AbstractFactory
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array<mixed>|null  $options
     *
     * @return SyslogHandler
     *
     * @throws ServiceNotCreatedException
     */
    public function __invoke(
        ContainerInterface $container,
        string $requestedName,
        array $options = null
    ): SyslogHandler {
        $options = $options ?? $this->getServiceOptions($container, $requestedName);

        $ident = $options['ident'] ?? '';
        if (empty($ident)) {
            throw new ServiceNotCreatedException(
                sprintf('The required \'ident\' configuration option is missing for service \'%s\'', $requestedName)
            );
        }

        return new SyslogHandler(
            $ident,
            $options['facility'] ?? LOG_USER,
            $options['level'] ?? Logger::DEBUG,
            isset($options['bubble']) ? (bool)$options['bubble'] : true,
            $options['logopts'] ?? LOG_PID
        );
    }
}
