<?php

declare(strict_types=1);

namespace Arp\LaminasMonolog\Factory\Processor;

use Arp\LaminasFactory\AbstractFactory;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Monolog\Processor\PsrLogMessageProcessor;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

final class PsrLogMessageProcessorFactory extends AbstractFactory
{
    /**
     * @throws ServiceNotCreatedException
     * @throws ContainerExceptionInterface
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
