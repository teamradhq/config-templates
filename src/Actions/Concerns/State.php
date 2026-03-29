<?php

declare(strict_types=1);

namespace TeamRadHQ\ConfigTemplates\Actions\Concerns;

enum State: string
{
    case Pending = 'pending';
    case Running = 'running';
    case Success = 'success';
    case Failed = 'failed';
    case Cancelled = 'cancelled';

    public static function finished(self $state): bool
    {
        return $state !== self::Pending && $state !== self::Running;
    }
}
