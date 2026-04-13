<?php

declare(strict_types=1);

namespace TeamRadHQ\ConfigTemplates\Actions;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Process\Process;
use TeamRadHQ\ConfigTemplates\Actions\Concerns\Action;
use TeamRadHQ\ConfigTemplates\Actions\Concerns\State;
use TeamRadHQ\ConfigTemplates\Actions\Results\ScalarResult;

/**
 * @implements Action<string>
 */
final readonly class InstallComposerDevPackages implements Action
{
    /** @var ScalarResult<string> */
    private ScalarResult $scalarResult;

    /**
     * @param string[] $packages
     */
    public function __construct(private array $packages)
    {
        $this->scalarResult = ScalarResult::string();
    }

    /**
     * {@inheritDoc}
     */
    public function run(): int
    {
        $result = '';

        $process = new Process(['composer', 'require', '--dev', ...$this->packages]);
        $process->run(function ($type, $buffer) use (&$result): void {
            $result .= $buffer;
        });

        $isSuccess = $process->isSuccessful();

        $this->scalarResult->state($isSuccess ? State::Success : State::Failed);
        $this->scalarResult->set($result);

        return $isSuccess ? Command::SUCCESS : Command::FAILURE;
    }

    /**
     * {@inheritDoc}
     *
     * @return ScalarResult<string>
     */
    public function result(): ScalarResult
    {
        return $this->scalarResult;
    }
}
