<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace App\Listeners;

use Illuminate\Auth\Events\Login as LoginEvent;

/**
 * Class UserLoginListener.
 */
class UserLoginListener extends AbstractListener
{
    public function handle(LoginEvent $event)
    {
        $event->user->updateLastLogin();
    }
}
