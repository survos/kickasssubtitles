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
    /**
     * @param int|null $element
     *
     * @return string
     */
    public function getFilename(?int $element = null): string
    {
        $filename = $this->getAttribute(SubtitleOptionsInterface::FILENAME);

        if (null === $element) {
            return $filename;
        }

        return \pathinfo($filename, $element);
    }

    /**
     * @return int
     */
    public function getFilesize(): int
    {
        return $this->getAttribute(SubtitleOptionsInterface::FILESIZE);
    }

    /**
     * @return Encoding
     */
    public function getEncoding(): Encoding
    {
        return $this->getAttribute(SubtitleInterface::ENCODING);
    }

    /**
     * @return SubtitleFormat
     */
    public function getFormat(): SubtitleFormat
    {
        return $this->getAttribute(SubtitleInterface::FORMAT);
    }
}
