<?php

declare(strict_types=1);

namespace TeamRadHQ\ConfigTemplates;

interface ConfiguresPackage
{
    /**
     * Get the path to where this package's files are installed.
     */
    public function packageDir(): string;

    /**
     * Get the directory that this package is installed to.
     *
     * @throws ConfigTemplateException When the project directory cannot be determined.
     */
    public function projectDir(): string;

    /**
     * Get the composer file that this package is installed to.
     *
     * @throws ConfigTemplateException When composer.json is not found.
     */
    public function composerFile(): string;
}
