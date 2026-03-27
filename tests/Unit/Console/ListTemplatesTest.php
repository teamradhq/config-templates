<?php

declare(strict_types=1);

namespace TeamRadHQ\ConfigTemplates\Tests\Unit\Console;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;
use TeamRadHQ\ConfigTemplates\Console\ListTemplates;

#[CoversClass(ListTemplates::class)]
final class ListTemplatesTest extends TestCase
{
    /**
     * @return string[]
     */
    public static function expectedTable(): array
    {
        return [
            str_pad('composer', 12) . ' Work with composer json files.',
            str_pad('phpcs', 12) . ' Work with phpcs xml files.',
            str_pad('phpstan', 12) . ' Work with phpstan neon files.',
            str_pad('phpunit', 12) . ' Work with phpunit xml files.',
            str_pad('pint', 12) . ' Work with pint json files.',
        ];
    }

    #[TestDox('it should provide a list of all available configurations.')]
    public function test_it_can_list_templates(): void
    {
        $commandTester = new CommandTester(new ListTemplates);

        $commandTester->execute([]);

        self::assertStringContainsString('Available config types', $commandTester->getDisplay());

        foreach (self::expectedTable() as $expected) {
            self::assertStringContainsString($expected, $commandTester->getDisplay());
        }

        self::assertSame(0, $commandTester->getStatusCode());
    }
}
