<?php

declare(strict_types=1);

namespace Arp\LaminasMonolog\Factory\Processor;

use Arp\LaminasFactory\AbstractFactory;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Monolog\Logger;
use Monolog\Processor\GitProcessor;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

final class GitProcessorFactory extends AbstractFactory
{
    /**
     * @throws ServiceNotCreatedException
     * @throws ContainerExceptionInterface
     */
    public function __invoke(ContainerInterface $container, string $requestedName, array $options = null): GitProcessor
    {
        $options = $options ?? $this->getServiceOptions($container, $requestedName);

        $level = $options['level'] ?? Logger::DEBUG;

        return new GitProcessor($level);
    }
}
