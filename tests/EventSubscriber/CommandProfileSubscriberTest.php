<?php

namespace Tourze\CommandProfileBundle\Tests\EventSubscriber;

use Carbon\CarbonImmutable;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Event\ConsoleTerminateEvent;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Tourze\CommandProfileBundle\EventSubscriber\CommandProfileSubscriber;
use Tourze\PHPUnitSymfonyKernelTest\AbstractEventSubscriberTestCase;

/**
 * @internal
 */
#[CoversClass(CommandProfileSubscriber::class)]
#[RunTestsInSeparateProcesses]
final class CommandProfileSubscriberTest extends AbstractEventSubscriberTestCase
{
    private CommandProfileSubscriber $subscriber;

    /** @var MockObject&OutputInterface */
    private OutputInterface $output;

    protected function onSetUp(): void
    {
        $this->subscriber = self::getService(CommandProfileSubscriber::class);
        $this->output = $this->createMock(OutputInterface::class);
    }

    private function resetTestTime(): void
    {
        CarbonImmutable::setTestNow(); // 重置测试时间
    }

    public function testOnCommand(): void
    {
        // 设置固定的测试时间
        $testTime = CarbonImmutable::create(2023, 1, 1, 12, 0, 0);
        CarbonImmutable::setTestNow($testTime);

        $input = $this->createMock(InputInterface::class);
        // 使用 Command 具体类而非接口的原因：
        // 1. ConsoleCommandEvent 构造函数要求 Command 类型参数，无法使用接口
        // 2. Symfony Console 组件设计中 Command 是基础抽象类，提供核心功能
        // 3. 测试中只需要 Command 实例作为事件参数，不需要调用具体方法
        $command = $this->createMock(Command::class);

        $event = new ConsoleCommandEvent($command, $input, $this->output);

        $this->subscriber->onCommand($event);

        // 使用反射检查私有属性 map
        $reflection = new \ReflectionClass($this->subscriber);
        $mapProperty = $reflection->getProperty('map');
        $mapProperty->setAccessible(true);

        $map = $mapProperty->getValue($this->subscriber);
        $this->assertInstanceOf(\WeakMap::class, $map);

        // 检查 input 是否被存储在 WeakMap 中
        $this->assertTrue($map->offsetExists($input));
        $this->assertEquals($testTime, $map->offsetGet($input));

        $this->resetTestTime();
    }

    public function testReset(): void
    {
        $input = $this->createMock(InputInterface::class);
        $command = $this->createMock(Command::class);
        $output = $this->createMock(OutputInterface::class);

        $event = new ConsoleCommandEvent($command, $input, $output);
        $this->subscriber->onCommand($event);

        // 验证WeakMap中有数据
        $reflection = new \ReflectionClass($this->subscriber);
        $mapProperty = $reflection->getProperty('map');
        $mapProperty->setAccessible(true);
        $map = $mapProperty->getValue($this->subscriber);
        $this->assertInstanceOf(\WeakMap::class, $map);
        $this->assertTrue($map->offsetExists($input));

        // 重置后验证WeakMap为空
        $this->subscriber->reset();
        $mapAfterReset = $mapProperty->getValue($this->subscriber);
        $this->assertInstanceOf(\WeakMap::class, $mapAfterReset);
        $this->assertFalse($mapAfterReset->offsetExists($input));
    }

    public function testOnTerminate(): void
    {
        // 设置开始时间，比结束时间早 5 秒
        $startTime = CarbonImmutable::create(2023, 1, 1, 11, 59, 55);
        CarbonImmutable::setTestNow($startTime);

        $input = $this->createMock(InputInterface::class);
        // 使用 Command 具体类而非接口的原因：
        // 1. ConsoleTerminateEvent 构造函数要求 Command 类型参数，无法使用接口
        // 2. Symfony Console 组件设计中 Command 是基础抽象类，提供核心功能
        // 3. 测试中只需要 Command 实例作为事件参数，不需要调用具体方法
        $command = $this->createMock(Command::class);

        // 先调用 onCommand 设置开始时间
        $commandEvent = new ConsoleCommandEvent($command, $input, $this->output);
        $this->subscriber->onCommand($commandEvent);

        // 设置结束时间，比开始时间晚 5 秒
        $endTime = CarbonImmutable::create(2023, 1, 1, 12, 0, 0);
        CarbonImmutable::setTestNow($endTime);

        $terminateEvent = new ConsoleTerminateEvent($command, $input, $this->output, 0);

        // 我们需要按照调用顺序进行模拟，因为同一个方法被调用了两次，但参数不同
        $this->output->expects($this->exactly(2))
            ->method('writeln')
            ->willReturnCallback(function ($message) {
                static $callCount = 0;
                ++$callCount;

                if (1 === $callCount) {
                    $this->assertEquals('', $message);
                } elseif (2 === $callCount) {
                    $this->assertStringContainsString('RunTime:', $message);
                    // 我们不再检查具体数值，只检查格式
                }

                return null;
            })
        ;

        $this->subscriber->onTerminate($terminateEvent);

        $this->resetTestTime();
    }
}
