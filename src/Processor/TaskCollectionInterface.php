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

use Throwable;

/**
 * Interface TaskCollectionInterface.
 */
interface TaskCollectionInterface
{
    const ERR_TASK_ALREDY_ADDED = 'Task already added';

    /**
     * @param TaskInterface $task
     *
     * @return bool
     */
    public function containsTask(TaskInterface $task): bool;

    /**
     * @param TaskInterface $task
     *
     * @return self
     */
    public function addTask(TaskInterface $task): self;

    /**
     * @param TaskStatus $status
     *
     * @return self
     */
    public function filterByStatus(TaskStatus $status): self;

    /**
     * @param TaskStatus $status
     *
     * @return bool
     */
    public function isInStatus(TaskStatus $status): bool;

    /**
     * @return bool
     */
    public function isProcessed(): bool;

    /**
     * @throws Throwable
     */
    public function tearDown(): void;
}
