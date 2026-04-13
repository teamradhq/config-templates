<?php

declare(strict_types=1);

namespace TeamRadHQ\ConfigTemplates\Data;

final class ConfigPackages
{
    /**
     * Get the dev package list required for the given config type.
     *
     * @return string[]
     */
    public static function packages(ConfigType $configType): array
    {
        return match ($configType) {
            ConfigType::PhpCs => [
                'squizlabs/php_codesniffer',
            ],
            ConfigType::PhpStan => [
                'phpstan/extension-installer',
                'phpstan/phpstan',
                'phpstan/phpstan',
                'phpstan/phpstan-mockery',
                'phpstan/phpstan-phpunit',
                'phpstan/phpstan-strict-rules',
            ],
            ConfigType::PhpUnit => [
                'brianium/paratest',
                'mockery/mockery',
                'phpunit/phpunit',
            ],
            ConfigType::Pint => ['laravel/pint'],
            ConfigType::Rector => ['rector/rector'],
        };
    }
}
