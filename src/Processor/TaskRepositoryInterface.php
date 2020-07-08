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
use KickAssSubtitles\Support\RepositoryInterface;
use Throwable;

/**
 * Interface TaskRepositoryInterface.
 */
interface TaskRepositoryInterface extends RepositoryInterface
{
    /**
     * @param TaskOptions $options
     * @param TaskType    $type
     *
     * @return TaskInterface
     *
     * @throws Throwable
     */
    public function create(TaskOptions $options, TaskType $type): TaskInterface;

    /**
     * @param TaskInterface $task
     *
     * @return TaskInterface
     *
     * @throws Throwable
     */
    public function clone(TaskInterface $task): TaskInterface;

    /**
     * @param TaskType   $type
     * @param TaskStatus $status
     *
     * @return int
     *
     * @throws Throwable
     */
    public function findCount(TaskType $type, TaskStatus $status): int;

    /**
     * @param string $identifier
     *
     * @return TaskInterface
     *
     * @throws Throwable
     */
    public function findByIdentifierOrFail(string $identifier): TaskInterface;

    /**
     * @param DateTime $cutOffDate
     *
     * @throws Throwable
     */
    public function deleteTasksOlderThan(DateTime $cutOffDate): void;
}
