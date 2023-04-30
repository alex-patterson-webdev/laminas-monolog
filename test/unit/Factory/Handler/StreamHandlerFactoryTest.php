<?php

declare(strict_types=1);

namespace ArpTest\LaminasMonolog\Factory\Handler;

use Arp\LaminasFactory\FactoryInterface;
use Arp\LaminasMonolog\Factory\Handler\StreamHandlerFactory;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Monolog\Formatter\FormatterInterface;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Test\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

/**
 * @covers \Arp\LaminasMonolog\Factory\Handler\StreamHandlerFactory
 */
final class StreamHandlerFactoryTest extends TestCase
{
    private ContainerInterface&MockObject $container;

    public function setUp(): void
    {
        $this->container = $this->createMock(ContainerInterface::class);
    }

    public function testImplementsFactoryInterface(): void
    {
        $factory = new StreamHandlerFactory();
        $this->assertInstanceOf(FactoryInterface::class, $factory);
    }

    /**
     * @throws ServiceNotCreatedException
     * @throws ContainerExceptionInterface
     */
    public function testMissingStreamConfigurationThrowsServiceNotCreatedException(): void
    {
        $factory = new StreamHandlerFactory();

        $requestedName = StreamHandlerFactory::class;

        $this->expectException(ServiceNotCreatedException::class);
        $this->expectExceptionMessage(
            sprintf('The required \'stream\' configuration option is missing for service \'%s\'', $requestedName)
        );

        $factory($this->container, $requestedName, []);
    }

    /**
     * @throws ContainerExceptionInterface
     */
    public function testInvalidClassNameConfigurationThrowsServiceNotCreatedException(): void
    {
        $factory = new StreamHandlerFactory();

        $requestedName = StreamHandlerFactory::class;

        $options = [
            'class_name' => \stdClass::class,
            'stream' => 'stream value',
        ];

        $this->expectException(ServiceNotCreatedException::class);
        $this->expectExceptionMessage(
            sprintf(
                'The stream handler provided via configuration option \'class_name\' is invalid: '
                . 'The stream handler class must extend from \'%s\'; \'%s\' provided for service \'%s\'',
                StreamHandler::class,
                $options['class_name'],
                $requestedName,
            ),
        );

        $factory($this->container, $requestedName, $options);
    }

    /**
     * @dataProvider getInvokeData
     *
     * @param array<string, mixed> $options
     *
     * @throws ContainerExceptionInterface
     * @throws ServiceNotCreatedException
     */
    public function testInvoke(array $options): void
    {
        $requestedName = StreamHandler::class;
        $options = array_merge(
            [
                'stream' => 'Mock stream value',
            ],
            $options,
        );

        $factory = new StreamHandlerFactory();

        $this->container->expects($this->once())
            ->method('has')
            ->with($options['formatter'] ?? LineFormatter::class)
            ->willReturn(true);

        /** @var FormatterInterface&MockObject $formatter */
        $formatter = $this->createMock(FormatterInterface::class);
        $this->container->expects($this->once())
            ->method('get')
            ->with($options['formatter'] ?? LineFormatter::class)
            ->willReturn($formatter);

        $this->assertInstanceOf(StreamHandler::class, $factory($this->container, $requestedName, $options));
    }

    /**
     * @return array<int, array<int, array<string, mixed>>>
     */
    public function getInvokeData(): array
    {
        return [
            [
                [],
            ],
            [
                [
                    'level' => Logger::CRITICAL,
                    'bubble' => false,
                ],
            ],
            [
                [
                    'file_permission' => 655,
                    'use_locking' => false,
                ],
            ]
        ];
    }
}
