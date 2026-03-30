<?php

declare(strict_types=1);

namespace TeamRadHQ\ConfigTemplates\Actions;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use Symfony\Component\Finder\SplFileInfo;
use TeamRadHQ\ConfigTemplates\Actions\Concerns\Action;
use TeamRadHQ\ConfigTemplates\Actions\Concerns\State;
use TeamRadHQ\ConfigTemplates\Actions\Results\FileInfoResult;
use TeamRadHQ\ConfigTemplates\ConfigTemplateException;
use TeamRadHQ\ConfigTemplates\Configuration;
use TeamRadHQ\ConfigTemplates\ConfiguresPackage;
use Throwable;

/**
 * @implements Action<SplFileInfo[]>
 */
final class InstallConfig implements Action
{
    private readonly string $templatePath;

    private readonly string $configPath;

    private readonly FileInfoResult $result;

    private int $status = Command::SUCCESS;

    /**
     * @throws ConfigTemplateException
     */
    public function __construct(
        string $type,
        private readonly ConfiguresPackage $configuresPackage = new Configuration,
        private readonly Filesystem $filesystem = new Filesystem,
    ) {
        if ($type !== 'phpstan') {
            throw new ConfigTemplateException('Invalid config type');
        }

        $this->templatePath = Path::join($this->configuresPackage->packageDir(), 'phpstan.neon');

        if (!$this->filesystem->exists($this->templatePath)) {
            throw new ConfigTemplateException('Could not find phpstan.neon.');
        }

        $this->configPath = Path::join($this->configuresPackage->projectDir(), 'phpstan.neon');

        if ($this->filesystem->exists($this->configPath)) {
            throw new ConfigTemplateException('Config file already exists.');
        }

        $this->result = new FileInfoResult(['template' => $this->templatePath, 'config' => $this->configPath]);
    }

    /**
     * @throws ConfigTemplateException
     */
    public function run(): int
    {
        try {
            $this->filesystem->copy($this->templatePath, $this->configPath);
        } catch (FileNotFoundException $exception) {
            $this->setFailed('Could not copy from source file.', $exception);
        } catch (IOException $exception) {
            $this->setFailed('Could not write to destination file.', $exception);
        }

        $this->result->state(State::Success);

        return $this->status;
    }

    /**
     * Provide the template and config file info.
     */
    public function result(): FileInfoResult
    {
        return $this->result;
    }

    /**
     * @throws ConfigTemplateException
     */
    private function setFailed(string $message, Throwable $throwable): never
    {
        $this->status = Command::FAILURE;
        $this->result->state(State::Failed);

        throw new ConfigTemplateException($message, 1, $throwable);
    }
}
