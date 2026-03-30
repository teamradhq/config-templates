<?php

declare(strict_types=1);

namespace TeamRadHQ\ConfigTemplates;

use Composer\Factory;

/**
 * Provides global package configuration.
 */
final readonly class Configuration implements ConfiguresPackage
{
    public function __construct(
        private Factory $factory = new Factory,
    ) {}

    /**
     * {@inheritDoc}
     */
    public function packageDir(): string
    {
        return dirname(__DIR__);
    }

    /**
     * {@inheritDoc}
     *
     * @throws ConfigTemplateException When the project directory cannot be determined.
     */
    public function projectDir(): string
    {
        try {
            return dirname($this->composerFile());
        } catch (ConfigTemplateException $configTemplateException) {
            throw new ConfigTemplateException(
                'Could not determine project directory',
                $configTemplateException->getCode(),
                previous: $configTemplateException
            );
        }
    }

    /**
     * {@inheritDoc}
     *
     * @throws ConfigTemplateException When composer.json is not found.
     */
    public function composerFile(): string
    {
        $filepath = realpath($this->factory::getComposerFile());

        if ($filepath === false) {
            throw new ConfigTemplateException('Could not find composer.json');
        }

        return $filepath;
    }
}
