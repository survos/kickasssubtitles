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
 * Class PurgeTasks.
 */
class PurgeTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:purge-tasks {--days=14}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Purge app database from old tasks';

    /**
     * @var TaskRepositoryInterface
     */
    protected $taskRepository;

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

        $days = (int) ($this->option('days'));

        $this->info('Purging old tasks');
        $this->taskRepository->deleteTasksOlderThan(Carbon::now()->subDays($days));
    }
}
