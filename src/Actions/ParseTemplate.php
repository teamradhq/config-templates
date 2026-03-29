<?php

declare(strict_types=1);

namespace TeamRadHQ\ConfigTemplates\Actions;

use RuntimeException;
use Symfony\Component\Finder\SplFileInfo;
use TeamRadHQ\ConfigTemplates\Actions\Concerns\Action;
use TeamRadHQ\ConfigTemplates\Actions\Concerns\Result;

/**
 * @implements Concerns\Action<SplFileInfo>
 */
final class ParseTemplate implements Action
{
    public function run(): int
    {
        return 1;
    }

    /**
     * @return Result<SplFileInfo>
     */
    public function result(): Result
    {
        throw new RuntimeException('Not implemented');
    }
}
