<?php

declare(strict_types=1);

namespace App\Listeners;

use Illuminate\Auth\Events\Login as LoginEvent;

/**
 * Class UserLoginListener
 * @package App\Listeners
 */
class UserLoginListener extends AbstractListener
{
    public function handle(LoginEvent $event)
    {
        $event->user->updateLastLogin();
    }
}
