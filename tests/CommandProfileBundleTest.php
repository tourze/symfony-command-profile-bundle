<?php

declare(strict_types=1);

namespace Tourze\CommandProfileBundle\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\CommandProfileBundle\CommandProfileBundle;
use Tourze\PHPUnitSymfonyKernelTest\AbstractBundleTestCase;

/**
 * @internal
 */
#[CoversClass(CommandProfileBundle::class)]
#[RunTestsInSeparateProcesses]
final class CommandProfileBundleTest extends AbstractBundleTestCase
{
}
