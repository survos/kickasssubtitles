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

use App\Repositories\UserRepository;
use Closure;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

/**
 * Class TemporaryUser.
 */
class TemporaryUser
{
    /**
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        Carbon::setLocale(config('app.locale'));

        if (app()->bound('LaravelCrawlerDetect') &&
            app('LaravelCrawlerDetect')->isCrawler()
        ) {
            return $next($request);
        }

        if ('no' === $request->cookie('temporary')) {
            return $next($request);
        }

        if (!$request->user()) {
            /** @var UserRepository $userRepository */
            $userRepository = app(UserRepository::class);

            $temporaryCount = app('cache')->remember('temporary_count', 60 * 24, function () use ($userRepository) {
                return $userRepository->countTemporary();
            });

            $temporaryCountLimit = config('app.temporary.limit');

            if ($temporaryCount > $temporaryCountLimit) {
                return $next($request);
            }

            $user = $userRepository->registerTemporary();

            /** @var StatefulGuard $auth */
            $auth = auth();
            $auth->login($user, true);

            /** @var RedirectResponse $response */
            $response = redirect($request->path());

            return $response->with(
                'status',
                __('messages.register_temporary')
            );
        }

        return $next($request);
    }
}
