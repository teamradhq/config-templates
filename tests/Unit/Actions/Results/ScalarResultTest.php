<?php

declare(strict_types=1);

namespace TeamRadHQ\ConfigTemplates\Tests\Unit\Actions\Results;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use TeamRadHQ\ConfigTemplates\Actions\Concerns\State;
use TeamRadHQ\ConfigTemplates\Actions\Results\ScalarResult;

#[CoversClass(ScalarResult::class)]
final class ScalarResultTest extends TestCase
{
    public function test_making_scalar_with_value(): void
    {
        self::assertSame('foo', ScalarResult::string('foo')->value());
        self::assertTrue(ScalarResult::bool(true)->value());
        self::assertSame(42, ScalarResult::int(42)->value());
        self::assertSame(42.0, ScalarResult::float(42.0)->value());
    }

    public function test_making_scalar_without_value(): void
    {
        self::assertNull(ScalarResult::string()->value());
        self::assertNull(ScalarResult::bool()->value());
        self::assertNull(ScalarResult::int()->value());
        self::assertNull(ScalarResult::float()->value());
    }

    public function test_setting_another_scalar_value(): void
    {
        /** @phpstan-ignore-next-line argument.type */
        self::assertNull(ScalarResult::string(42)->value());

        /** @phpstan-ignore-next-line argument.type */
        self::assertNull(ScalarResult::bool(42)->value());

        /** @phpstan-ignore-next-line argument.type */
        self::assertNull(ScalarResult::int(42.0)->value());

        /** @phpstan-ignore-next-line argument.type */
        self::assertNull(ScalarResult::float(42)->value());
    }

    public function test_scalar_value_is_not_mutable(): void
    {
        $scalarResult = ScalarResult::string('foo');
        $scalarResult->set('bar');

        self::assertSame('foo', $scalarResult->value());
    }

    public function test_status_is_set_on_state_finished(): void
    {
        $scalarResult = ScalarResult::string('foo');
        $initialStatus = $scalarResult->status();
        $scalarResult->state(State::Success);

        self::assertNull($initialStatus);
        self::assertSame(State::Success, $scalarResult->state());
        self::assertSame(0, $scalarResult->status());
    }
}
