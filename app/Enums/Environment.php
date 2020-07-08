<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace App\Enums;

use MyCLabs\Enum\Enum;

/**
 * @method static Environment DEVELOPMENT()
 * @method static Environment PRODUCTION()
 * @method static Environment TESTING()
 */
class Environment extends Enum
{
    const DEVELOPMENT = 'development';

    const PRODUCTION = 'production';

    const TESTING = 'testing';
}
