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

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use InvalidArgumentException;
use KickAssSubtitles\Support\HydratableInterface;
use Throwable;

/**
 * Trait TaskTrait.
 */
trait TaskTrait
{
    /**
     * Parent.
     *
     * @var null|TaskInterface
     */
    protected $p;

    /**
     * Children.
     *
     * @var TaskCollectionInterface
     */
    protected $ch;

    /**
     * {@inheritdoc}
     */
    public function getParent(): ?TaskInterface
    {
        return $this->p;
    }

    /**
     * {@inheritdoc}
     */
    public function setParent(TaskInterface $parent): void
    {
        $this->p = $parent;
    }

    /**
     * {@inheritdoc}
     */
    public function getChildren(): TaskCollectionInterface
    {
        if (null === $this->ch) {
            $this->ch = new TaskCollection();
        }

        return $this->ch;
    }

    /**
     * {@inheritdoc}
     */
    public function addChild(TaskInterface $task): void
    {
        $task->setParent($this);
        $this->getChildren()->addTask($task);
    }

    /**
     * {@inheritdoc}
     */
    public function isProcessed(): bool
    {
        return \in_array($this->getStatus()->getValue(), [
            TaskStatus::COMPLETED,
            TaskStatus::FAILED,
        ], true);
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentifier(): string
    {
        return $this->getAttribute(TaskInterface::IDENTIFIER);
    }

    /**
     * {@inheritdoc}
     */
    public function getType(): TaskType
    {
        return $this->getAttribute(TaskInterface::TYPE);
    }

    /**
     * {@inheritdoc}
     */
    public function getStatus(): TaskStatus
    {
        return $this->getAttribute(static::STATUS);
    }

    /**
     * {@inheritdoc}
     */
    public function setStatus(TaskStatus $status, ?Throwable $e = null): void
    {
        if (!$this->getStatus()->isValidStatusChange($status)) {
            throw new InvalidArgumentException(static::ERR_INVALID_STATUS_CHANGE);
        }

        $this->setAttribute(TaskInterface::STATUS, $status);

        if (!$this->getStatus()->equals(TaskStatus::FAILED())) {
            $this->save();

            return;
        }

        if (null === $e) {
            throw new InvalidArgumentException(self::ERR_MISSING_EXCEPTION);
        }

        $this->setAttribute(TaskInterface::ERROR, TaskError::createFromThrowable($e));
        $this->save();
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions(): TaskOptions
    {
        $array = $this->getAttribute(static::OPTIONS);
        /** @var HydratableInterface $class */
        $class = $array[HydratableInterface::CLASS_ATTRIBUTE];
        /** @var TaskOptions $options */
        $options = $class::hydrateFromArray($array);

        return $options;
    }

    /**
     * @return Collection
     */
    public function getResults(): Collection
    {
        $results = new Collection();

        $array = $this->getAttribute(static::RESULTS);
        foreach ($array as $result) {
            /** @var HydratableInterface $class */
            $class = $result[HydratableInterface::CLASS_ATTRIBUTE];
            $results->push($class::hydrateFromArray($result));
        }

        return $results;
    }

    /**
     * {@inheritdoc}
     */
    public function addResult(Arrayable $result): void
    {
        $this->setAttribute(static::RESULTS, $this->getResults()->push($result));
        $this->save();
    }

    /**
     * {@inheritdoc}
     */
    public function prependResult(Arrayable $result): void
    {
        $this->setAttribute(static::RESULTS, $this->getResults()->prepend($result));
        $this->save();
    }

    /**
     * {@inheritdoc}
     */
    public function getProcessorName(): ?ProcessorName
    {
        return $this->getAttribute(static::PROCESSOR_NAME);
    }

    /**
     * {@inheritdoc}
     */
    public function setProcessorName(ProcessorName $processorName): void
    {
        $this->setAttribute(static::PROCESSOR_NAME, $processorName);
    }

    /**
     * {@inheritdoc}
     */
    public function getError(): ?TaskErrorInterface
    {
        $array = $this->getAttribute(TaskInterface::ERROR);

        if (empty($array)) {
            return null;
        }

        /** @var HydratableInterface $class */
        $class = $array[HydratableInterface::CLASS_ATTRIBUTE];

        /** @var TaskErrorInterface $error */
        $error = $class::hydrateFromArray($array);

        return $error;
    }
}
