<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace App\Models\Collections;

use Illuminate\Database\Eloquent\Collection;
use KickAssSubtitles\Processor\TaskCollectionInterface;
use KickAssSubtitles\Processor\TaskCollectionTrait;
use KickAssSubtitles\Support\DownloadableInterface;

/**
 * Class TaskCollection.
 */
class TaskCollection extends Collection implements TaskCollectionInterface, DownloadableInterface
{
    use TaskCollectionTrait;

    /**
     * {@inheritdoc}
     */
    public function isDownloadable(): bool
    {
        if ($this->count() < 2) {
            return false;
        }
        if (!$this->isProcessed()) {
            return false;
        }
        foreach ($this as $task) {
            if ($task->isDownloadable()) {
                return true;
            }
        }

        return false;
    }
}
