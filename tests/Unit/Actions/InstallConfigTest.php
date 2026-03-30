<?php

declare(strict_types=1);

namespace TeamRadHQ\ConfigTemplates\Tests\Unit\Actions;

use Generator;
use Mockery;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use TeamRadHQ\ConfigTemplates\Actions\InstallConfig;
use TeamRadHQ\ConfigTemplates\ConfigTemplateException;
use TeamRadHQ\ConfigTemplates\ConfiguresPackage;

#[CoversClass(InstallConfig::class)]
final class InstallConfigTest extends TestCase
{
    /**
     * @return Generator<string, array{string, callable(): InstallConfig}>
     */
    public static function provideErrors(): Generator
    {
        yield 'template type is invalid' => [
            'Invalid config type',
            fn (): InstallConfig => new InstallConfig('invalid'),
        ];

        yield 'template file is missing' => [
            'Could not find phpstan.neon.',
            function (): InstallConfig {
                $mock = Mockery::mock(Filesystem::class);
                $mock->allows('exists')
                    ->withArgs(fn (string $file): bool => str_ends_with($file, 'phpstan.neon'))
                    ->andReturns(false);

                return new InstallConfig('phpstan', filesystem: $mock);
            },
        ];

        yield 'config file already exists' => [
            'Config file already exists.',
            function (): InstallConfig {
                $mock = Mockery::mock(Filesystem::class);
                $mock->allows('exists')
                    ->andReturns(true);

                return new InstallConfig('phpstan', filesystem: $mock);
            },
        ];

        yield 'cannot copy from source file' => [
            'Could not copy from source file.',
            function (): InstallConfig {
                $mock = Mockery::mock(Filesystem::class);
                $mock->allows('exists')->andReturn(true, false);
                $mock->allows('copy')->andThrows(FileNotFoundException::class);

                return new InstallConfig('phpstan', filesystem: $mock);
            },
        ];

        yield 'cannot copy to target file' => [
            'Could not write to destination file.',
            function (): InstallConfig {
                $mock = Mockery::mock(Filesystem::class);
                $mock->allows('exists')->andReturn(true, false);
                $mock->allows('copy')
                    ->andThrows(IOException::class);

                return new InstallConfig('phpstan', filesystem: $mock);
            },
        ];
    }

    /**
     * @param callable(): InstallConfig $action
     *
     * @throws ConfigTemplateException
     */
    #[DataProvider('provideErrors')]
    public function test_errors_are_thrown(string $message, callable $action): void
    {
        $this->expectExceptionMessage($message);
        $this->expectException(ConfigTemplateException::class);

        $action()->run();
    }

    /**
     * @throws ConfigTemplateException
     */
    public function test_file_is_copied(): void
    {
        [$tmpdir, $source, $target] = self::setUpFiles();

        $mock = Mockery::mock(ConfiguresPackage::class);
        $mock->allows('packageDir')->andReturn($tmpdir);
        $mock->allows('projectDir')->andReturn(dirname($tmpdir));

        $installConfig = new InstallConfig('phpstan', $mock);
        $installConfig->run();

        self::assertFileExists($target);
        self::assertSame('testing', file_get_contents($target));
        self::assertCount(2, $installConfig->result()->value());

        self::removeFiles([$source, $target]);
    }

    /**
     * @return array{0: string, 1: string, 2: string}
     */
    private static function setUpFiles(): array
    {
        @mkdir($tmpdir = sys_get_temp_dir() . '/testing/template', recursive: true);
        file_put_contents($source = $tmpdir . '/phpstan.neon', 'testing');
        $target = dirname($tmpdir) . '/phpstan.neon';

        if (file_exists($target)) {
            unlink($target);
        }

        return [$tmpdir, $source, $target];
    }

    /**
     * @param string[] $files
     */
    private static function removeFiles(array $files): void
    {
        $filesystem = new Filesystem;
        $filesystem->remove($files);
    }
}
