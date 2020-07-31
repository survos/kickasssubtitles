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

use Illuminate\Contracts\Events\Dispatcher as EventDispatcherInterface;
use InvalidArgumentException;
use KickAssSubtitles\Processor\Event\TaskCompleted;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LogLevel;
use Psr\Log\NullLogger;
use Throwable;

/**
 * Class AbstractProcessor.
 */
abstract class AbstractProcessor implements ProcessorInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var string
     */
    public $logLevel = LogLevel::ERROR;

    /**
     * @var array
     */
    public $logSkip = [];

    /**
     * @var null|EventDispatcherInterface;
     */
    protected $eventDispatcher;

    public function __construct()
    {
        $this->setLogger(new NullLogger());
    }

    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher): void
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): ProcessorName
    {
        return new ProcessorName(static::class);
    }

    /**
     * {@inheritdoc}
     */
    public function getSupportedTaskType(): TaskType
    {
        return TaskType::DEFAULT();
    }

    /**
     * {@inheritdoc}
     */
    public function processOne(TaskInterface $task): void
    {
        try {
            $task->setStatus(TaskStatus::PROCESSING());
            $task->setProcessorName($this->getName());

            if (!$this->getSupportedTaskType()->equals($task->getType())) {
                throw new InvalidArgumentException(static::ERR_INVALID_TASK_TYPE);
            }

            $this->beforeProcessTask($task);
            $this->processTask($task);
            $this->afterProcessTask($task);
            $task->setStatus(TaskStatus::COMPLETED());
            if ($this->eventDispatcher) {
                $this->eventDispatcher->dispatch(new TaskCompleted($task->getIdentifier()));
            }
        } catch (Throwable $e) {
            $task->setStatus(TaskStatus::FAILED(), $e);
            $this->log($e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function processMany(TaskCollectionInterface $tasks): void
    {
        foreach ($tasks as $task) {
            $this->processOne($task);
        }
    }

    /**
     * @throws Throwable
     */
    protected function beforeProcessTask(TaskInterface $task): void
    {
    }

    /**
     * @throws Throwable
     */
    abstract protected function processTask(TaskInterface $task): void;

    /**
     * @throws Throwable
     */
    protected function afterProcessTask(TaskInterface $task): void
    {
    }

    protected function log(Throwable $e): void
    {
        foreach ($this->logSkip as $className) {
            if ($e instanceof $className) {
                return;
            }
        }

        $this->logger->log($this->logLevel, $e->getMessage(), [
            'processor' => $this->getName(),
            'exception' => $e,
        ]);
    }
}
