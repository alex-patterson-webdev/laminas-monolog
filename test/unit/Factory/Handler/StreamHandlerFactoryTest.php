<?php

declare(strict_types=1);

namespace ArpTest\LaminasMonolog\Factory\Handler;

use Arp\LaminasFactory\FactoryInterface;
use Arp\LaminasMonolog\Factory\Handler\StreamHandlerFactory;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Monolog\Handler\StreamHandler;
use Monolog\Test\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

/**
 * @covers \Arp\LaminasMonolog\Factory\Handler\StreamHandlerFactory
 */
final class StreamHandlerFactoryTest extends TestCase
{
    private ContainerInterface $container;

    public function setUp(): void
    {
        $this->container = $this->createMock(ContainerInterface::class);
    }

    public function testImplementsFactoryInterface(): void
    {
        $handler = new StreamHandlerFactory();
        $this->assertInstanceOf(FactoryInterface::class, $handler);
    }

    /**
     * @throws ServiceNotCreatedException
     * @throws ContainerExceptionInterface
     */
    public function testMissingStreamConfigurationThrowsServiceNotCreatedException(): void
    {
        $handler = new StreamHandlerFactory();

        $requestedName = StreamHandlerFactory::class;

        $this->expectException(ServiceNotCreatedException::class);
        $this->expectExceptionMessage(
            sprintf('The required \'stream\' configuration option is missing for service \'%s\'', $requestedName)
        );

        $handler($this->container, $requestedName, []);
    }

    /**
     * @throws ContainerExceptionInterface
     */
    public function testInvalidClassNameConfigurationThrowsServiceNotCreatedException(): void
    {
        $handler = new StreamHandlerFactory();

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

        $handler($this->container, $requestedName, $options);
    }
}
