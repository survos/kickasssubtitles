<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace App\Services;

/**
 * Class RouteBinds.
 */
class RouteBinds
{
    public function bindId($value, $route)
    {
        return hashid_decode($value);
    }
}
