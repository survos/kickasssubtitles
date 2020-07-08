<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace KickAssSubtitles\Subtitle;

use Throwable;

/**
 * Interface SubtitleFormatDetectorInterface.
 */
interface SubtitleFormatDetectorInterface
{
    const ERR_DETECTION_FAILED = 'Detection failed';

    /**
     * @param string $file
     *
     * @return SubtitleFormat
     *
     * @throws Throwable
     */
    public function detectFile(string $file): SubtitleFormat;
}
