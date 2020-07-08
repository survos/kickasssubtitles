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

use KickAssSubtitles\Subtitle\SubtitleProvider;
use KickAssSubtitles\Support\RepositoryInterface;
use Throwable;

/**
 * Interface VideoRepositoryInterface.
 */
interface VideoRepositoryInterface extends RepositoryInterface
{
    const ERR_INVALID_FILESIZE = 'Invalid filesize';

    const ERR_MISSING_FILENAMES = 'Missing filenames';

    const ERR_MISSING_REQUIRED_HASHES = 'Missing required hashes';

    /**
     * @param array  $hashes
     * @param array  $filenames
     * @param int    $filesize
     * @param ImdbId $imdbId
     *
     * @return VideoInterface
     *
     * @throws Throwable
     */
    public function create(
        array $hashes,
        array $filenames,
        int $filesize,
        ImdbId $imdbId
    ): VideoInterface;

    /**
     * @param string           $hash
     * @param SubtitleProvider $provider
     *
     * @return VideoInterface
     *
     * @throws Throwable
     */
    public function findByProviderHashOrFail(
        string $hash,
        SubtitleProvider $provider
    ): VideoInterface;
}
