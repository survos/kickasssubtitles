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

use Illuminate\Contracts\Routing\Registrar;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Mcamara\LaravelLocalization\LaravelLocalization;
use Mcamara\LaravelLocalization\Traits\LoadsTranslatedCachedRoutes;

/**
 * Class RouteServiceProvider.
 */
class RouteServiceProvider extends ServiceProvider
{
    use LoadsTranslatedCachedRoutes;

    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     */
    public function boot(): void
    {
        parent::boot();
    }

    public function map(): void
    {
        $router = $this->app->make(Registrar::class);

        $router->bind('id', function ($value, $route) {
            return hashid_decode($value);
        });

        $this->mapWebRoutes($router);
        $this->mapApiRoutes($router);
    }

    /**
     * Define the "web" routes for the application.
     * These routes all receive session state, CSRF protection, etc.
     *
     * @param Registrar $router
     */
    protected function mapWebRoutes(Registrar $router)
    {
        $localization = $this->app->make(LaravelLocalization::class);
        $router
            ->prefix($localization->setLocale())
            ->middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'))
        ;
    }

    /**
     * Define the "api" routes for the application.
     * These routes are typically stateless.
     *
     * @param Registrar $router
     */
    protected function mapApiRoutes(Registrar $router)
    {
        $router
            ->prefix('api')
            ->middleware('api')
            ->namespace($this->namespace.'\\Api')
            ->group(base_path('routes/api.php'))
        ;
    }
}
