<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace Tests\Support;

use MyCLabs\Enum\Enum;

/**
 * @method static Format SUBRIP()
 * @method static Format TMPLAYER()
 * @method static Format WEBVTT()
 */
class Format extends Enum
{
    const SUBRIP = 'subrip';

    const TMPLAYER = 'tmplayer';

    const WEBVTT = 'webvtt';
}
