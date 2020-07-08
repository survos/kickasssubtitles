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
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use InvalidArgumentException;
use KickAssSubtitles\Support\UserInterface;

/**
 * Class UserRegistered.
 */
class UserRegistered extends Notification
{
    /**
     * @var UserInterface
     */
    public $user;

    /**
     * @param UserInterface $user
     */
    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }

    /**
     * Get the notification's channels.
     *
     * @param mixed $notifiable
     *
     * @return array|string
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * {@inheritdoc}
     */
    public function toMail($notifiable)
    {
        $subject = __('messages.activate_account');
        $action = __('messages.activate_account');
        $line = __('email.activate_account');

        if (!\is_string($subject) || !\is_string($action) || !\is_string($line)) {
            throw new InvalidArgumentException();
        }

        return (new MailMessage())
            ->subject($subject)
            ->line($line)
            ->action(
                $action,
                route(Route::ACTIVATE, [$this->user->getActivationToken()], true)
            );
    }
}
