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
 * @method static VideoFps F23_976()
 * @method static VideoFps F23_980()
 * @method static VideoFps F24_000()
 * @method static VideoFps F25_000()
 * @method static VideoFps F29_970()
 * @method static VideoFps F30_000()
 * @method static VideoFps F50_000()
 * @method static VideoFps F59_940()
 * @method static VideoFps F60_000()
 */
class VideoFps extends Enum
{
    const F23_976 = '23.976';

    const F23_980 = '23.980';

    const F24_000 = '24.000';

    const F25_000 = '25.000';

    const F29_970 = '29.970';

    const F30_000 = '30.000';

    const F50_000 = '50.000';

    const F59_940 = '59.940';

    const F60_000 = '60.000';
}
