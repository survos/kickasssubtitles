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

use App\Models\Collections\TaskCollection;
use App\Models\Task;
use Exception;
use Illuminate\Support\Collection;
use KickAssSubtitles\Movie\ImageInterface;
use KickAssSubtitles\Movie\MovieInterface;
use KickAssSubtitles\Processor\TaskCollectionInterface;
use KickAssSubtitles\Processor\TaskDownloaderInterface;
use KickAssSubtitles\Processor\TaskInterface;
use KickAssSubtitles\Processor\TaskStatus;
use KickAssSubtitles\Processor\TaskType;
use KickAssSubtitles\Storage\HasStorageInterface;
use KickAssSubtitles\Support\DownloadableInterface;
use function Safe\sprintf;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;
use ZipStream\Option\Archive as ArchiveOptions;
use ZipStream\ZipStream;

/**
 * Class TaskDownloader.
 */
class TaskDownloader implements TaskDownloaderInterface
{
    /**
     * {@inheritdoc}
     */
    public function downloadTask(
        TaskInterface $task,
        ?string $filename = null
    ): StreamedResponse {
        /** @var DownloadableInterface $downloadableTask */
        $downloadableTask = $task;
        if (!$downloadableTask->isDownloadable()) {
            throw new Exception($downloadableTask::ERR_NOT_DOWNLOADABLE);
        }

        /** @var Task $task */
        $task = $downloadableTask;
        $self = $this;
        $filename = (null !== $filename) ?
            $filename :
            sprintf('%s.zip', $task->getRouteKey())
        ;

        $response = new StreamedResponse(function () use ($self, $task, $filename) {
            $options = new ArchiveOptions();
            $options->setSendHttpHeaders(true);
            $options->setContentType('application/octet-stream');
            $options->setZeroHeader(true);

            $zip = new ZipStream($filename, $options);
            switch ($task->getType()->getValue()) {
                case TaskType::SUBTITLE_CONVERSION:
                    $self->addSubtitleConversionTaskToZip($task, $zip);

                    break;
                case TaskType::SUBTITLE_SEARCH:
                    $self->addSubtitleSearchTaskToZip($task, $zip);

                    break;
                default:
                    throw new Exception(static::ERR_UNSUPPORTED_TASK_TYPE);
                    break;
            }
            $zip->finish();
        });

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function downloadTaskCollection(
        TaskCollectionInterface $tasks,
        ?string $filename = null
    ): StreamedResponse {
        /** @var TaskCollection $tasksCollection */
        $tasksCollection = $tasks;
        if ((0 === $tasksCollection->count()) || !$tasksCollection->isDownloadable()) {
            throw new Exception($tasksCollection::ERR_NOT_DOWNLOADABLE);
        }

        $self = $this;
        $filename = (null !== $filename) ?
            $filename :
            sprintf('%s.zip', uniqid())
        ;

        $response = new StreamedResponse(function () use ($self, $tasks, $filename) {
            $options = new ArchiveOptions();
            $options->setSendHttpHeaders(true);
            $options->setContentType('application/octet-stream');
            $options->setZeroHeader(true);

            $zip = new ZipStream($filename, $options);
            /** @var Collection $completedTasks */
            $completedTasks = $tasks->filterByStatus(TaskStatus::COMPLETED());
            foreach ($completedTasks as $task) {
                /* @var Task $task */
                switch ($task->getType()->getValue()) {
                    case TaskType::SUBTITLE_CONVERSION:
                        $self->addSubtitleConversionTaskToZip($task, $zip, $task->getRouteKey());

                        break;
                    case TaskType::SUBTITLE_SEARCH:
                        $self->addSubtitleSearchTaskToZip($task, $zip, $task->getRouteKey());

                        break;
                    default:
                        throw new Exception(static::ERR_UNSUPPORTED_TASK_TYPE);
                        break;
                }
            }
            $zip->finish();
        });

        return $response;
    }

    /**
     * @throws Throwable
     */
    protected function addSubtitleConversionTaskToZip(
        TaskInterface $task,
        ZipStream $zip,
        string $location = ''
    ) {
        if (!empty($location)) {
            $location = $location.'/';
        }

        /** @var HasStorageInterface $taskWithStorage */
        $taskWithStorage = $task;
        $storage = $taskWithStorage->getStorage();
        $name = $location.$storage->getFile(
            $task::STORAGE_INPUT,
            \PATHINFO_BASENAME
        );
        $path = $storage->getFile($task::STORAGE_INPUT);
        $zip->addFileFromPath($name, $path);

        /** @var Collection $children */
        $children = $task->getChildren()->filterByStatus(TaskStatus::COMPLETED());

        foreach ($children as $childTask) {
            /** @var HasStorageInterface $childTask */
            $storage = $childTask->getStorage();
            /** @var TaskInterface $childTask */
            $name = sprintf(
                '%s/%s',
                $childTask->getProcessorName()->getShortNameSnakeCase(),
                $storage->getFile(
                    $childTask::STORAGE_OUTPUT,
                    \PATHINFO_BASENAME
                )
            );
            $path = $storage->getFile($childTask::STORAGE_OUTPUT);
            $zip->addFileFromPath($location.$name, $path);
        }
    }

    /**
     * @throws Throwable
     */
    protected function addSubtitleSearchTaskToZip(
        TaskInterface $task,
        ZipStream $zip,
        string $location = ''
    ) {
        if (!empty($location)) {
            $location = $location.'/';
        }

        $movie = null;
        $image = null;
        foreach ($task->getResults() as $result) {
            if ($result instanceof MovieInterface) {
                $movie = $result;
            }
            if ($result instanceof ImageInterface) {
                $image = $result;
            }
        }

        /** @var Collection $children */
        $children = $task->getChildren()->filterByStatus(TaskStatus::COMPLETED());

        foreach ($children as $childTask) {
            /** @var TaskInterface $childTask */
            $names = [];
            $counter = 2;
            $imageAdded = false;
            foreach ($childTask->getResults() as $subtitle) {
                $folder = $childTask->getProcessorName()->getShortNameSnakeCase();
                if ($movie) {
                    $folder .= '/'.$movie->getFolder();
                }
                $name = sprintf(
                    '%s/%s',
                    $folder,
                    $childTask->getOptions()->getFilename(\PATHINFO_FILENAME).'.'.$subtitle->getFormat()->getExtensions()[0]
                );
                if (\in_array($name, $names, true)) {
                    $name = sprintf(
                        '%s/%s',
                        $folder,
                        $childTask->getOptions()->getFilename(\PATHINFO_FILENAME).'.'.__('messages.version').$counter.'.'.$subtitle->getFormat()->getExtensions()[0]
                    );
                    ++$counter;
                }
                $names[] = $name;
                $path = $subtitle->getFile();
                $zip->addFileFromPath($location.$name, $path);
                if ($image && !$imageAdded) {
                    $imageName = sprintf(
                        '%s/%s',
                        $folder,
                        $childTask->getOptions()->getFilename(\PATHINFO_FILENAME).'.'.$image->getFile(\PATHINFO_EXTENSION)
                    );
                    $zip->addFileFromPath($location.$imageName, $image->getFile());
                    $imageAdded = true;
                }
            }
        }
    }
}
