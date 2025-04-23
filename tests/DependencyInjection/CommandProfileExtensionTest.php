<?php

namespace Tourze\CommandProfileBundle\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Tourze\CommandProfileBundle\DependencyInjection\CommandProfileExtension;
use Tourze\CommandProfileBundle\EventSubscriber\CommandProfileSubscriber;

class CommandProfileExtensionTest extends TestCase
{
    public function testLoad(): void
    {
        $extension = new CommandProfileExtension();
        $container = new ContainerBuilder();

        $extension->load([], $container);

        // 验证服务是否已注册
        $this->assertTrue($container->has(CommandProfileSubscriber::class));

        // 获取服务定义
        $definition = $container->getDefinition(CommandProfileSubscriber::class);

        // 验证服务是否正确配置
        $this->assertTrue($definition->isAutowired());
        $this->assertTrue($definition->isAutoconfigured());
        $this->assertFalse($definition->isPublic());
    }
}
