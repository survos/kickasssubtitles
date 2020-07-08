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
 * @method static MovieType MOVIE()
 * @method static MovieType SERIES()
 * @method static MovieType EPISODE()
 */
class MovieType extends Enum
{
    const MOVIE = 'movie';

    const SERIES = 'series';

    const EPISODE = 'episode';
}
