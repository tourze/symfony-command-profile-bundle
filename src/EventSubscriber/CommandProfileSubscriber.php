<?php

namespace Tourze\CommandProfileBundle\EventSubscriber;

use Carbon\CarbonImmutable;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Event\ConsoleTerminateEvent;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

/**
 * 用于在命令执行成功后，打印出最终的耗时情况
 */
#[Autoconfigure(public: true)]
class CommandProfileSubscriber
{
    /** @var \WeakMap<InputInterface, CarbonImmutable> */
    private \WeakMap $map;

    public function __construct()
    {
        $this->map = new \WeakMap();
    }

    #[AsEventListener(event: ConsoleEvents::COMMAND)]
    public function onCommand(ConsoleCommandEvent $event): void
    {
        $this->map->offsetSet($event->getInput(), CarbonImmutable::now(date_default_timezone_get()));
    }

    #[AsEventListener(event: ConsoleEvents::TERMINATE)]
    public function onTerminate(ConsoleTerminateEvent $event): void
    {
        $startTime = $this->map->offsetGet($event->getInput());
        assert($startTime instanceof CarbonImmutable);

        try {
            $runTime = CarbonImmutable::now(date_default_timezone_get())->diffInSeconds($startTime);
            $output = $event->getOutput();
            $output->writeln('');
            $output->writeln("RunTime: {$runTime}");
        } finally {
            $this->map->offsetUnset($event->getInput());
        }
    }

    public function reset(): void
    {
        $this->map = new \WeakMap();
    }
}
