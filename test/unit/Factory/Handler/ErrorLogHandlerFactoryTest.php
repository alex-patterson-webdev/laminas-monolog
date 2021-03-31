<?php

declare(strict_types=1);

namespace ArpTest\LaminasMonolog\Factory\Handler;

use Arp\LaminasFactory\FactoryInterface;
use Arp\LaminasMonolog\Factory\Handler\ErrorLogHandlerFactory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

/**
 * @covers \Arp\LaminasMonolog\Factory\Handler\ErrorLogHandlerFactory
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package ArpTest\LaminasMonolog\Factory\Handler
 */
final class ErrorLogHandlerFactoryTest extends TestCase
{
    /**
     * @var ContainerInterface&MockObject
     */
    private $container;

    /**
     * Prepare the test case dependencies
     */
    public function setUp(): void
    {
        $this->container = $this->createMock(ContainerInterface::class);
    }

    /**
     * Assert that the factory implements FactoryInterface
     */
    public function testImplementsFactoryInterface(): void
    {
        $factory = new ErrorLogHandlerFactory();

        $this->assertInstanceOf(FactoryInterface::class, $factory);
    }
}
