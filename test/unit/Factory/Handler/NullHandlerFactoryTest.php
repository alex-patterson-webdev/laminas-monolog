<?php

declare(strict_types=1);

namespace ArpTest\LaminasMonolog\Factory\Handler;

use Arp\LaminasFactory\FactoryInterface;
use Arp\LaminasMonolog\Factory\Handler\NullHandlerFactory;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Monolog\Handler\NullHandler;
use Monolog\Logger;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

/**
 * @covers \Arp\LaminasMonolog\Factory\Handler\NullHandlerFactory
 */
final class NullHandlerFactoryTest extends TestCase
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
        $factory = new NullHandlerFactory();

        $this->assertInstanceOf(FactoryInterface::class, $factory);
    }

    /**
     * @dataProvider getInvokeWillReturnConfiguredNullHandlerInstanceData
     *
     * @param array<mixed> $options
     *
     * @throws ServiceNotCreatedException
     * @throws ContainerExceptionInterface
     */
    public function testInvokeWillReturnConfiguredNullHandlerInstance(array $options): void
    {
        $factory = new NullHandlerFactory();

        $handler = $factory($this->container, NullHandler::class, $options);

        $this->assertInstanceOf(NullHandler::class, $handler);
    }

    /**
     * @return array<mixed>
     */
    public function getInvokeWillReturnConfiguredNullHandlerInstanceData(): array
    {
        return [
            [
                []
            ],
            [
                [
                    'level' => Logger::CRITICAL,
                ]
            ]
        ];
    }
}
