<?php

declare(strict_types=1);

namespace ArpTest\LaminasMonolog\Factory\Formatter;

use Arp\LaminasFactory\FactoryInterface;
use Arp\LaminasMonolog\Factory\Formatter\LineFormatterFactory;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Monolog\Formatter\LineFormatter;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

/**
 * @covers \Arp\LaminasMonolog\Factory\Formatter\LineFormatterFactory
 * @covers \Arp\LaminasMonolog\Factory\Formatter\AbstractNormalizerFormatterFactory
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package ArpTest\LaminasMonolog\Factory\Formatter
 */
final class LineFormatterFactoryTest extends TestCase
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
        $factory = new LineFormatterFactory();

        $this->assertInstanceOf(FactoryInterface::class, $factory);
    }

    /**
     * @dataProvider getInvokeWillReturnConfiguredLineFormatterInstanceData
     *
     * @param array<mixed> $options
     *
     * @throws ServiceNotCreatedException
     * @throws ContainerExceptionInterface
     */
    public function testInvokeWillReturnConfiguredLineFormatterInstance(array $options): void
    {
        $factory = new LineFormatterFactory();

        $formatter = $factory($this->container, LineFormatter::class, $options);

        if (isset($options['date_format'])) {
            $this->assertSame($options['date_format'], $formatter->getDateFormat());
        }

        if (isset($options['max_normalize_depth'])) {
            $this->assertSame($options['max_normalize_depth'], $formatter->getMaxNormalizeDepth());
        }

        $this->assertInstanceOf(LineFormatter::class, $formatter);
    }

    /**
     * @return array<mixed>
     */
    public function getInvokeWillReturnConfiguredLineFormatterInstanceData(): array
    {
        return [
            [
                [],
            ],
            [
                [
                    'date_format' => 'Y-m-d H:i:s',
                ],
            ],
            [
                [
                    'max_normalize_depth' => 100,
                ],
            ],
            [
                [
                    'max_normalize_item_count' => 2000,
                    'json_pretty_print' => true,
                    'json_encode_options' => [
                        \JSON_THROW_ON_ERROR,
                    ]
                ]
            ],
        ];
    }
}
