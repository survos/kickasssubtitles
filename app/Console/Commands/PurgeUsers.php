<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace App\Console\Commands;

use App\Console\Command;
use App\Repositories\UserRepository;
use Illuminate\Support\Carbon;

/**
 * Class PurgeUsers.
 */
class PurgeUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:purge-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Purge app database from old users';

    /**
     * @var UserRepository
     */
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        parent::__construct();
        $this->userRepository = $userRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        parent::handle();

        $this->info('Purging inactive temporary users');
        $this->userRepository->deleteTemporaryUsersInactiveSince(Carbon::now()->subDays(60));
    }
}
