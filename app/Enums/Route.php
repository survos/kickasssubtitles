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
 * @method static Route HOME()
 * @method static Route TASKS_GROUP()
 * @method static Route TASKS_GROUP_DOWNLOAD()
 * @method static Route TASKS_DOWNLOAD()
 * @method static Route HISTORY()
 * @method static Route SEARCH()
 * @method static Route SEARCH_CREATE()
 * @method static Route CONVERT()
 * @method static Route CONVERT_CREATE()
 * @method static Route DOWNLOAD_CREATE()
 * @method static Route LOGIN()
 * @method static Route LOGOUT()
 * @method static Route REGISTER()
 * @method static Route REGISTER_TEMPORARY()
 * @method static Route MOVIES()
 * @method static Route MOVIES_SHOW()
 * @method static Route SUBTITLES_SHOW()
 * @method static Route ACTIVATE()
 * @method static Route PASSWORD_REQUEST()
 * @method static Route PASSWORD_EMAIL()
 * @method static Route PASSWORD_RESET()
 */
class Route extends Enum
{
    const HOME = 'home';

    const TASKS_GROUP = 'tasks.group';

    const TASKS_GROUP_DOWNLOAD = 'tasks.group.download';

    const TASKS_DOWNLOAD = 'tasks.download';

    const HISTORY = 'history';

    const SEARCH = 'search';

    const SEARCH_CREATE = 'search.create';

    const CONVERT = 'convert';

    const CONVERT_CREATE = 'convert.create';

    const DOWNLOAD_CREATE = 'download.create';

    const LOGIN = 'login';

    const LOGOUT = 'logout';

    const REGISTER = 'register';

    const REGISTER_TEMPORARY = 'register.temporary';

    const MOVIES = 'movies';

    const MOVIES_SHOW = 'movies.show';

    const SUBTITLES_SHOW = 'subtitles.show';

    const ACTIVATE = 'activate';

    const PASSWORD_REQUEST = 'password.request';

    const PASSWORD_EMAIL = 'password.email';

    const PASSWORD_RESET = 'password.reset';
}
