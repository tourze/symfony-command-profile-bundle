# Symfony Command Profile Bundle

[English](README.md) | [中文](README.zh-CN.md)

[![Latest Version](https://img.shields.io/packagist/v/tourze/symfony-command-profile-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/symfony-command-profile-bundle)
[![Build Status](https://img.shields.io/travis/tourze/symfony-command-profile-bundle/master.svg?style=flat-square)](https://travis-ci.org/tourze/symfony-command-profile-bundle)
[![Quality Score](https://img.shields.io/scrutinizer/g/tourze/symfony-command-profile-bundle.svg?style=flat-square)](https://scrutinizer-ci.com/g/tourze/symfony-command-profile-bundle)
[![Total Downloads](https://img.shields.io/packagist/dt/tourze/symfony-command-profile-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/symfony-command-profile-bundle)

一个用于 Symfony 的 Bundle，可以在每次 Console 命令执行后自动输出本次运行的耗时，帮助你分析自定义或内置命令的性能表现。

## 功能特性

- 自动统计并输出每个 Symfony Console 命令的执行耗时
- 无需修改命令代码，开箱即用
- 轻量、易集成

## 安装说明

确保你的项目环境为 Symfony 6.4+ 和 PHP 8.1+。

通过 Composer 安装：

```bash
composer require tourze/symfony-command-profile-bundle
```

## 快速开始

1. 如未自动注册，请在 `config/bundles.php` 中手动注册 Bundle：

```php
return [
    // ...
    Tourze\CommandProfileBundle\CommandProfileBundle::class => ['all' => true],
];
```

2. 执行任意 Symfony Console 命令：

```bash
php bin/console your:command
```

命令执行结束后，终端会自动输出类似如下的耗时信息：

```
RunTime: 0.123456
```

无需额外配置。

## 高级说明

- Bundle 通过事件订阅器监听 `ConsoleEvents::COMMAND` 和 `ConsoleEvents::TERMINATE` 事件实现功能。
- 使用 [nesbot/carbon](https://carbon.nesbot.com/) 进行高精度时间计算。

## 贡献指南

请查阅 [CONTRIBUTING.md](CONTRIBUTING.md) 获取详细贡献流程。

## 许可协议

本项目遵循 MIT 开源协议。

## 更新日志

请参阅 [CHANGELOG.md](CHANGELOG.md) 获取版本历史及升级说明。
