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

use KickAssSubtitles\Encoding\Encoding;

/**
 * Interface DataInterface.
 */
interface SubtitleOptionsInterface
{
    const DEFAULT_FILENAME = 'subtitles.txt';

    const FILENAME = 'filename';

    const FILESIZE = 'filesize';

    public function getFilename(): string;

    public function getFilesize(): int;

    public function getEncoding(): Encoding;

    public function getFormat(): SubtitleFormat;
}
