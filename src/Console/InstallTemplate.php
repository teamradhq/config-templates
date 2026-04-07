<?php

declare(strict_types=1);

namespace TeamRadHQ\ConfigTemplates\Console;

use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;
use TeamRadHQ\ConfigTemplates\Actions\InstallConfig;
use TeamRadHQ\ConfigTemplates\ConfigTemplateException;
use TeamRadHQ\ConfigTemplates\Data\ConfigType;

#[AsCommand(
    name: 'install:config',
    description: 'Install a configuration template',
    help: 'Installs a configuration file.'
)]
final class InstallTemplate
{
    /**
     * @throws ConfigTemplateException
     */
    public function __invoke(
        #[Argument(
            description: 'The type of config to install',
            name: 'type',
            suggestedValues: [
                'phpcs',
                'phpstan',
                'phpunit',
                'pint',
                'rector',
            ]
        )]
        ConfigType $configType,
        OutputInterface $output,
    ): int {
        try {
            $installConfig = new InstallConfig($configType->value);
            $installConfig->run();
        } catch (ConfigTemplateException $configTemplateException) {
            $output->writeln('<error>' . $configTemplateException->getMessage() . '</error>');

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
