<?php

declare(strict_types=1);

namespace TeamRadHQ\ConfigTemplates\Console;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'list:configs',
    description: 'List available config types',
    help: 'List all of the available config types that can be used.'
)]
final class ListTemplates
{
    public function __invoke(OutputInterface $output): int
    {
        $this->title($output, 'Available config types');

        $this->tabulate($output, [
            'composer' => 'Work with composer json files.',
            'phpcs' => 'Work with phpcs xml files.',
            'phpstan' => 'Work with phpstan neon files.',
            'phpunit' => 'Work with phpunit xml files.',
            'pint' => 'Work with pint json files.',
        ]);

        return Command::SUCCESS;
    }

    private function title(OutputInterface $output, string $title): void
    {
        $output->writeln(sprintf(
            '<options=bold;fg=green>%s:</>',
            $title,
        ));
    }

    /**
     * @param array<string, string> $data
     */
    private function tabulate(OutputInterface $output, array $data): void
    {
        foreach ($data as $info => $comment) {
            $output->writeln(sprintf(
                "\t<info>%-12s</info> <comment>%s</comment>",
                $info,
                $comment,
            ));
        }
    }
}
