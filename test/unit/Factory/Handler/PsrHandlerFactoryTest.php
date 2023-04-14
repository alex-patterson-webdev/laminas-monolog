<?php

declare(strict_types=1);

namespace ArpTest\LaminasMonolog\Factory\Handler;

use Arp\LaminasFactory\FactoryInterface;
use Arp\LaminasMonolog\Factory\Handler\PsrHandlerFactory;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\PsrHandler;
use Monolog\Logger;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

/**
 * @covers \Arp\LaminasMonolog\Factory\Handler\PsrHandlerFactory
 * @covers \Arp\LaminasMonolog\Factory\FactoryLoggerProviderTrait
 * @covers \Arp\LaminasMonolog\Factory\FactoryFormatterProviderTrait
 */
final class PsrHandlerFactoryTest extends TestCase
{
    /**
     * @var ContainerInterface&MockObject
     */
    private ContainerInterface $container;

    public function setUp(): void
    {
        $this->container = $this->createMock(ContainerInterface::class);
    }

    /**
     * Assert that the factory implements FactoryInterface
     */
    public function testImplementsFactoryInterface(): void
    {
        $factory = new PsrHandlerFactory();

        $this->assertInstanceOf(FactoryInterface::class, $factory);
    }

    /**
     * @throws ContainerExceptionInterface
     */
    public function testServiceNotCreatedExceptionIsThrownIfNotLoggerOptionIsProvided(): void
    {
        $factory = new PsrHandlerFactory();

        $requestedName = 'LoggerServiceName';

        $this->expectException(ServiceNotCreatedException::class);
        $this->expectExceptionMessage(
            sprintf('The required \'logger\' configuration option is missing for service \'%s\'', $requestedName)
        );

        $factory($this->container, $requestedName, []);
    }

    /**
     * @dataProvider getInvokeWillReturnConfiguredPsrHandlerInstanceData
     *
     * @param array<mixed> $options
     *
     * @throws ServiceNotCreatedException
     * @throws ContainerExceptionInterface
     */
    public function testInvokeWillReturnConfiguredPsrHandlerInstance(array $options): void
    {
        $factory = new PsrHandlerFactory();

        $handler = $factory($this->container, PsrHandler::class, $options);

        $this->assertInstanceOf(PsrHandler::class, $handler);
    }

    /**
     * @return array<mixed>
     */
    public function getInvokeWillReturnConfiguredPsrHandlerInstanceData(): array
    {
        return [
            [
                [
                    'logger' => $this->createMock(LoggerInterface::class),
                ]
            ],
            [
                [
                    'logger' => $this->createMock(LoggerInterface::class),
                    'level' => Logger::CRITICAL,
                ]
            ],
            [
                [
                    'logger' => $this->createMock(LoggerInterface::class),
                    'handler' => $this->createMock(HandlerInterface::class),
                ]
            ]
        ];
    }
}
