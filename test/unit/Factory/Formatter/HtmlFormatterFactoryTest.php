<?php

declare(strict_types=1);

namespace ArpTest\LaminasMonolog\Factory\Formatter;

use Arp\LaminasFactory\FactoryInterface;
use Arp\LaminasMonolog\Factory\Formatter\HtmlFormatterFactory;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Monolog\Formatter\HtmlFormatter;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

/**
 * @covers \Arp\LaminasMonolog\Factory\Formatter\HtmlFormatterFactory
 * @covers \Arp\LaminasMonolog\Factory\Formatter\AbstractNormalizerFormatterFactory
 */
final class HtmlFormatterFactoryTest extends TestCase
{
    /**
     * @var ContainerInterface&MockObject
     */
    private ContainerInterface $container;

    public function setUp(): void
    {
        $this->container = $this->createMock(ContainerInterface::class);
    }

    public function testImplementsFactoryInterface(): void
    {
        $factory = new HtmlFormatterFactory();

        $this->assertInstanceOf(FactoryInterface::class, $factory);
    }

    /**
     * @dataProvider getInvokeWillReturnConfiguredHtmlFormatterInstanceData
     *
     * @param array<mixed> $options
     *
     * @throws ServiceNotCreatedException
     * @throws ContainerExceptionInterface
     */
    public function testInvokeWillReturnConfiguredHtmlFormatterInstance(array $options): void
    {
        $factory = new HtmlFormatterFactory();

        $formatter = $factory($this->container, HtmlFormatter::class, $options);

        if (isset($options['date_format'])) {
            $this->assertSame($options['date_format'], $formatter->getDateFormat());
        }

        $this->assertInstanceOf(HtmlFormatter::class, $formatter);
    }

    /**
     * @return array<mixed>
     */
    public function getInvokeWillReturnConfiguredHtmlFormatterInstanceData(): array
    {
        return [
            [
                [],
            ],
            [
                [
                    'date_format' => 'Y-m-d H:i:s',
                ]
            ]
        ];
    }
}
