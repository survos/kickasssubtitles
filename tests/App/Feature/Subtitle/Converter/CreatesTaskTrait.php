<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace Tests\App\Feature\Subtitle\Converter;

use KickAssSubtitles\Processor\TaskInterface;
use KickAssSubtitles\Processor\TaskRepositoryInterface;
use KickAssSubtitles\Processor\TaskType;
use KickAssSubtitles\Subtitle\SubtitleConversionOptions;
use KickAssSubtitles\Subtitle\SubtitleFormat;
use KickAssSubtitles\Subtitle\SubtitleInterface;
use function Safe\file_put_contents;
use function Safe\unlink;
use Throwable;

/**
 * Class CreatesTaskTrait.
 */
trait CreatesTaskTrait
{
    /**
     * @var string
     */
    public static $srt = "1\n00:01:02,900 --> 00:01:06,180\nLOS ANGELES, ROK 2029";

    /**
     * @var string
     */
    public static $sub = "00:01:02.90,00:01:06.18\nLOS ANGELES, ROK 2029";

    /**
     * @var TaskInterface
     */
    protected $task;

    /**
     * @throws Throwable
     */
    protected function tearDown(): void
    {
        $this->task->tearDownStorage();
        parent::tearDown(); // THIS CALL MUST BE LAST
    }

    /**
     * @param string         $contents
     * @param SubtitleFormat $inputFormat
     * @param SubtitleFormat $outptFormat
     *
     * @return TaskInterface
     *
     * @throws Throwable
     */
    protected function createTask(
        string $contents,
        SubtitleFormat $inputFormat,
        SubtitleFormat $outptFormat
    ): TaskInterface {
        $file = \sys_get_temp_dir().DIRECTORY_SEPARATOR.'input.'.$inputFormat->getExtensions()[0];
        file_put_contents($file, $contents);

        /** @var TaskRepositoryInterface $taskRepository */
        $taskRepository = $this->app->make(TaskRepositoryInterface::class);

        $options = new SubtitleConversionOptions([
            SubtitleConversionOptions::FILE => $file,
            SubtitleInterface::FORMAT => $outptFormat->getValue(),
        ]);

        $task = $taskRepository->create(
            $options,
            TaskType::SUBTITLE_CONVERSION()
        );

        unlink($file);

        return $task;
    }
}
