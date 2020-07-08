<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace App\Repositories;

use Exception;
use Illuminate\Database\QueryException;
use KickAssSubtitles\Support\UserInterface;
use Throwable;

/**
 * Class UserRepository.
 */
class UserRepository
{
    const ERR_INVALID_TOKEN = 'Invalid token';

    /**
     * @var string
     */
    protected $userClass;

    /**
     * @param string $userClass
     */
    public function __construct(string $userClass)
    {
        $this->userClass = $userClass;
    }

    /**
     * @param string $token
     *
     * @return UserInterface
     *
     * @throws Throwable
     */
    public function activate(string $token): UserInterface
    {
        $userClass = $this->userClass;

        $user = $userClass::where(UserInterface::ACTIVATION_TOKEN, $token)->first();
        if (!$user instanceof UserInterface) {
            throw new Exception(static::ERR_INVALID_TOKEN);
        }
        $user->setAttribute(UserInterface::ACTIVE, true);
        $user->setAttribute(UserInterface::ACTIVATION_TOKEN, null);
        $user->save();

        return $user;
    }

    /**
     * @param string $username
     * @param string $email
     * @param string $password
     *
     * @return UserInterface
     */
    public function register(string $username, string $email, string $password): UserInterface
    {
        $userClass = $this->userClass;

        return $userClass::create([
            UserInterface::USERNAME => $username,
            UserInterface::EMAIL => $email,
            UserInterface::PASSWORD => bcrypt($password),
            UserInterface::ACTIVATION_TOKEN => str_random(30).\time(),
        ]);
    }

    /**
     * @return UserInterface
     *
     * @throws Throwable
     */
    public function registerTemporary(): UserInterface
    {
        $userClass = $this->userClass;
        $user = new $userClass();
        $continue = true;
        while ($continue) {
            try {
                $user->setAttribute(UserInterface::USERNAME, 'u'.\rand(1, 10000));
                $user->save();
                $continue = false;
            } catch (QueryException $e) {
                if (23000 !== $e->getCode()) {
                    throw $e;
                }
            }
        }

        return $user;
    }
}
