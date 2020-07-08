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

/**
 * Interface SubtitleCollectionInterface.
 */
interface SubtitleCollectionInterface
{
    /**
     * Will move subtitles having given format to the begining of collection.
     *
     * @param SubtitleFormat $format
     *
     * @return $this
     */
    public function sortByFormat(SubtitleFormat $format): self;

    /**
     * @param SubtitleProvider $provider
     *
     * @return bool
     */
    public function containsProvider(SubtitleProvider $provider): bool;
}
