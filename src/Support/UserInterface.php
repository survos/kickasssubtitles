<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace KickAssSubtitles\Support;

/**
 * Interface UserInterface.
 */
interface UserInterface
{
    const USERNAME = 'username';

    const EMAIL = 'email';

    const PASSWORD = 'password';

    const ACTIVATION_TOKEN = 'activation_token';

    const REMEMBER_TOKEN = 'remember_token';

    const ACTIVE = 'active';

    const LAST_LOGIN = 'last_login';

    public function isTemporary(): bool;

    public function getUsername(): string;

    public function getEmail(): ?string;

    public function getActivationToken(): string;

    public function updateLastLogin(): void;
}
