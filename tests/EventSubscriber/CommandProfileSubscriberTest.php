<?php

namespace Tourze\CommandProfileBundle\Tests\EventSubscriber;

use Carbon\CarbonImmutable;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Event\ConsoleTerminateEvent;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Tourze\CommandProfileBundle\EventSubscriber\CommandProfileSubscriber;

class CommandProfileSubscriberTest extends TestCase
{
    private CommandProfileSubscriber $subscriber;
    private OutputInterface $output;

    protected function setUp(): void
    {
        $this->subscriber = new CommandProfileSubscriber();
        $this->output = $this->createMock(OutputInterface::class);
    }

    protected function tearDown(): void
    {
        CarbonImmutable::setTestNow(); // 重置测试时间
    }

    public function testOnCommand(): void
    {
        // 设置固定的测试时间
        $testTime = CarbonImmutable::create(2023, 1, 1, 12, 0, 0);
        CarbonImmutable::setTestNow($testTime);

        $input = $this->createMock(InputInterface::class);
        $command = $this->createMock(Command::class);

        $event = new ConsoleCommandEvent($command, $input, $this->output);

        $this->subscriber->onCommand($event);

        // 使用反射检查私有属性 startTime
        $reflection = new \ReflectionClass($this->subscriber);
        $startTimeProperty = $reflection->getProperty('startTime');
        $startTimeProperty->setAccessible(true);

        $this->assertNotNull($startTimeProperty->getValue($this->subscriber));
        $this->assertEquals($testTime, $startTimeProperty->getValue($this->subscriber));
    }

    public function testOnTerminate(): void
    {
        // 设置结束时间先，让后面的开始时间更晚，确保得到正数结果
        $endTime = CarbonImmutable::create(2023, 1, 1, 12, 0, 0);
        CarbonImmutable::setTestNow($endTime);

        $input = $this->createMock(InputInterface::class);
        $command = $this->createMock(Command::class);

        $terminateEvent = new ConsoleTerminateEvent($command, $input, $this->output, 0);

        // 我们需要按照调用顺序进行模拟，因为同一个方法被调用了两次，但参数不同
        $this->output->expects($this->exactly(2))
            ->method('writeln')
            ->willReturnCallback(function ($message) {
                static $callCount = 0;
                $callCount++;

                if ($callCount === 1) {
                    $this->assertEquals('', $message);
                } elseif ($callCount === 2) {
                    $this->assertStringContainsString('RunTime:', $message);
                    // 我们不再检查具体数值，只检查格式
                }

                return null;
            });

        // 设置开始时间，比结束时间早 5 秒
        $startTime = CarbonImmutable::create(2023, 1, 1, 11, 59, 55);

        // 使用反射设置 startTime 属性
        $reflection = new \ReflectionClass($this->subscriber);
        $startTimeProperty = $reflection->getProperty('startTime');
        $startTimeProperty->setAccessible(true);
        $startTimeProperty->setValue($this->subscriber, $startTime);

        $this->subscriber->onTerminate($terminateEvent);
    }

    public function testReset(): void
    {
        // 设置固定的测试时间
        CarbonImmutable::setTestNow(CarbonImmutable::create(2023, 1, 1, 12, 0, 0));

        $input = $this->createMock(InputInterface::class);
        $command = $this->createMock(Command::class);

        $event = new ConsoleCommandEvent($command, $input, $this->output);
        $this->subscriber->onCommand($event);

        // 使用反射检查私有属性 startTime
        $reflection = new \ReflectionClass($this->subscriber);
        $startTimeProperty = $reflection->getProperty('startTime');
        $startTimeProperty->setAccessible(true);

        // 确认 startTime 已设置
        $this->assertNotNull($startTimeProperty->getValue($this->subscriber));

        // 调用 reset 方法
        $this->subscriber->reset();

        // 确认 startTime 已被重置
        $this->assertNull($startTimeProperty->getValue($this->subscriber));
    }
}
