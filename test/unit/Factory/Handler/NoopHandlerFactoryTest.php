<?php

declare(strict_types=1);

namespace ArpTest\LaminasMonolog\Factory\Handler;

use Arp\LaminasFactory\FactoryInterface;
use Arp\LaminasMonolog\Factory\Handler\NoopHandlerFactory;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Monolog\Handler\NoopHandler;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

/**
 * @covers \Arp\LaminasMonolog\Factory\Handler\NoopHandlerFactory
 */
final class NoopHandlerFactoryTest extends TestCase
{
    private ContainerInterface $container;

    public function setUp(): void
    {
        $this->container = $this->createMock(ContainerInterface::class);
    }

    public function testImplementsFactoryInterface(): void
    {
        $factory = new NoopHandlerFactory();
        $this->assertInstanceOf(FactoryInterface::class, $factory);
    }

    /**
     * @throws ServiceNotCreatedException
     */
    public function testInvoke(): void
    {
        $factory = new NoopHandlerFactory();

        $this->assertInstanceOf(NoopHandler::class, $factory($this->container, NoopHandler::class));
    }
}
