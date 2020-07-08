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

    /**
     * @return string
     */
    public function getFilename(): string;

    /**
     * @return int
     */
    public function getFilesize(): int;

    /**
     * @return Encoding
     */
    public function getEncoding(): Encoding;

    /**
     * @return SubtitleFormat
     */
    public function getFormat(): SubtitleFormat;
}
