<?php

declare(strict_types=1);

namespace Tourze\CommandProfileBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class CommandProfileBundle extends Bundle
{
    /**
     * @return array<string, mixed>
     */
    public static function getBundleDependencies(): array
    {
        return ['all' => true];
    }
}
