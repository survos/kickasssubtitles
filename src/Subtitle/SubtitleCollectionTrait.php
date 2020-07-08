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
 * Trait SubtitleCollectionTrait.
 */
trait SubtitleCollectionTrait
{
    /**
     * {@inheritdoc}
     */
    public function sortByFormat(SubtitleFormat $format): self
    {
        return $this->sortBy(function (SubtitleInterface $subtitle, $key) use ($format) {
            if ($subtitle->getFormat()->equals($format)) {
                return 0;
            }

            return 1;
        });
    }

    /**
     * {@inheritdoc}
     */
    public function containsProvider(SubtitleProvider $provider): bool
    {
        return $this->contains(function (SubtitleInterface $subtitle, $key) use ($provider) {
            return $subtitle->getProvider()->equals($provider);
        });
    }
}
