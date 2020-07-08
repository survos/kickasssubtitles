<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace App\Providers;

use App\Http\ViewComposers\LayoutComposer;
use App\Http\ViewComposers\LogoComposer;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Support\ServiceProvider;

/**
 * Class ViewServiceProvider.
 */
class ViewServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $view = $this->app->make(ViewFactory::class);
        $view->composer('*', LayoutComposer::class);
        $view->composer('partials.logo', LogoComposer::class);
    }
}
