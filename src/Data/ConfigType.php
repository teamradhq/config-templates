<?php

declare(strict_types=1);

namespace TeamRadHQ\ConfigTemplates\Data;

enum ConfigType: string
{
    case PhpCs = 'phpcs';
    case PhpStan = 'phpstan';
    case PhpUnit = 'phpunit';
    case Pint = 'pint';
    case Rector = 'rector';
}
