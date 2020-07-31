<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace Tests\Processor;

use KickAssSubtitles\Processor\Task;
use KickAssSubtitles\Processor\TaskInterface;
use KickAssSubtitles\Processor\TaskOptions;
use KickAssSubtitles\Processor\TaskRepository;
use KickAssSubtitles\Processor\TaskType;
use Throwable;

/**
 * Trait CreatesTaskTrait.
 */
trait CreatesTaskTrait
{
    /**
     * @throws Throwable
     */
    protected function createTask(?TaskType $type = null): TaskInterface
    {
        if (null === $type) {
            $type = TaskType::DEFAULT();
        }

        $repository = new TaskRepository(Task::class);

        return $repository->create(new TaskOptions([
            'text' => 'Lorem Ipsum',
        ]), $type);
    }
}
