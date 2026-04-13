<?php

declare(strict_types=1);

namespace TeamRadHQ\ConfigTemplates\Console;

use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;
use TeamRadHQ\ConfigTemplates\Actions\InstallComposerDevPackages;
use TeamRadHQ\ConfigTemplates\ConfigTemplateException;

#[AsCommand(
    name: 'install:packages',
    description: 'Install composer dev packages',
    help: 'Attempts to install the given composer packages.'
)]
final class InstallPackages
{
    /**
     * @param string[] $packages
     *
     * @throws ConfigTemplateException
     */
    public function __invoke(
        #[Argument(
            description: 'The packages to be installed',
            name: 'packages',
            suggestedValues: ['phpstan/phpstan', 'phpunit/phpunit']
        )]
        array $packages,
        OutputInterface $output,
    ): int {
        $installComposerDevPackages = new InstallComposerDevPackages($packages);
        $status = $installComposerDevPackages->run();

        $logType = $status === 0 ? 'info' : 'error';

        $output->writeln(sprintf(
            '<%s>%s</%s>',
            $logType,
            $installComposerDevPackages->result()->value(),
            $logType
        ));

        return Command::SUCCESS;
    }
}
