<?php

declare(strict_types=1);

namespace Arp\LaminasMonolog\Factory\Processor;

use Arp\LaminasFactory\AbstractFactory;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Monolog\Logger;
use Monolog\Processor\GitProcessor;
use Psr\Container\ContainerInterface;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\LaminasMonolog\Factory\Processor
 */
final class GitProcessorFactory extends AbstractFactory
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array<mixed>|null  $options
     *
     * @return GitProcessor
     *
     * @throws ServiceNotCreatedException
     */
    public function __invoke(ContainerInterface $container, string $requestedName, array $options = null): GitProcessor
    {
        $options = $options ?? $this->getServiceOptions($container, $requestedName);

        $level = $options['level'] ?? Logger::DEBUG;

        return new GitProcessor($level);
    }
}
