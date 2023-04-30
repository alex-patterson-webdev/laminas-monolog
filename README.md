![github workflow](https://github.com/alex-patterson-webdev/laminas-monolog/actions/workflows/workflow.yml/badge.svg)
[![codecov](https://codecov.io/gh/alex-patterson-webdev/laminas-monolog/branch/master/graph/badge.svg)](https://codecov.io/gh/alex-patterson-webdev/laminas-monolog)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/alex-patterson-webdev/laminas-monolog/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/alex-patterson-webdev/laminas-monolog/?branch=master)

# Arp\LaminasMonolog

## About

Monolog integration module for Laminas Applications

## Installation

Installation via [Composer](https://getcomposer.org).

    require alex-patterson-webdev/laminas-monolog ^1.0.0

Register the modules services with the Laminas Service Manager by adding the module namespace to your applications `config/modules.config.php` file.

    // config/modules.config.php
    return [
        // ...
        'Arp\\LaminasMonolog',
        // ...
    ];

## Loggers

The easiest way to create a new Monolog logger is via configuration options. The `Arp\\Monolog` provides factory classes to allow 
for _most_ of the default Monolog features to be optionally included, without having to write any code.




