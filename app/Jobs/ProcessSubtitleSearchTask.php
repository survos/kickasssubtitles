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

use App\Services\SubtitleProvider;
use KickAssSubtitles\Processor\TaskInterface;
use Throwable;

/**
 * Class ProcessSearchTask.
 */
class ProcessSubtitleSearchTask extends AbstractJob
{
    /**
     * @var TaskInterface
     */
    protected $task;

    public function __construct(TaskInterface $task)
    {
        $this->task = $task;
    }

    /**
     * @throws Throwable
     */
    public function handle(SubtitleProvider $provider): void
    {
        $provider->processOne($this->task);
    }
}
