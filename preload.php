<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

require __DIR__.'/vendor/autoload.php';

$preloader = new \KickAssSubtitles\Support\Preloader(__DIR__.'/vendor/composer/autoload_classmap.php');
$preloader
    ->paths([
        __DIR__.'/vendor/psr',
        __DIR__.'/vendor/laravel',
    ])
    ->ignore([
        \Psr\Log\Test::class,
        \Illuminate\Filesystem\Cache::class,
        \Illuminate\Log\LogManager::class,
        \Illuminate\Http\Testing\File::class,
        \Illuminate\Http\UploadedFile::class,
        \Illuminate\Support\Carbon::class,
        \Illuminate\Foundation\Testing\HttpException::class,
        \Illuminate\Foundation\Testing\TestCase::class,
        \Illuminate\Foundation\Testing\TestResponse::class,
        \Illuminate\Foundation\Testing\Concerns\InteractsWithDatabase::class,
        \Illuminate\Foundation\Testing\Constraints\HasInDatabase::class,
        \Illuminate\Foundation\Testing\Constraints\SoftDeletedInDatabase::class,
    ])
    ->load()
;
