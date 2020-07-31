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

use MyCLabs\Enum\Enum;

/**
 * @method static static TMDB()
 * @method static static OMDB()
 * @method static static KICKASSSUBTITLES()
 */
class MovieProvider extends Enum
{
    const TMDB = 'tmdb';

    const OMDB = 'omdb';

    const KICKASSSUBTITLES = 'kickasssubtitles';

    /**
     * @var array
     */
    protected static $names = [
        self::TMDB => 'The Movie Database',
        self::OMDB => 'The Open Movie Database',
        self::KICKASSSUBTITLES => 'KickAssSubtitles',
    ];

    public function getName(): string
    {
        return self::$names[$this->getValue()];
    }
}
