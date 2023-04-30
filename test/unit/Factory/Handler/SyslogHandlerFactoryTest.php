<?php

declare(strict_types=1);

namespace ArpTest\LaminasMonolog\Factory\Handler;

use Arp\LaminasFactory\FactoryInterface;
use Arp\LaminasMonolog\Factory\Handler\SyslogHandlerFactory;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Arp\LaminasMonolog\Factory\Handler\SyslogHandlerFactory
 */
final class SyslogHandlerFactoryTest extends TestCase
{
    public function testImplementsFactoryInterface(): void
    {
        $handler = new SyslogHandlerFactory();
        $this->assertInstanceOf(FactoryInterface::class, $handler);
    }
}
