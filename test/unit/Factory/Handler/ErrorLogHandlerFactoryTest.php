<?php

declare(strict_types=1);

namespace ArpTest\LaminasMonolog\Factory\Handler;

use Arp\LaminasFactory\FactoryInterface;
use Arp\LaminasMonolog\Factory\Handler\ErrorLogHandlerFactory;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Logger;
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
    private ContainerInterface $container;

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

    /**
     * Assert that calls to __invoke() will return a configured ErrorLogHandler instance
     *
     * @throws ServiceNotCreatedException
     */
    public function testInvokeWillReturnValidErrorLogHandler(): void
    {
        $factory = new ErrorLogHandlerFactory();

        $options = [
            'level' => Logger::DEBUG,
        ];

        $this->assertInstanceOf(
            ErrorLogHandler::class,
            $factory($this->container, ErrorLogHandler::class, $options)
        );
    }
}
