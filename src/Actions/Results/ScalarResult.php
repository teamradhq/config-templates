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
    /**
     * @var TValue|null
     */
    private string|int|float|bool|null $value = null;

    private State $state = State::Pending;

    /**
     * @param ScalarType $type
     * @param TValue|null $value
     */
    private function __construct(
        private readonly ScalarType $type,
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
        /** @var self<string> */
        return new self(ScalarType::String, $value);
    }

    /**
     * Define an integer scalar result.
     *
     * @param int|null $value
     * @return self<int>
     */
    public static function int(mixed $value = null): self
    {
        /** @var self<int> */
        return new self(ScalarType::Integer, $value);
    }

    /**
     * Define a floating point scalar result.
     *
     * @param float|null $value
     * @return self<float>
     */
    public static function float(mixed $value = null): self
    {
        /** @var self<float> */
        return new self(ScalarType::Float, $value);
    }

    /**
     * Define a boolean scalar result.
     *
     * @param bool|null $value
     * @return self<bool>
     */
    public static function bool(mixed $value = null): self
    {
        /** @var self<bool> */
        return new self(ScalarType::Boolean, $value);
    }

    /**
     * {@inheritDoc}
     */
    public function state(?State $state = null): State
    {
        if ($state !== null) {
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
        return $this->value;
    }

    /**
     * {@inheritDoc}
     *
     * @param TValue|null $value
     */
    public function set(mixed $value = null): void
    {
        if ($this->value === null && gettype($value) === $this->type->value) {
            $this->value = $value;
        }
    }
}
