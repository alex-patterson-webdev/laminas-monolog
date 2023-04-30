<?php

declare(strict_types=1);

namespace Arp\LaminasMonolog\Factory\Formatter;

use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Monolog\Formatter\HtmlFormatter;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

final class HtmlFormatterFactory extends AbstractNormalizerFormatterFactory
{
    /**
     * @throws ServiceNotCreatedException
     * @throws ContainerExceptionInterface
     */
    public function __invoke(ContainerInterface $container, string $requestedName, array $options = null): HtmlFormatter
    {
        $options = $options ?? $this->getServiceOptions($container, $requestedName);

        $formatter = new HtmlFormatter(
            $options['date_format'] ?? null
        );

        $this->configureNormalizerFormatter($formatter, $options);
        return $formatter;
    }
}
