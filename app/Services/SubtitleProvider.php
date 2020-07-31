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
use KickAssSubtitles\Processor\BulkProcessor;
use KickAssSubtitles\Processor\TaskInterface;
use KickAssSubtitles\Processor\TaskRepositoryInterface;
use KickAssSubtitles\Processor\TaskStatus;
use KickAssSubtitles\Subtitle\SubtitleInterface;
use KickAssSubtitles\Subtitle\SubtitleSearchOptions;

/**
 * Class SubtitleSearcher.
 */
class SubtitleProvider extends BulkProcessor
{
    /**
     * @var MovieProvider
     */
    protected $movieProvider;

    /**
     * @var SubtitleConverter
     */
    protected $subtitleConverter;

    public function __construct(
        array $processors,
        TaskRepositoryInterface $taskRepository,
        MovieProvider $movieProvider,
        SubtitleConverter $subtitleConverter
    ) {
        parent::__construct($processors, $taskRepository);
        $this->movieProvider = $movieProvider;
        $this->subtitleConverter = $subtitleConverter;
    }

    /**
     * {@inheritdoc}
     */
    protected function afterProcessTask(TaskInterface $task): void
    {
        $imdbId = $this->getImdbId($task);

        if ($imdbId) {
            $results = $this->movieProvider->search($imdbId);
            foreach ($results as $result) {
                $task->addResult($result);
            }
        }

        // if needed, convert subtitles to the desired format & encoding
        foreach ($task->getChildren()->filterByStatus(TaskStatus::COMPLETED()) as $childTask) {
            /** @var TaskInterface $childTask */
            $results = $childTask->getResults();
            /** @var SubtitleSearchOptions $options */
            $options = $childTask->getOptions();
            foreach ($results as $result) {
                if (!($result instanceof SubtitleInterface)) {
                    continue;
                }
                $subtitle = $result;
                if (!$options->needsConversion($subtitle)) {
                    continue;
                }
                $convertedSubtitles = $this->subtitleConverter->convert(
                    $subtitle,
                    $options->getFormat(),
                    $options->getEncoding()
                );
                foreach ($convertedSubtitles as $convertedSubtitle) {
                    $childTask->prependResult($convertedSubtitle);
                }
            }
        }
    }

    protected function getImdbId(TaskInterface $task): ?ImdbId
    {
        foreach ($task->getChildren()->filterByStatus(TaskStatus::COMPLETED()) as $childTask) {
            foreach ($childTask->getResults() as $result) {
                if (($result instanceof SubtitleInterface) &&
                    (null !== $result->getImdbId())
                ) {
                    $imdbId = $result->getImdbId();

                    return $imdbId;
                }
            }
        }

        return null;
    }
}
