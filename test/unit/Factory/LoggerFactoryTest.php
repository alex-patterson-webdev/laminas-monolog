<?php

declare(strict_types=1);

namespace ArpTest\LaminasMonolog\Factory;

use Arp\LaminasFactory\FactoryInterface;
use Arp\LaminasMonolog\Factory\LoggerFactory;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\NoopHandler;
use Monolog\Handler\NullHandler;
use Monolog\Logger;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Arp\LaminasMonolog\Factory\LoggerFactory
 */
final class LoggerFactoryTest extends TestCase
{
    /**
     * @var ServiceLocatorInterface&MockObject
     */
    private $container;

    public function setUp(): void
    {
        $this->container = $this->createMock(ServiceLocatorInterface::class);
    }

    /**
     * Assert the class implements FactoryInterface
     */
    public function testImplementsFactoryInterface(): void
    {
        $factory = new LoggerFactory();

        $this->assertInstanceOf(FactoryInterface::class, $factory);
    }

    /**
     * Assert that a ServiceNotCreatedException is thrown from invoke() if a Handler is invalid
     */
    public function testInvokeWillThrowServiceNotCreatedExceptionIfProvidingInvalidHandlerConfiguration(): void
    {
        $logger = new LoggerFactory();

        $serviceName = Logger::class;
        $handler = new \stdClass();
        $options = [
            'handlers' => [
                $handler,
            ],
        ];

        $this->expectException(ServiceNotCreatedException::class);
        $this->expectExceptionMessage(
            sprintf(
                'The log handler \'%s\' must be an object of type \'%s\'; '
                . '\'%s\' provided for service \'%s\'',
                'object',
                HandlerInterface::class,
                get_class($handler),
                $serviceName,
            )
        );

        $logger($this->container, Logger::class, $options);
    }

    /**
     * @param array<mixed> $handlerConfigs
     *
     * @dataProvider getLoggerWillCreateConfiguredHandlersData
     *
     * @throws ServiceNotCreatedException
     */
    public function testLoggerWillCreateConfiguredHandlers(array $handlerConfigs = []): void
    {
        /** @var LoggerFactory&MockObject $factory */
        $factory = $this->getMockBuilder(LoggerFactory::class)
            ->onlyMethods(['getService', 'buildService'])
            ->getMock();

        $serviceName = 'FooLogger';

        $handlers = [];
        $getArgs = $buildArgs = [];
        $getReturns = $buildReturns = [];
        foreach ($handlerConfigs as $handlerName => $handler) {
            if (is_object($handler)) {
                $handlers[] = $handler;
                continue;
            }
            if (!is_array($handler) && !is_string($handler)) {
                continue;
            }

            /** @var HandlerInterface&MockObject $handlerObject */
            $handlerObject = $this->createMock(HandlerInterface::class);

            if (is_string($handler)) {
                $getArgs[] = [$this->container, $handler, $serviceName];
                $getReturns[] = $handlerObject;
            } elseif (is_array($handler)) {
                /** @var HandlerInterface&MockObject $handlerObject */
                $handlerObject = $this->createMock(HandlerInterface::class);
                $buildArgs[] = [$this->container, $handlerName, $handler, $serviceName];
                $buildReturns[] = $handlerObject;
            }
            $handlers[] = $handlerObject;
        }

        if (!empty($getArgs)) {
            $factory->expects($this->exactly(count($getArgs)))
                ->method('getService')
                ->withConsecutive(...$getArgs)
                ->willReturnOnConsecutiveCalls(...$getReturns);
        }

        if (!empty($buildArgs)) {
            $factory->expects($this->exactly(count($buildArgs)))
                ->method('buildService')
                ->withConsecutive(...$buildArgs)
                ->willReturnOnConsecutiveCalls(...$buildReturns);
        }

        $logger = $factory($this->container, $serviceName, ['handlers' => $handlerConfigs]);

        $this->assertSame($handlers, $logger->getHandlers());
    }

    /**
     * @return array<mixed>
     */
    public function getLoggerWillCreateConfiguredHandlersData(): array
    {
        return [
            [
                [
                    'Foo\\Bar\\Service\\TestHandler',
                    HandlerInterface::class,
                    $this->createMock(HandlerInterface::class),
                ],
            ],
            [
                [
                    $this->createMock(HandlerInterface::class),
                    NoopHandler::class => [
                        'test' => 123,
                        'config' => 'data'
                    ],
                    NullHandler::class,
                ]
            ],
            [
                [
                    $this->createMock(HandlerInterface::class),
                    $this->createMock(HandlerInterface::class),
                    NoopHandler::class,
                    $this->createMock(HandlerInterface::class),
                ],
            ],
        ];
    }
}
