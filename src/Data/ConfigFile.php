<?php

declare(strict_types=1);

namespace TeamRadHQ\ConfigTemplates\Data;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use Symfony\Component\Finder\SplFileInfo;
use TeamRadHQ\ConfigTemplates\ConfigTemplateException;
use TeamRadHQ\ConfigTemplates\Configuration;
use TeamRadHQ\ConfigTemplates\ConfiguresPackage;

final readonly class ConfigFile
{
    /**
     * @throws ConfigTemplateException
     */
    public function __construct(
        public string $type,
        public string $configPath,
        public string $templatePath,
    ) {
        if ($configPath === $templatePath) {
            throw new ConfigTemplateException('Template and config cannot be the same file');
        }
    }

    /**
     * @throws ConfigTemplateException
     */
    public static function forType(
        string $type,
        ConfiguresPackage $configuresPackage = new Configuration,
        Filesystem $filesystem = new Filesystem,
    ): self {
        $name = match ($type) {
            'phpcs' => 'phpcs.xml',
            'phpstan' => 'phpstan.neon',
            'phpunit' => 'phpunit.xml',
            'pint' => 'pint.json',
            'rector' => 'rector.php',
            default => throw new ConfigTemplateException('Unsupported type: ' . $type),
        };

        if (!$filesystem->exists($templatePath = Path::join($configuresPackage->packageDir(), $name))) {
            throw new ConfigTemplateException('Could not find ' . $templatePath);
        }

        if ($filesystem->exists($configPath = Path::join($configuresPackage->projectDir(), $name))) {
            throw new ConfigTemplateException('Config file already exists.');
        }

        return new self($type, $configPath, $templatePath);
    }

    public function info(): SplFileInfo
    {
        return new SplFileInfo($this->configPath, '', '');
    }

    public function templateInfo(): SplFileInfo
    {
        return new SplFileInfo($this->templatePath, '', '');
    }
}
