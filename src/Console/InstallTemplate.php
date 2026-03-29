<?php

declare(strict_types=1);

namespace TeamRadHQ\ConfigTemplates\Console;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;

#[AsCommand(
    name: 'install:config',
    description: 'Install a configuration template',
    help: 'Installs a configuration file.'
)]
final class InstallTemplate
{
    public function __invoke(): int
    {
        return Command::SUCCESS;
    }
}
