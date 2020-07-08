<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace KickAssSubtitles\Processor;

use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

/**
 * Interface TaskDownloaderInterface.
 */
interface TaskDownloaderInterface
{
    const ERR_UNSUPPORTED_TASK_TYPE = 'Unsupported task type';

    /**
     * @param TaskInterface $task
     * @param string|null   $filename
     *
     * @return StreamedResponse
     *
     * @throws Throwable
     */
    public function downloadTask(
        TaskInterface $task,
        ?string $filename = null
    ): StreamedResponse;

    /**
     * @param TaskCollectionInterface $tasks
     * @param string|null             $filename
     *
     * @return StreamedResponse
     *
     * @throws Throwable
     */
    public function downloadTaskCollection(
        TaskCollectionInterface $tasks,
        ?string $filename = null
    ): StreamedResponse;
}
