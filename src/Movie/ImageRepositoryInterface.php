<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace KickAssSubtitles\Movie;

use Throwable;

/**
 * Interface ImageRepositoryInterface.
 */
interface ImageRepositoryInterface
{
    /**
     * @param string        $urlOrPath
     * @param ImdbId        $imdbId
     * @param ImageType     $type
     * @param ImageProvider $provider
     *
     * @return ImageInterface
     *
     * @throws Throwable
     */
    public function createFromUrlOrPath(
        string $urlOrPath,
        ImdbId $imdbId,
        ImageType $type,
        ImageProvider $provider
    ): ImageInterface;

    /**
     * @param ImageInterface $image
     *
     * @return ImageInterface
     *
     * @throws Throwable
     */
    public function createFromImage(ImageInterface $image): ImageInterface;

    /**
     * @param ImdbId $imdbId
     *
     * @return ImageInterface
     *
     * @throws Throwable
     */
    public function findByImdbIdOrFail(ImdbId $imdbId): ImageInterface;
}
