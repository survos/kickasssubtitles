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
 * Interface MovieInterface.
 */
interface MovieInterface
{
    const IMDB_ID = 'imdb_id';

    const TITLE = 'title';

    const SLUG = 'slug';

    const TYPE = 'type';

    const YEAR_FROM = 'year_from';

    const YEAR_TO = 'year_to';

    const PROVIDER = 'provider';

    const PROVIDER_PREVIOUS = 'provider_previous';

    /**
     * @return string
     *
     * @throws Throwable
     */
    public function getTitle(): string;

    /**
     * @return string
     *
     * @throws Throwable
     */
    public function getSlug(): string;

    /**
     * @return string
     */
    public function getFolder(): string;

    /**
     * @return ImdbId
     */
    public function getImdbId(): ImdbId;

    /**
     * @return MovieYear
     */
    public function getYear(): MovieYear;

    /**
     * @return MovieType
     *
     * @throws Throwable
     */
    public function getType(): MovieType;

    /**
     * @return MovieProvider
     */
    public function getProvider(): MovieProvider;

    /**
     * @param MovieProvider $provider
     */
    public function setProvider(MovieProvider $provider): void;

    /**
     * @return null|MovieProvider
     */
    public function getProviderPrevious(): ?MovieProvider;

    /**
     * @return ImageInterface|null
     */
    public function getPoster(): ?ImageInterface;
}
