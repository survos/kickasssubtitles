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

use DateTime;
use KickAssSubtitles\Support\ModelInterface;
use KickAssSubtitles\Support\RepositoryInterface;
use Throwable;

/**
 * Interface TaskRepositoryInterface.
 */
interface TaskRepositoryInterface extends RepositoryInterface
{
    /**
     * @throws Throwable
     */
    public function create(TaskOptions $options, TaskType $type): TaskInterface;

    /**
     * @throws Throwable
     */
    public function clone(TaskInterface $task): TaskInterface;

    /**
     * @throws Throwable
     */
    public function findCount(TaskType $type, TaskStatus $status): int;

    /**
     * @throws Throwable
     */
    public function findByIdentifierOrFail(string $identifier): TaskInterface;

    /**
     * @throws Throwable
     */
    public function deleteTasksOlderThan(
        DateTime $cutOffDate,
        ?ModelInterface $user = null,
        int $limit = 20
    ): void;
}
