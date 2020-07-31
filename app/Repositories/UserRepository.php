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
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use KickAssSubtitles\Processor\TaskRepositoryInterface;
use KickAssSubtitles\Support\Exception\NotImplementedException;
use KickAssSubtitles\Support\FiltersInterface;
use KickAssSubtitles\Support\ModelInterface;
use KickAssSubtitles\Support\RepositoryInterface;
use KickAssSubtitles\Support\UserInterface;
use Throwable;

/**
 * Class UserRepository.
 */
class UserRepository implements RepositoryInterface
{
    const ERR_INVALID_TOKEN = 'Invalid token';

    /**
     * @var string
     */
    protected $userClass;

    /**
     * @var TaskRepositoryInterface
     */
    protected $taskRepository;

    public function __construct(string $userClass, TaskRepositoryInterface $taskRepository)
    {
        $this->userClass = $userClass;
        $this->taskRepository = $taskRepository;
    }

    /**
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

    public function register(string $username, string $email, string $password): UserInterface
    {
        $userClass = $this->userClass;

        return $userClass::create([
            UserInterface::USERNAME => $username,
            UserInterface::EMAIL => $email,
            UserInterface::PASSWORD => bcrypt($password),
            UserInterface::ACTIVATION_TOKEN => str_random(30).time(),
        ]);
    }

    /**
     * @throws Throwable
     */
    public function registerTemporary(): UserInterface
    {
        $randomUsernames = [];
        for ($i = 1; $i <= 10; ++$i) {
            $randomUsernames[] = 'u'.rand(1, 100000);
        }

        $userClass = $this->userClass;
        $exception = null;
        foreach ($randomUsernames as $username) {
            try {
                $user = new $userClass();
                $user->setAttribute(UserInterface::USERNAME, $username);
                $user->save();
                $exception = null;
                break;
            } catch (Throwable $e) {
                $exception = $e;
            }
        }

        if (null !== $exception) {
            throw $exception;
        }

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function findByIdOrFail(int $id): ModelInterface
    {
        $userClass = $this->userClass;

        return $userClass::findOrFail($id);
    }

    /**
     * {@inheritdoc}
     */
    public function findAll(FiltersInterface $filters): LengthAwarePaginator
    {
        throw new NotImplementedException();
    }

    /**
     * {@inheritdoc}
     */
    public function delete(object $entity): void
    {
        /** @var ModelInterface $user */
        $user = $entity;
        $this->taskRepository->deleteTasksOlderThan(Carbon::now()->addDays(30), $user);
        $user->delete();
    }
}
