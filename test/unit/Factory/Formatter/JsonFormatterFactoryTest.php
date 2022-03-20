<?php

declare(strict_types=1);

namespace ArpTest\LaminasMonolog\Factory\Formatter;

use Arp\LaminasFactory\FactoryInterface;
use Arp\LaminasMonolog\Factory\Formatter\JsonFormatterFactory;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Monolog\Formatter\JsonFormatter;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

/**
 * @covers \Arp\LaminasMonolog\Factory\Formatter\JsonFormatterFactory
 * @covers \Arp\LaminasMonolog\Factory\Formatter\AbstractNormalizerFormatterFactory
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package ArpTest\LaminasMonolog\Factory\Formatter
 */
final class JsonFormatterFactoryTest extends TestCase
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
        $factory = new JsonFormatterFactory();

        $this->assertInstanceOf(FactoryInterface::class, $factory);
    }

    /**
     * @dataProvider getInvokeReturnsConfiguredJsonFormatterInstanceData
     *
     * @param array<mixed> $options
     *
     * @throws ServiceNotCreatedException
     * @throws ContainerExceptionInterface
     */
    public function testInvokeReturnsConfiguredJsonFormatterInstance(array $options): void
    {
        $factory = new JsonFormatterFactory();

        $formatter = $factory($this->container, JsonFormatter::class, $options);

        $this->assertSame(
            $options['batch_mode'] ?? JsonFormatter::BATCH_MODE_JSON,
            $formatter->getBatchMode()
        );

        $this->assertSame(
            $options['append_new_line'] ?? true,
            $formatter->isAppendingNewlines()
        );

        if (isset($options['date_format'])) {
            $this->assertSame($options['date_format'], $formatter->getDateFormat());
        }

        if (isset($options['max_normalize_depth'])) {
            $this->assertSame($options['max_normalize_depth'], $formatter->getMaxNormalizeDepth());
        }

        $this->assertInstanceOf(JsonFormatter::class, $formatter);
    }

    /**
     * @return array<mixed>
     */
    public function getInvokeReturnsConfiguredJsonFormatterInstanceData(): array
    {
        return [
            [
                [],
            ],
            [
                [
                    'batch_mode' => JsonFormatter::BATCH_MODE_NEWLINES,
                ]
            ],
            [
                [
                    'append_new_line' => false,
                    'ignore_empty_context_and_extra' => true,
                    'include_stack_traces' => true,
                ]
            ],
            [
                [
                    'date_format' => 'Y-m-d H:i:s',
                ]
            ],
            [
                [
                    'max_normalize_depth' => 1234,
                ]
            ]
        ];
    }
}
