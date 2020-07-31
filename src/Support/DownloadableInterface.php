<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace KickAssSubtitles\Support;

/**
 * Interface DownloadableInterface.
 */
interface DownloadableInterface
{
    const ERR_NOT_DOWNLOADABLE = 'Not downloadable';

    public function isDownloadable(): bool;
}
