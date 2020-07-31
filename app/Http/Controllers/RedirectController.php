<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace App\Http\Controllers;

use App\Enums\Route;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;

/**
 * Class RedirectController.
 */
class RedirectController extends Controller
{
    public function movie(string $hashid): RedirectResponse
    {
        return redirect()->route(
            Route::MOVIES_SHOW,
            ['id' => $hashid],
            RedirectResponse::HTTP_MOVED_PERMANENTLY
        );
    }

    public function browse(): RedirectResponse
    {
        return redirect()->route(
            Route::MOVIES,
            [],
            RedirectResponse::HTTP_MOVED_PERMANENTLY
        );
    }

    public function find(): RedirectResponse
    {
        return redirect()->route(
            Route::SEARCH,
            [],
            RedirectResponse::HTTP_MOVED_PERMANENTLY
        );
    }
}
