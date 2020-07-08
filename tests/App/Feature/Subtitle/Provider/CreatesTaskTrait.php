<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace Tests\App\Feature\Subtitle\Provider;

use KickAssSubtitles\Language\Language;
use KickAssSubtitles\Processor\TaskInterface;
use KickAssSubtitles\Processor\TaskRepositoryInterface;
use KickAssSubtitles\Processor\TaskType;
use KickAssSubtitles\Subtitle\SubtitleInterface;
use KickAssSubtitles\Subtitle\SubtitleProvider;
use KickAssSubtitles\Subtitle\SubtitleSearchOptions;
use Throwable;

/**
 * Trait CreatesTaskTrait.
 */
trait CreatesTaskTrait
{
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
     * @return TaskInterface
     *
     * @throws Throwable
     */
    protected function createTask(): TaskInterface
    {
        /** @var TaskRepositoryInterface $taskRepository */
        $taskRepository = $this->app->make(TaskRepositoryInterface::class);

        $options = new SubtitleSearchOptions([
            SubtitleSearchOptions::FILENAME => 'Showgirls (1995).mp4',
            SubtitleSearchOptions::FILESIZE => 2094235131,
            SubtitleInterface::LANGUAGE => Language::PL,
            SubtitleProvider::OPENSUBTITLES()->getHashStorageField() => '163ce22b6261f50a',
            SubtitleProvider::NAPIPROJEKT()->getHashStorageField() => 'b7d32bdb4ad75e00178259ad4c11b9a1',
        ]);

        $task = $taskRepository->create(
            $options,
            TaskType::SUBTITLE_SEARCH()
        );

        return $task;
    }
}
