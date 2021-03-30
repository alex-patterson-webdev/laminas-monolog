<?php

declare(strict_types=1);

namespace Arp\LaminasMonolog;

/**
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\LaminasMonolog
 */
class Module
{
    /**
     * @return array<mixed>
     */
    public function getConfig(): array
    {
        return require __DIR__ . '/../config/module.config.php';
    }
}
