<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace App\Notifications;

use App\Enums\Route;
use Illuminate\Auth\Notifications\ResetPassword as BaseResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use InvalidArgumentException;

/**
 * Class ResetPassword.
 */
class ResetPassword extends BaseResetPassword
{
    /**
     * {@inheritdoc}
     */
    public function toMail($notifiable)
    {
        $subject = __('messages.reset_password');
        $action = __('messages.reset_password');
        $line = __('email.reset_password');

        if (!\is_string($subject) || !\is_string($action) || !\is_string($line)) {
            throw new InvalidArgumentException();
        }

        return (new MailMessage())
            ->subject($subject)
            ->line($line)
            ->action(
                $action,
                route(Route::PASSWORD_RESET, [$this->token], true)
            );
    }
}
