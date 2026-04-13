<?php

declare(strict_types=1);

namespace TeamRadHQ\ConfigTemplates\Actions\Results;

use TeamRadHQ\ConfigTemplates\Actions\Concerns\Result;
use TeamRadHQ\ConfigTemplates\Actions\Concerns\ScalarType;
use TeamRadHQ\ConfigTemplates\Actions\Concerns\State;

/**
 * @template TValue of scalar
 *
 * @implements Result<TValue>
 */
final class ScalarResult implements Result
{
    private string|int|float|bool|null $value = null;

    private State $state = State::Pending;

    private function __construct(
        private readonly ScalarType $scalarType,
        string|int|float|bool|null $value = null,
    ) {
        $this->set($value);
    }

    /**
     * Define a string scalar result.
     *
     * @param string|null $value
     * @return self<string>
     */
    public static function string(mixed $value = null): self
    {
        /** @var self<string> $self */
        $self = new self(ScalarType::String, $value);

        return $self;
    }

    /**
     * Define an integer scalar result.
     *
     * @param int|null $value
     * @return self<int>
     */
    public static function int(mixed $value = null): self
    {
        /** @var self<int> $self */
        $self = new self(ScalarType::Integer, $value);

        return $self;
    }

    /**
     * Define a floating point scalar result.
     *
     * @param float|null $value
     * @return self<float>
     */
    public static function float(mixed $value = null): self
    {
        /** @var self<float> $self */
        $self = new self(ScalarType::Float, $value);

        return $self;
    }

    /**
     * Define a boolean scalar result.
     *
     * @param bool|null $value
     * @return self<bool>
     */
    public static function bool(mixed $value = null): self
    {
        /** @var self<bool> $self */
        $self = new self(ScalarType::Boolean, $value);

        return $self;
    }

    /**
     * {@inheritDoc}
     */
    public function state(?State $state = null): State
    {
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

        return 0;
    }

    /**
     * {@inheritDoc}
     *
     * @return TValue|null
     */
    public function value(): mixed
    {
        $value = $this->value;

        if (is_null($value)) {
            return null;
        }

        /** @var TValue $value */
        return $value;
    }

    /**
     * {@inheritDoc}
     *
     * @param scalar|null $value
     */
    public function set(mixed $value = null): void
    {
        if ($this->value === null && gettype($value) === $this->scalarType->value) {
            $this->value = $value;
        }
    }
}
