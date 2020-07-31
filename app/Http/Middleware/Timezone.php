<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Class Timezone.
 */
class Timezone
{
    /**
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $defaultTimezone = config('app.timezone');
        $timezone = $request->cookie('timezone', $defaultTimezone);
        $request->session()->put('timezone', $timezone);

        return $next($request);
    }
}
