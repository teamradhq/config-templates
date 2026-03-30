<?php

declare(strict_types=1);

namespace TeamRadHQ\ConfigTemplates\Tests\Unit;

use Composer\Factory;
use Generator;
use Mockery;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use TeamRadHQ\ConfigTemplates\ConfigTemplateException;
use TeamRadHQ\ConfigTemplates\Configuration;

#[CoversClass(Configuration::class)]
final class ConfigurationTest extends TestCase
{
    /**
     * @return Generator<string, array{string, callable(Configuration): string}>
     */
    public static function provideErrors(): Generator
    {
        yield 'composer.json not found' => [
            'Could not find composer.json',
            fn (Configuration $configuration): string => $configuration->composerFile(),
        ];

        yield 'project path cannot be determined' => [
            'Could not determine project directory',
            fn (Configuration $configuration): string => $configuration->projectDir(),
        ];
    }

    /**
     * @param callable(Configuration): string $shouldThrow
     */
    #[TestDox('An error should occur when $_dataName.')]
    #[DataProvider('provideErrors')]
    public function test_error_occurs_when_composer_file_is_not_found(string $message, callable $shouldThrow): void
    {
        $mock = Mockery::mock(Factory::class);
        $mock->expects('getComposerFile')->andReturns('invalid-path');
        $packageConfiguration = new Configuration($mock);

        $this->expectExceptionMessage($message);
        $this->expectException(ConfigTemplateException::class);

        $shouldThrow($packageConfiguration);
    }

    /**
     * @throws ConfigTemplateException
     */
    #[TestDox('The project directory should be relative to main composer.json file.')]
    public function test_project_directory_is_correct(): void
    {
        $expected = dirname(__DIR__, 2);
        $packageConfiguration = new Configuration;

        self::assertSame($expected, $packageConfiguration->packageDir());
        self::assertSame($expected, $packageConfiguration->projectDir());
        self::assertSame(
            $expected . DIRECTORY_SEPARATOR . 'composer.json',
            $packageConfiguration->composerFile()
        );
    }
}
