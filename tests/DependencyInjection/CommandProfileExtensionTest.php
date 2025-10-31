<?php

namespace Tourze\CommandProfileBundle\Tests\DependencyInjection;

use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Tourze\CommandProfileBundle\DependencyInjection\CommandProfileExtension;
use Tourze\CommandProfileBundle\EventSubscriber\CommandProfileSubscriber;
use Tourze\PHPUnitSymfonyUnitTest\AbstractDependencyInjectionExtensionTestCase;

/**
 * @internal
 */
#[CoversClass(CommandProfileExtension::class)]
final class CommandProfileExtensionTest extends AbstractDependencyInjectionExtensionTestCase
{
    public function testLoad(): void
    {
        $extension = new CommandProfileExtension();
        $container = new ContainerBuilder();
        $container->setParameter('kernel.environment', 'test');

        $extension->load([], $container);

        $this->assertTrue($container->has(CommandProfileSubscriber::class));

        $definition = $container->getDefinition(CommandProfileSubscriber::class);
        $this->assertTrue($definition->isAutowired());
        $this->assertTrue($definition->isAutoconfigured());
        $this->assertTrue($definition->isPublic());
        $this->assertTrue($definition->hasTag('as-coroutine'));
    }
}
