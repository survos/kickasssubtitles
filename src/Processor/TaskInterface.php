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
use Throwable;

/**
 * Interface TaskInterface.
 */
interface TaskInterface
{
    const ERR_INVALID_OPTIONS = 'Invalid options instance';

    const ERR_INVALID_STATUS_CHANGE = 'Invalid status change';

    const ERR_MISSING_EXCEPTION = 'Failed task should include relevant exception';

    const STORAGE_INPUT = 'input';

    const STORAGE_OUTPUT = 'output';

    const IDENTIFIER = 'identifier';

    const TYPE = 'type';

    const STATUS = 'status';

    const OPTIONS = 'options';

    const RESULTS = 'results';

    const ERROR = 'error';

    const PROCESSOR_NAME = 'processor_name';

    /**
     * @return self|null
     */
    public function getParent(): ?self;

    /**
     * @param TaskInterface $parent
     */
    public function setParent(self $parent): void;

    /**
     * @return TaskCollectionInterface
     */
    public function getChildren(): TaskCollectionInterface;

    /**
     * @param self $task
     */
    public function addChild(self $task): void;

    /**
     * @return string
     */
    public function getIdentifier(): string;

    /**
     * @return TaskType
     */
    public function getType(): TaskType;

    /**
     * @return TaskStatus
     */
    public function getStatus(): TaskStatus;

    /**
     * @param TaskStatus     $status
     * @param Throwable|null $e
     *
     * @throws Throwable
     */
    public function setStatus(TaskStatus $status, ?Throwable $e = null): void;

    /**
     * @return null|TaskErrorInterface
     */
    public function getError(): ?TaskErrorInterface;

    /**
     * @return TaskOptions
     */
    public function getOptions(): TaskOptions;

    /**
     * @param Arrayable $result
     */
    public function addResult(Arrayable $result): void;

    /**
     * @param Arrayable $result
     */
    public function prependResult(Arrayable $result): void;

    /**
     * @return Collection
     */
    public function getResults(): Collection;

    /**
     * @return ProcessorName|null
     */
    public function getProcessorName(): ?ProcessorName;

    /**
     * @param ProcessorName $processorName
     */
    public function setProcessorName(ProcessorName $processorName): void;

    /**
     * @return bool
     */
    public function isProcessed(): bool;
}
