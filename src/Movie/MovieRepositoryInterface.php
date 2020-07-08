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
    /**
     * @param string        $title
     * @param ImdbId        $imdbId
     * @param MovieYear     $year
     * @param MovieType     $type
     * @param MovieProvider $provider
     *
     * @return MovieInterface
     */
    public function create(
        string $title,
        ImdbId $imdbId,
        MovieYear $year,
        MovieType $type,
        MovieProvider $provider
    ): MovieInterface;

    /**
     * @param MovieInterface $movie
     *
     * @return MovieInterface
     *
     * @throws Throwable
     */
    public function createFromMovie(MovieInterface $movie): MovieInterface;

    /**
     * @param ImdbId $imdbId
     *
     * @return MovieInterface
     *
     * @throws Throwable
     */
    public function findByImdbIdOrFail(ImdbId $imdbId): MovieInterface;
}
