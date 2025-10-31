# Symfony Command Profile Bundle

[English](README.md) | [中文](README.zh-CN.md)

[![Latest Version](https://img.shields.io/packagist/v/tourze/symfony-command-profile-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/symfony-command-profile-bundle)
[![PHP Version](https://img.shields.io/packagist/php-v/tourze/symfony-command-profile-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/symfony-command-profile-bundle)
[![License](https://img.shields.io/packagist/l/tourze/symfony-command-profile-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/symfony-command-profile-bundle)
[![Build Status](https://img.shields.io/travis/tourze/symfony-command-profile-bundle/master.svg?style=flat-square)](https://travis-ci.org/tourze/symfony-command-profile-bundle)
[![Quality Score](https://img.shields.io/scrutinizer/g/tourze/symfony-command-profile-bundle.svg?style=flat-square)](https://scrutinizer-ci.com/g/tourze/symfony-command-profile-bundle)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/tourze/symfony-command-profile-bundle.svg?style=flat-square)](https://scrutinizer-ci.com/g/tourze/symfony-command-profile-bundle)
[![Total Downloads](https://img.shields.io/packagist/dt/tourze/symfony-command-profile-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/symfony-command-profile-bundle)

A Symfony bundle that profiles and outputs the runtime of console commands. It helps you analyze the performance of your custom or built-in Symfony console commands by displaying the execution time after each run.

## Features

- Automatically tracks and displays the execution time for every Symfony console command
- No code modification needed for your commands
- Lightweight and easy to integrate

## Installation

Make sure your project uses Symfony 6.4+ and PHP 8.1+.

Install via Composer:

```bash
composer require tourze/symfony-command-profile-bundle
```

## Quick Start

1. Register the bundle in your Symfony application's `config/bundles.php` (if Flex does not auto-register):

```php
return [
    // ...
    Tourze\CommandProfileBundle\CommandProfileBundle::class => ['all' => true],
];
```

2. Run any Symfony console command:

```bash
php bin/console your:command
```

At the end of the command output, you will see a line like:

```text
RunTime: 0.123456
```

No additional configuration is required.

## Advanced

- The bundle uses event subscribers to hook into `ConsoleEvents::COMMAND` and `ConsoleEvents::TERMINATE`.
- Uses [nesbot/carbon](https://carbon.nesbot.com/) for precise time calculation.

## Contributing

Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details.

## License

This bundle is open-sourced software licensed under the MIT license.

## Changelog

See [CHANGELOG.md](CHANGELOG.md) for version history and upgrade notes.
