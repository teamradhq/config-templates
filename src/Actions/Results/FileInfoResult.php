<?php

declare(strict_types=1);

namespace TeamRadHQ\ConfigTemplates\Actions\Results;

use InvalidArgumentException;
use Symfony\Component\Finder\SplFileInfo;
use TeamRadHQ\ConfigTemplates\Actions\Concerns\Result;
use TeamRadHQ\ConfigTemplates\Actions\Concerns\State;

/**
 * @implements Result<SplFileInfo[]>
 */
final class FileInfoResult implements Result
{
    private State $state = State::Pending;

    /** @var SplFileInfo[] */
    private array $value = [];

    /**
     * @param array<array-key, string> $locations
     */
    public function __construct(private readonly array $locations) {}

    /**
     * {@inheritDoc}
     *
     * @throws InvalidArgumentException When new state is provided after the action is finished.
     */
    public function state(?State $state = null): State
    {
        if ($state instanceof State && State::finished($this->state)) {
            throw new InvalidArgumentException('Cannot transition to a new state when action is already finished.');
        }

        if ($state instanceof State) {
            $this->state = $state;
        }

        return $this->state;
    }

    /**
     * {@inheritDoc}
     */
    public function status(): ?int
    {
        if (!State::finished($this->state)) {
            return null;
        }

        return $this->state === State::Success ? 0 : 1;
    }

    /**
     * {@inheritDoc}
     *
     * @return SplFileInfo[]
     */
    public function value(): array
    {
        if ($this->state === State::Success && count($this->value) !== count($this->locations)) {
            $this->set();
        }

        return $this->value;
    }

    /**
     * {@inheritDoc}
     *
     * @param mixed $value
     * @return void
     */
    public function set(mixed $value = null): void
    {
        foreach ($this->locations as $key => $location) {
            $this->value[$key] = new SplFileInfo($location, '', '');
        }
    }
}
