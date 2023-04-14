<?php

declare(strict_types=1);

namespace Arp\LaminasMonolog\Factory;

use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;
use Monolog\Formatter\FormatterInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

trait FactoryFormatterProviderTrait
{
    /**
     * @throws ServiceNotCreatedException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function getFormatter(
        ContainerInterface $container,
        string|FormatterInterface $formatter,
        string $serviceName
    ): FormatterInterface {
        if (is_string($formatter)) {
            if (!$container->has($formatter)) {
                throw new ServiceNotFoundException(
                    sprintf('The formatter \'%s\' could not be found for service \'%s\'', $formatter, $serviceName)
                );
            }

            $formatter = $container->get($formatter);
        }

        if (!$formatter instanceof FormatterInterface) {
            throw new ServiceNotCreatedException(
                sprintf(
                    'The resolved formatter must be an object of type \'%s\'; \'%s\' provided for service \'%s\'',
                    FormatterInterface::class,
                    is_object($formatter) ? get_class($formatter) : gettype($formatter),
                    $serviceName
                )
            );
        }

        return $formatter;
    }
}
