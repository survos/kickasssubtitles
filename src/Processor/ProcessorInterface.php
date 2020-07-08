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
 * Interface ProcessorInterface.
 */
interface ProcessorInterface
{
    const ERR_INVALID_TASK_TYPE = 'Invalid task type';

    const ERR_PROCESSOR_ALREADY_ADDED = 'Processor already added';

    /**
     * @return ProcessorName
     */
    public function getName(): ProcessorName;

    /**
     * @return TaskType
     */
    public function getSupportedTaskType(): TaskType;

    /**
     * @param TaskInterface $task
     *
     * @throws Throwable
     */
    public function processOne(TaskInterface $task): void;

    /**
     * @param TaskCollectionInterface&iterable $tasks
     *
     * @throws Throwable
     */
    public function processMany(TaskCollectionInterface $tasks): void;
}
