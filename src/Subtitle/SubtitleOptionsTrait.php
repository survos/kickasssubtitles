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
 * Trait SubtitleOptionsTrait.
 */
trait SubtitleOptionsTrait
{
    public function getFilename(?int $element = null): string
    {
        $filename = $this->getAttribute(SubtitleOptionsInterface::FILENAME);

        if (null === $element) {
            return $filename;
        }

        return pathinfo($filename, $element);
    }

    public function getFilesize(): int
    {
        return $this->getAttribute(SubtitleOptionsInterface::FILESIZE);
    }

    public function getEncoding(): Encoding
    {
        return $this->getAttribute(SubtitleInterface::ENCODING);
    }

    public function getFormat(): SubtitleFormat
    {
        return $this->getAttribute(SubtitleInterface::FORMAT);
    }
}
