<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\AbstractController;
use App\Jobs\DeleteTemporaryUser;
use Illuminate\Contracts\Cookie\Factory as CookieFactory;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class LoginController.
 */
class LoginController extends AbstractController
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers {
        logout as baseLogout;
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * @return string
     */
    public function username(): string
    {
        return 'username';
    }

    /**
     * Show the application's login form.
     *
     * @return Response
     */
    public function showLoginForm(): Response
    {
        /** @var ResponseFactory $response */
        $response = response();

        return $response->view('controllers.auth.login.show_login_form');
    }

    /**
     * {@inheritdoc}
     */
    protected function credentials(Request $request)
    {
        $credentials = $request->only($this->username(), 'password');
        $credentials['active'] = true;

        return $credentials;
    }

    /**
     * Log the user out of the application.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function logout(Request $request)
    {
        /** @var CookieFactory $cookie */
        $cookie = cookie();
        $user = $request->user();
        $response = $this->baseLogout($request);
        if ($user->isTemporary()) {
            DeleteTemporaryUser::dispatch($user);
            $response->withCookie(
                $cookie->forever('temporary', 'no')
            );
        }

        return $response;
    }
}
