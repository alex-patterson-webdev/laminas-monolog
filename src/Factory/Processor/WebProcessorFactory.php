<?php

declare(strict_types=1);

namespace Arp\LaminasMonolog\Factory\Processor;

use Arp\LaminasFactory\AbstractFactory;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Monolog\Processor\WebProcessor;
use Psr\Container\ContainerInterface;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\LaminasMonolog\Factory\Processor
 */
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
     * @param ContainerInterface        $container
     * @param string                    $requestedName
     * @param array<string, mixed>|null $options
     *
     * @return WebProcessor
     *
     * @throws ServiceNotCreatedException
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
