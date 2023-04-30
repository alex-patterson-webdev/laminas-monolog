<?php

declare(strict_types=1);

namespace Arp\LaminasMonolog\Factory\Handler;

use Arp\LaminasFactory\AbstractFactory;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Monolog\Handler\SyslogHandler;
use Monolog\Logger;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

final class SyslogHandlerFactory extends AbstractFactory
{
    /**
     * @throws ServiceNotCreatedException
     * @throws ContainerExceptionInterface
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
            !isset($options['bubble']) || (bool)$options['bubble'],
            $options['logopts'] ?? LOG_PID
        );
    }
}
