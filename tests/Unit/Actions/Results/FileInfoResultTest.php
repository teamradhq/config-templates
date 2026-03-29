<?php

declare(strict_types=1);

namespace TeamRadHQ\ConfigTemplates\Tests\Unit\Actions\Results;

use Generator;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\SplFileInfo;
use TeamRadHQ\ConfigTemplates\Actions\Concerns\State;
use TeamRadHQ\ConfigTemplates\Actions\Results\FileInfoResult;

#[CoversClass(FileInfoResult::class)]
#[UsesClass(State::class)]
final class FileInfoResultTest extends TestCase
{
    /**
     * @return Generator<string, array{State, int, ?SplFileInfo}>
     */
    public static function finishedResults(): Generator
    {
        yield 'successful' => [State::Success, 0, new SplFileInfo(__FILE__, '', '')];

        yield 'failed' => [State::Failed, 1, null];

        yield 'cancelled' => [State::Cancelled, 1, null];
    }

    /**
     * @return Generator<array{0: State}>
     */
    public static function finishedStates(): Generator
    {
        yield [State::Success];

        yield [State::Failed];

        yield [State::Cancelled];
    }

    #[TestDox('A pending result should be created with null status and value.')]
    public function test_it_can_be_created(): void
    {
        $result = new FileInfoResult(__FILE__);

        self::assertSame(State::Pending, $result->state());
        self::assertNull($result->status());
        self::assertNull($result->value());
    }

    #[TestDox('A $_dataName result should provide a status and value.')]
    #[DataProvider('finishedResults')]
    public function test_a_finished_result_has_a_value(State $state, int $status, ?SplFileInfo $value): void
    {
        $result = new FileInfoResult(__FILE__);
        $result->state($state);

        self::assertSame($state, $result->state());
        self::assertSame($status, $result->status());
        self::assertSame($value?->getFilename(), $result->value()?->getFilename());
    }

    #[TestDox('A $state result should not be able to transition to another state.')]
    #[DataProvider('finishedStates')]
    public function test_a_finished_result_should_not_change_state(State $state): void
    {
        $result = new FileInfoResult(__FILE__);
        $result->state($state);

        $this->expectExceptionMessage('Cannot transition to a new state when action is already finished.');
        $this->expectException(InvalidArgumentException::class);

        $result->state(State::Failed);
    }
}
