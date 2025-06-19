<?php

namespace Tourze\CommandProfileBundle\EventSubscriber;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Event\ConsoleTerminateEvent;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Contracts\Service\ResetInterface;

/**
 * 用于在命令执行成功后，打印出最终的耗时情况
 */
#[AutoconfigureTag('as-coroutine')]
class CommandProfileSubscriber implements ResetInterface
{
    private ?CarbonInterface $startTime = null;

    #[AsEventListener(event: ConsoleEvents::COMMAND)]
    public function onCommand(ConsoleCommandEvent $event): void
    {
        $this->startTime = CarbonImmutable::now(date_default_timezone_get());
    }

    #[AsEventListener(event: ConsoleEvents::TERMINATE)]
    public function onTerminate(ConsoleTerminateEvent $event): void
    {
        $runTime = CarbonImmutable::now(date_default_timezone_get())->diffInSeconds($this->startTime);
        $output = $event->getOutput();
        $output->writeln('');
        $output->writeln("RunTime: {$runTime}");
    }

    public function reset(): void
    {
        $this->startTime = null;
    }
}
