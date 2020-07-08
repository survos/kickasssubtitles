<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace KickAssSubtitles\LineEnding;

use Throwable;

/**
 * Interface LineEndingDetectorInterface.
 */
interface LineEndingDetectorInterface
{
    const ERR_DETECTION_FAILED = 'Detection failed';

    /**
     * @param string $input
     *
     * @return LineEnding
     *
     * @throws Throwable
     */
    public function detect(string $input): LineEnding;

    /**
     * @param string $file
     *
     * @return LineEnding
     *
     * @throws Throwable
     */
    public function detectFile(string $file): LineEnding;
}
