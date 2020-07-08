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

use Illuminate\Support\Collection;
use InvalidArgumentException;

/**
 * Trait TaskCollectionTrait.
 */
trait TaskCollectionTrait
{
    /**
     * {@inheritdoc}
     */
    public function containsTask(TaskInterface $task): bool
    {
        return $this->contains(function ($t, $key) use ($task) {
            /* @var TaskInterface $t */
            return $t->getIdentifier() === $task->getIdentifier();
        });
    }

    /**
     * {@inheritdoc}
     */
    public function addTask(TaskInterface $task): TaskCollectionInterface
    {
        if ($this->containsTask($task)) {
            throw new InvalidArgumentException(TaskCollectionInterface::ERR_TASK_ALREDY_ADDED);
        }

        $this->push($task);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function filterByStatus(TaskStatus $status): TaskCollectionInterface
    {
        return $this->filter(function ($task) use ($status) {
            /* @var TaskInterface $task */
            return $task->getStatus()->equals($status);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function isInStatus(TaskStatus $status): bool
    {
        /** @var Collection $filtered */
        $filtered = $this->filterByStatus($status);

        return $this->count() === $filtered->count();
    }

    /**
     * {@inheritdoc}
     */
    public function isProcessed(): bool
    {
        foreach ($this as $task) {
            /** @var TaskInterface $task */
            if (!$task->isProcessed()) {
                return false;
            }
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown(): void
    {
        $this->each(function ($task) {
            /* @var TaskInterface $task */
            $task->getStorage()->tearDown();
        });
    }
}
