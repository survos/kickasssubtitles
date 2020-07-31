<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace Tests\App\Feature\Movie\Provider;

use KickAssSubtitles\Movie\MovieInterface;
use KickAssSubtitles\Movie\MovieSearchOptions;
use KickAssSubtitles\Processor\TaskInterface;
use KickAssSubtitles\Processor\TaskRepository;
use KickAssSubtitles\Processor\TaskRepositoryInterface;
use KickAssSubtitles\Processor\TaskType;
use Throwable;

/**
 * Trait CreatesTaskTrait.
 */
trait CreatesTaskTrait
{
    /**
     * @var string
     */
    public static $movieTitle = 'Showgirls';

    /**
     * @var string
     */
    public static $movieImdbId = 'tt0114436';

    /**
     * @var string
     */
    public static $movieYear = '1995';

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
     * @throws Throwable
     */
    protected function createTask(): TaskInterface
    {
        /** @var TaskRepositoryInterface $taskRepository */
        $taskRepository = $this->app->make(TaskRepository::class);

        $task = $taskRepository->create(new MovieSearchOptions([
            MovieInterface::IMDB_ID => static::$movieImdbId,
        ]), TaskType::MOVIE_SEARCH());

        return $task;
    }
}
