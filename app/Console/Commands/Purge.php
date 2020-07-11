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
use Illuminate\Support\Carbon;
use KickAssSubtitles\Processor\TaskRepositoryInterface;

/**
 * Class Purge.
 */
class Purge extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:purge';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Purge app database from old items';

    /**
     * @var TaskRepositoryInterface
     */
    protected $taskRepository;

    /**
     * @param TaskRepositoryInterface $taskRepository
     */
    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        parent::__construct();
        $this->taskRepository = $taskRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        parent::handle();

        $this->info('Purging old tasks');
        $this->taskRepository->deleteTasksOlderThan(Carbon::now()->subDays(30));
    }
}
