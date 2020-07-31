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
     * @throws Throwable
     */
    public function getTitle(): string;

    /**
     * @throws Throwable
     */
    public function getSlug(): string;

    public function getFolder(): string;

    public function getImdbId(): ImdbId;

    public function getYear(): MovieYear;

    /**
     * @throws Throwable
     */
    public function getType(): MovieType;

    public function getProvider(): MovieProvider;

    public function setProvider(MovieProvider $provider): void;

    public function getProviderPrevious(): ?MovieProvider;

    public function getPoster(): ?ImageInterface;
}
