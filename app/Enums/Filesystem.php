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
 * @method static Filesystem SUBTITLES()
 * @method static Filesystem TASKS()
 * @method static Filesystem UPLOADS()
 * @method static Filesystem TMP()
 * @method static Filesystem PUBLIC()
 * @method static Filesystem MEDIALIBRARY_TESTING()
 */
class Filesystem extends Enum
{
    const SUBTITLES = 'subtitles';

    const TASKS = 'tasks';

    const UPLOADS = 'uploads';

    const TMP = 'tmp';

    const PUBLIC = 'public';

    const MEDIALIBRARY_TESTING = 'medialibrary_testing';
}
