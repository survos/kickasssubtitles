<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace App\Services;

use KickAssSubtitles\Movie\ImdbId;
use KickAssSubtitles\Movie\MovieSearchOptions;
use KickAssSubtitles\Processor\BulkProcessor;
use KickAssSubtitles\Processor\TaskStatus;
use KickAssSubtitles\Processor\TaskType;
use Throwable;

/**
 * Class SubtitleSearcher.
 */
class MovieProvider extends BulkProcessor
{
    /**
     * @param ImdbId $imdbId
     *
     * @return array
     *
     * @throws Throwable
     */
    public function search(ImdbId $imdbId): array
    {
        $options = new MovieSearchOptions([
            MovieSearchOptions::IMDB_ID => $imdbId->getValue(),
        ]);

        $task = $this->taskRepository->create(
            $options,
            TaskType::MOVIE_SEARCH()
        );

        $this->processOne($task);

        if ($task->getStatus()->equals(TaskStatus::FAILED())) {
            return [];
        }

        $results = [];
        foreach ($task->getChildren()->filterByStatus(TaskStatus::COMPLETED()) as $childTask) {
            foreach ($childTask->getResults() as $result) {
                $results[] = $result;
            }
        }

        return $results;
    }
}
