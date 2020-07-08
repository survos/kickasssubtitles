<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

use App\Enums\Route;

/*
 * auth
 ******************************************************************************/

$router
    ->get('register/temporary', 'Auth\RegisterController@registerTemporary')
    ->name(Route::REGISTER_TEMPORARY)
    ->middleware('guest')
;
$router
    ->get('register', 'Auth\RegisterController@showRegistrationForm')
    ->name(Route::REGISTER)
    ->middleware('guest')
;
$router
    ->post('register', 'Auth\RegisterController@register')
    ->middleware('guest')
;
$router
    ->get('activate/{token}', 'Auth\RegisterController@activate')
    ->name(Route::ACTIVATE)
    ->middleware('guest')
;
$router
    ->get('login', 'Auth\LoginController@showLoginForm')
    ->name(Route::LOGIN)
    ->middleware('guest')
;
$router
    ->post('login', 'Auth\LoginController@login')
    ->middleware('guest')
;
$router
    ->post('logout', 'Auth\LoginController@logout')
    ->name(Route::LOGOUT)
;
$router
    ->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')
    ->name(Route::PASSWORD_REQUEST)
    ->middleware('guest')
;
$router
    ->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')
    ->name(Route::PASSWORD_EMAIL)
    ->middleware('guest')
;
$router
    ->get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')
    ->name(Route::PASSWORD_RESET)
    ->middleware('guest')
;
$router
    ->post('password/reset', 'Auth\ResetPasswordController@reset')
    ->middleware('guest')
;

/*
 * tasks
 ******************************************************************************/

$router
    ->get('search', 'TasksController@search')
    ->name(Route::SEARCH)
;
$router
    ->post('search', 'TasksController@createSearches')
    ->name(Route::SEARCH_CREATE)
;
$router
    ->get('convert', 'TasksController@convert')
    ->name(Route::CONVERT)
;
$router
    ->post('convert', 'TasksController@createConversions')
    ->name(Route::CONVERT_CREATE)
;
$router
    ->post('download', 'TasksController@createDownloads')
    ->name(Route::DOWNLOAD_CREATE)
;
$router
    ->get('tasks/group/{group}', 'TasksController@group')
    ->name(Route::TASKS_GROUP)
;
$router
    ->get('history', 'TasksController@history')
    ->name(Route::HISTORY)
    ->middleware('auth')
;

/*
 * downloads
 ******************************************************************************/

$router
    ->get('tasks/group/{group}/download', 'DownloadsController@downloadTasksGroup')
    ->name(Route::TASKS_GROUP_DOWNLOAD)
;
$router
    ->get('tasks/{id}/download', 'DownloadsController@downloadTask')
    ->name(Route::TASKS_DOWNLOAD)
;

/*
 * movies
 ******************************************************************************/

$router
    ->get('movies/{id}', 'MoviesController@show')
    ->name(Route::MOVIES_SHOW)
;
$router
    ->get('movies', 'MoviesController@index')
    ->name(Route::MOVIES)
;
$router
    ->get('/', 'MoviesController@recentlySearched')
    ->name(Route::HOME)
;

/*
 * subtitles
 ******************************************************************************/

$router
    ->get('subtitles/{id}', 'SubtitlesController@show')
    ->name(Route::SUBTITLES_SHOW)
;

/*
 * legacy routes redirects
 ******************************************************************************/

$router
    ->get('movie/{hashid}', 'RedirectController@movie')
;
$router
    ->get('browse', 'RedirectController@browse')
;
$router
    ->get('find', 'RedirectController@find')
;
