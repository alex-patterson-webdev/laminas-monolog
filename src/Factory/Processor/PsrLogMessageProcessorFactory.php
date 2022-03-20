<?php

declare(strict_types=1);

namespace Arp\LaminasMonolog\Factory\Processor;

use Arp\LaminasFactory\AbstractFactory;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Monolog\Processor\PsrLogMessageProcessor;
use Psr\Container\ContainerInterface;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\LaminasMonolog\Factory\Processor
 */
final class PsrLogMessageProcessorFactory extends AbstractFactory
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array<string, mixed>|null $options
     *
     * @return PsrLogMessageProcessor
     *
     * @throws ServiceNotCreatedException
     */
    public function __invoke(
        ContainerInterface $container,
        string $requestedName,
        array $options = null
    ): PsrLogMessageProcessor {
        $options = $options ?? $this->getServiceOptions($container, $requestedName);

        return new PsrLogMessageProcessor(
            (isset($options['date_format']) && is_string($options['date_format']))
                ? $options['date_format']
                : null,
            isset($options['remove_used_context_fields']) && $options['remove_used_context_fields']
        );
    }
}
