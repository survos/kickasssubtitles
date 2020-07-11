<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace App\Jobs;

use App\Services\SubtitleConverter;
use KickAssSubtitles\Processor\TaskInterface;
use Throwable;

/**
 * Class ProcessConversionTask.
 */
class ProcessSubtitleConversionTask extends AbstractJob
{
    /**
     * @var TaskInterface
     */
    protected $task;

    /**
     * @param TaskInterface $task
     */
    public function __construct(TaskInterface $task)
    {
        $this->task = $task;
    }

    /**
     * @param SubtitleConverter $converter
     *
     * @throws Throwable
     */
    public function handle(SubtitleConverter $converter): void
    {
        $converter->processOne($this->task);
    }
}
