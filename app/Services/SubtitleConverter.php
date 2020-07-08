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

use KickAssSubtitles\Encoding\Encoding;
use KickAssSubtitles\Processor\BulkProcessor;
use KickAssSubtitles\Processor\TaskRepositoryInterface;
use KickAssSubtitles\Processor\TaskStatus;
use KickAssSubtitles\Processor\TaskType;
use KickAssSubtitles\Subtitle\SubtitleConversionOptions;
use KickAssSubtitles\Subtitle\SubtitleFormat;
use KickAssSubtitles\Subtitle\SubtitleInterface;
use KickAssSubtitles\Subtitle\SubtitleRepositoryInterface;
use function Safe\filesize;
use Throwable;

/**
 * Class Converter.
 */
class SubtitleConverter extends BulkProcessor
{
    /**
     * @var SubtitleRepositoryInterface
     */
    protected $subtitleRepository;

    /**
     * @param array                       $processors
     * @param TaskRepositoryInterface     $taskRepository
     * @param SubtitleRepositoryInterface $subtitleRepository
     */
    public function __construct(
        array $processors,
        TaskRepositoryInterface $taskRepository,
        SubtitleRepositoryInterface $subtitleRepository
    ) {
        parent::__construct($processors, $taskRepository);
        $this->subtitleRepository = $subtitleRepository;
    }

    /**
     * @return SubtitleRepositoryInterface
     */
    public function getSubtitleRepository(): SubtitleRepositoryInterface
    {
        return $this->subtitleRepository;
    }

    /**
     * @param SubtitleInterface $subtitle
     * @param SubtitleFormat    $format
     * @param Encoding          $encoding
     *
     * @return SubtitleInterface[]
     *
     * @throws Throwable
     */
    public function convert(
        SubtitleInterface $subtitle,
        SubtitleFormat $format,
        Encoding $encoding
    ): array {
        $options = new SubtitleConversionOptions([
            SubtitleInterface::FORMAT => $format->getValue(),
            SubtitleInterface::ENCODING => $encoding->getValue(),
            SubtitleInterface::LANGUAGE => $subtitle->getLanguage()->getValue(),
            SubtitleConversionOptions::INPUT_ENCODING => $subtitle->getEncoding()->getValue(),
            SubtitleConversionOptions::FILENAME => $subtitle->getFile(PATHINFO_BASENAME),
            SubtitleConversionOptions::FILESIZE => filesize($subtitle->getFile()),
        ]);
        $options->setFile($subtitle->getFile());

        $task = $this->taskRepository->create($options, TaskType::SUBTITLE_CONVERSION());

        $this->processOne($task);

        if ($task->getStatus()->equals(TaskStatus::FAILED())) {
            return [];
        }

        $results = [];
        foreach ($task->getChildren()->filterByStatus(TaskStatus::COMPLETED()) as $childTask) {
            $convertedSubtitle = $this->subtitleRepository->createFromFile(
                $childTask->getStorage()->getFile($childTask::STORAGE_OUTPUT),
                $format,
                $subtitle->getLanguage(),
                $encoding,
                $subtitle->getImdbId(),
                $subtitle->getProvider()
            );
            $results[] = $convertedSubtitle;
        }

        $this->taskRepository->delete($task);

        return $results;
    }
}
