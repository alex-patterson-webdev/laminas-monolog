<?php

declare(strict_types=1);

namespace Arp\LaminasMonolog\Factory\Processor;

use Arp\LaminasFactory\AbstractFactory;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Monolog\Processor\WebProcessor;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

final class WebProcessorFactory extends AbstractFactory
{
    /**
     * @var array<string, string>
     */
    private array $defaultExtraFields = [
        'url'         => 'REQUEST_URI',
        'ip'          => 'REMOTE_ADDR',
        'http_method' => 'REQUEST_METHOD',
        'server'      => 'SERVER_NAME',
        'referrer'    => 'HTTP_REFERER',
    ];

    /**
     * @throws ServiceNotCreatedException
     * @throws ContainerExceptionInterface
     */
    public function __invoke(ContainerInterface $container, string $requestedName, array $options = null): WebProcessor
    {
        $options = $options ?? $this->getServiceOptions($container, $requestedName);

        try {
            return new WebProcessor(
                $options['server_data'] ?? null,
                $options['extra_fields'] ?? $this->defaultExtraFields
            );
        } catch (\UnexpectedValueException $e) {
            throw new ServiceNotCreatedException(
                sprintf(
                    'Failed to create web processor \'%s\': %s',
                    $requestedName,
                    $e->getMessage()
                ),
                $e->getCode(),
                $e
            );
        }
    }
}
