<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace App\Http\Controllers;

use App\Models\TaskFilters;
use Illuminate\Pagination\LengthAwarePaginator;
use KickAssSubtitles\Processor\TaskCollectionInterface;
use KickAssSubtitles\Processor\TaskDownloaderInterface;
use KickAssSubtitles\Processor\TaskInterface;
use KickAssSubtitles\Processor\TaskRepositoryInterface;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

/**
 * Class DownloadsController.
 */
class DownloadsController extends AbstractController
{
    /**
     * @var TaskRepositoryInterface
     */
    protected $taskRepository;

    /**
     * @var TaskDownloaderInterface
     */
    protected $taskDownloader;

    /**
     * @param TaskRepositoryInterface $taskRepository
     * @param TaskDownloaderInterface $taskDownloader
     */
    public function __construct(
        TaskRepositoryInterface $taskRepository,
        TaskDownloaderInterface $taskDownloader
    ) {
        $this->taskRepository = $taskRepository;
        $this->taskDownloader = $taskDownloader;
    }

    /**
     * @param int $id
     *
     * @return StreamedResponse
     *
     * @throws Throwable
     */
    public function downloadTask(int $id): StreamedResponse
    {
        /** @var TaskInterface $task */
        $task = $this->taskRepository->findByIdOrFail($id);

        return $this->taskDownloader->downloadTask($task);
    }

    /**
     * @param string $group
     *
     * @return StreamedResponse
     *
     * @throws Throwable
     */
    public function downloadTasksGroup(string $group): StreamedResponse
    {
        /** @var TaskFilters $filters */
        $filters = new TaskFilters();
        $filters
            ->setGroup($group)
            ->setLimit(1000)
        ;

        /** @var LengthAwarePaginator $tasks */
        $tasks = $this->taskRepository->findAll($filters);

        /** @var TaskCollectionInterface $collection */
        $collection = $tasks->getCollection();

        return $this->taskDownloader->downloadTaskCollection(
            $collection,
            $group.'.zip'
        );
    }
}
