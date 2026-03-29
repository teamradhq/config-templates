<?php

declare(strict_types=1);

namespace TeamRadHQ\ConfigTemplates\Actions\Concerns;

/**
 * A class that represents an action which is run and has a result.
 *
 * @template TResultValue
 */
interface Action
{
    /**
     * Perform the action.
     */
    public function run(): int;

    /**
     * Get the current result of the action.
     *
     * @return Result<TResultValue>
     */
    public function result(): Result;
}
