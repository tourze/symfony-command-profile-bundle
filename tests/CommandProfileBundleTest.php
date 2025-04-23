<?php

namespace Tourze\CommandProfileBundle\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Tourze\CommandProfileBundle\CommandProfileBundle;
use Tourze\CommandProfileBundle\DependencyInjection\CommandProfileExtension;

class CommandProfileBundleTest extends TestCase
{
    public function testGetContainerExtension(): void
    {
        $bundle = new CommandProfileBundle();
        $extension = $bundle->getContainerExtension();

        // 验证扩展是否是正确的类型
        $this->assertInstanceOf(CommandProfileExtension::class, $extension);
    }

    public function testBuild(): void
    {
        $bundle = new CommandProfileBundle();
        $container = new ContainerBuilder();

        // 调用build方法不应抛出异常
        $this->assertNull($bundle->build($container));
    }
}
