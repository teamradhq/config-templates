<?php

declare(strict_types=1);

namespace TeamRadHQ\ConfigTemplates\Actions\Concerns;

use InvalidArgumentException;

/**
 * Represents the state and result of an action.
 *
 * @template TResultValue
 */
interface Result
{
    /**
     * Get the current state of the action, optionally provide a new state.
     *
     * @throws InvalidArgumentException When the new state transition is invalid for the current state.
     */
    public function state(?State $state = null): State;

    /**
     * Get the status code of the action.
     */
    public function status(): ?int;

    /**
     * Get the resulting value of the action.
     *
     * @return TResultValue|null
     */
    public function value(): mixed;
}
