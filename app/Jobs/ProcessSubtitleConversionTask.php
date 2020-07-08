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
use Throwable;

/**
 * Class ProcessConversionTask.
 */
class ProcessSubtitleConversionTask extends AbstractJob
{
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
