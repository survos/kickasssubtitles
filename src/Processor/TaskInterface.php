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

    public function getParent(): ?self;

    /**
     * @param TaskInterface $parent
     */
    public function setParent(self $parent): void;

    public function getChildren(): TaskCollectionInterface;

    public function addChild(self $task): void;

    public function getIdentifier(): string;

    public function getType(): TaskType;

    public function getStatus(): TaskStatus;

    /**
     * @throws Throwable
     */
    public function setStatus(TaskStatus $status, ?Throwable $e = null): void;

    public function getError(): ?TaskErrorInterface;

    public function getOptions(): TaskOptions;

    public function addResult(Arrayable $result): void;

    public function prependResult(Arrayable $result): void;

    public function getResults(): Collection;

    public function getProcessorName(): ?ProcessorName;

    public function setProcessorName(ProcessorName $processorName): void;

    public function isProcessed(): bool;
}
