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
 * @method static Menu MAIN()
 * @method static Menu AUTH()
 * @method static Menu LANG()
 */
class Menu extends Enum
{
    const MAIN = 'main';

    const AUTH = 'auth';

    const LANG = 'lang';
}
