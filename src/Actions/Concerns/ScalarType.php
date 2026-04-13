<?php

declare(strict_types=1);

namespace TeamRadHQ\ConfigTemplates\Actions\Concerns;

enum ScalarType: string
{
    case Boolean = 'boolean';
    case Integer = 'integer';
    case Float = 'double';
    case String = 'string';
}
