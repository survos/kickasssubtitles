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

use KickAssSubtitles\Processor\TaskOptions;
use KickAssSubtitles\Support\ObjectCastsTrait;

/**
 * Class MovieSearchOptions.
 */
class MovieSearchOptions extends TaskOptions
{
    use ObjectCastsTrait;

    const IMDB_ID = 'imdb_id';

    public function getImdbId(): ImdbId
    {
        return $this->getAttribute(static::IMDB_ID);
    }

    protected function getObjectCasts(): array
    {
        return [
            static::IMDB_ID => ImdbId::class,
        ];
    }
}
