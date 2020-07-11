<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace App\Jobs;

use App\Repositories\UserRepository;
use KickAssSubtitles\Processor\Exception\TaskIsProcessingException;
use KickAssSubtitles\Support\UserInterface;
use Throwable;

/**
 * Class DeleteTemporaryUser.
 */
class DeleteTemporaryUser extends AbstractJob
{
    /**
     * @var UserInterface
     */
    protected $user;

    /**
     * @param UserInterface $user
     */
    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }

    /**
     * @param UserRepository $userRepository
     *
     * @throws Throwable
     */
    public function handle(UserRepository $userRepository)
    {
        try {
            $userRepository->delete($this->user);
        } catch (TaskIsProcessingException $e) {
            static::dispatch($this->user)->delay(now()->addMinutes(1));
        }
    }
}
