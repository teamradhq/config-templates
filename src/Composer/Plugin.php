<?php

declare(strict_types=1);

namespace TeamRadHQ\ConfigTemplates\Composer;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;

final class Plugin implements PluginInterface
{
    public function activate(Composer $composer, IOInterface $io): void
    {
        // TODO: Implement activate() method.
    }

    public function deactivate(Composer $composer, IOInterface $io): void
    {
        // TODO: Implement deactivate() method.
    }

    public function uninstall(Composer $composer, IOInterface $io): void
    {
        // TODO: Implement uninstall() method.
    }
}
