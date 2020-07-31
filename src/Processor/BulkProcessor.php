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

use InvalidArgumentException;
use KickAssSubtitles\Processor\Exception\AllChildTasksFailedException;

/**
 * Class BulkProcessor.
 */
class BulkProcessor extends AbstractProcessor
{
    /**
     * @var ProcessorInterface[]
     */
    protected $processors = [];

    /**
     * @var TaskRepositoryInterface
     */
    protected $taskRepository;

    /**
     * @var TaskType|null
     */
    protected $supportedTaskType;

    /**
     * @var bool
     */
    protected $stopAfterFirstCompletedTask = false;

    public function __construct(
        array $processors,
        TaskRepositoryInterface $taskRepository
    ) {
        parent::__construct();
        if (empty($processors)) {
            throw new InvalidArgumentException();
        }
        foreach ($processors as $processor) {
            $this->addProcessor($processor);
        }
        $this->taskRepository = $taskRepository;
    }

    public function setStopAfterFirstCompletedTask(bool $stop): void
    {
        $this->stopAfterFirstCompletedTask = $stop;
    }

    public function addProcessor(ProcessorInterface $processor): self
    {
        if (isset($this->processors[$processor->getName()->getName()])) {
            throw new InvalidArgumentException(static::ERR_PROCESSOR_ALREADY_ADDED);
        }

        $this->processors[$processor->getName()->getName()] = $processor;

        if (null === $this->supportedTaskType) {
            $this->supportedTaskType = $processor->getSupportedTaskType();
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSupportedTaskType(): TaskType
    {
        return $this->supportedTaskType;
    }

    /**
     * {@inheritdoc}
     */
    protected function processTask(TaskInterface $task): void
    {
        $completed = 0;

        foreach ($this->processors as $processor) {
            if ($this->stopAfterFirstCompletedTask && $completed) {
                break;
            }

            $childTask = $this->taskRepository->clone($task);
            $processor->processOne($childTask);
            if ($childTask->getStatus()->equals(TaskStatus::COMPLETED())) {
                ++$completed;
            }
            $task->addChild($childTask);
        }

        if ($task->getChildren()->isInStatus(TaskStatus::FAILED())) {
            throw new AllChildTasksFailedException();
        }
    }
}
