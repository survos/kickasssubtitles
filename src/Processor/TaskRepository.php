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
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use InvalidArgumentException;
use KickAssSubtitles\Support\Exception\NotImplementedException;
use KickAssSubtitles\Support\FiltersInterface;
use KickAssSubtitles\Support\ModelInterface;
use KickAssSubtitles\Support\Str;

/**
 * Class TaskRepository.
 */
class TaskRepository implements TaskRepositoryInterface
{
    /**
     * @var string
     */
    protected $taskClass;

    /**
     * @param string $taskClass
     */
    public function __construct(string $taskClass)
    {
        $this->taskClass = $taskClass;
    }

    /**
     * {@inheritdoc}
     */
    public function create(TaskOptions $options, TaskType $type): TaskInterface
    {
        $optionsClass = $type->getOptionsClassName();
        if (!$options instanceof $optionsClass) {
            throw new InvalidArgumentException(TaskInterface::ERR_INVALID_OPTIONS);
        }

        $taskClass = $this->taskClass;
        $task = new $taskClass();
        $task->setAttribute(TaskInterface::IDENTIFIER, Str::uuid());
        $task->setAttribute(TaskInterface::TYPE, $type);
        $task->setAttribute(TaskInterface::STATUS, TaskStatus::PENDING);
        $task->setAttribute(TaskInterface::OPTIONS, $options);

        $task->save();

        if ($type->equals(TaskType::SUBTITLE_CONVERSION())) {
            $task->getStorage()->addFile(
                $task::STORAGE_INPUT,
                $options->getFile()
            );
        }

        return $task;
    }

    /**
     * {@inheritdoc}
     */
    public function clone(TaskInterface $task): TaskInterface
    {
        $clonedOptions = $task->getOptions()->replicate();

        if ($task->getType()->equals(TaskType::SUBTITLE_CONVERSION())) {
            $clonedOptions->setFile(
                $task->getStorage()->getFile($task::STORAGE_INPUT)
            );
        }

        $clonedTask = $this->create($clonedOptions, $task->getType());

        return $clonedTask;
    }

    /**
     * {@inheritdoc}
     */
    public function findByIdOrFail(int $id): ModelInterface
    {
        throw new NotImplementedException();
    }

    /**
     * {@inheritdoc}
     */
    public function findAll(FiltersInterface $filters): LengthAwarePaginator
    {
        throw new NotImplementedException();
    }

    /**
     * {@inheritdoc}
     */
    public function findCount(TaskType $type, TaskStatus $status): int
    {
        throw new NotImplementedException();
    }

    /**
     * {@inheritdoc}
     */
    public function findByIdentifierOrFail(string $identifier): TaskInterface
    {
        throw new NotImplementedException();
    }

    /**
     * {@inheritdoc}
     */
    public function delete(object $entity): void
    {
        /** @var TaskInterface $task */
        $task = $entity;
        foreach ($task->getChildren() as $childTask) {
            $childTask->tearDownStorage();
            $childTask->delete();
        }
        $task->tearDownStorage();
        $task->delete();
    }

    /**
     * {@inheritdoc}
     */
    public function deleteTasksOlderThan(DateTime $cutOffDate): void
    {
        throw new NotImplementedException();
    }
}
