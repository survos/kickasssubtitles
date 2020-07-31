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

use KickAssSubtitles\Support\RepositoryInterface;
use Throwable;

/**
 * Interface MovieRepositoryInterface.
 */
interface MovieRepositoryInterface extends RepositoryInterface
{
    public function create(
        string $title,
        ImdbId $imdbId,
        MovieYear $year,
        MovieType $type,
        MovieProvider $provider
    ): MovieInterface;

    /**
     * @throws Throwable
     */
    public function createFromMovie(MovieInterface $movie): MovieInterface;

    /**
     * @throws Throwable
     */
    public function findByImdbIdOrFail(ImdbId $imdbId): MovieInterface;
}
