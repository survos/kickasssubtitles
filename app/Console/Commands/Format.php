<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace App\Console\Commands;

use App\Console\Command;
use App\Enums\Filesystem;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Filesystem\FilesystemManager;
use KickAssSubtitles\Support\Str;
use function Safe\scandir;
use function Safe\unlink;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

/**
 * Class Format.
 */
class Format extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:format';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Format database & storage folders';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        parent::handle();

        if (!$this->confirm('Are you sure?')) {
            return;
        }

        $this->stopSupervisor();

        $this->call('migrate:fresh');

        foreach (Filesystem::values() as $filesystem) {
            $this->formatDisk($filesystem);
        }

        $this->clearLogs();

        $this->startSupervisor();
    }

    protected function formatDisk(Filesystem $filesystem): void
    {
        $this->info('Formatting disk: '.$filesystem->getValue());
        $storage = app(FilesystemManager::class);
        /** @var FilesystemAdapter $disk */
        $disk = $storage->disk($filesystem->getValue());
        foreach ($disk->directories() as $directory) {
            $disk->deleteDirectory($directory);
            $this->info('Directory deleted: '.$directory);
        }
    }

    protected function clearLogs(): void
    {
        $logsPath = storage_path('logs');
        foreach (array_diff(scandir($logsPath), ['..', '.']) as $file) {
            if (!Str::endsWith($file, '.log')) {
                continue;
            }
            $filePath = $logsPath.\DIRECTORY_SEPARATOR.$file;
            unlink($filePath);
            $this->info('File deleted: '.$filePath);
        }
    }

    protected function stopSupervisor(): void
    {
        $this->callSupervisor(['stop', 'app:']);
    }

    protected function startSupervisor(): void
    {
        $this->callSupervisor(['start', 'app:']);
    }

    protected function callSupervisor(array $command): void
    {
        $process = new Process(array_merge([
            'supervisorctl',
        ], $command));

        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException();
        }

        $output = explode(PHP_EOL, $process->getOutput());
        foreach ($output as $line) {
            $this->info($line);
        }
    }
}
