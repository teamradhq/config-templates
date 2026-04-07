<?php

declare(strict_types=1);

namespace TeamRadHQ\ConfigTemplates\Tests\Unit\Data;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use TeamRadHQ\ConfigTemplates\ConfigTemplateException;
use TeamRadHQ\ConfigTemplates\Data\ConfigFile;

#[CoversClass(ConfigFile::class)]
final class ConfigFileTest extends TestCase
{
    public static string $path = __DIR__ . '/config.txt';

    public static string $templatePath = __DIR__ . '/templates.txt';

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        file_put_contents(self::$templatePath, 'template');
        file_put_contents(self::$path, 'config');
    }

    public static function tearDownAfterClass(): void
    {
        unlink(self::$templatePath);
        unlink(self::$path);

        parent::tearDownAfterClass();
    }

    /**
     * @throws ConfigTemplateException
     */
    public function test_it_can_be_created(): void
    {
        $configFile = new ConfigFile('test', self::$path, self::$templatePath);

        self::assertSame('config', $configFile->info()->getContents());
        self::assertSame('template', $configFile->templateInfo()->getContents());
    }

    /**
     * @throws ConfigTemplateException
     */
    public function test_template_and_config_cannot_be_the_same_file(): void
    {
        $this->expectExceptionMessage('Template and config cannot be the same file');
        $this->expectException(ConfigTemplateException::class);

        new ConfigFile('test', self::$path, self::$path);
    }
}
