<?php

declare(strict_types=1);

namespace Arp\LaminasMonolog\Factory;

use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;
use Monolog\Formatter\FormatterInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Trait used to allow factories to resolve a Formatter from configuration options
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\LaminasMonolog\Factory
 */
trait FactoryFormatterProviderTrait
{
    /**
     * @param ContainerInterface        $container
     * @param string|FormatterInterface $formatter
     * @param string                    $serviceName
     *
     * @return FormatterInterface
     *
     * @throws ServiceNotCreatedException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function getFormatter(
        ContainerInterface $container,
        $formatter,
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
