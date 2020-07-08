<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace App\Console;

use Illuminate\Console\Application as Artisan;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use function Safe\filesize;
use function Safe\unlink;

/**
 * Class Kernel.
 */
class Kernel extends ConsoleKernel
{
    /**
     * The Artisan application instance.
     *
     * @var null|Artisan
     */
    protected $artisan;

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
    ];

    /**
     * {@inheritdoc}
     */
    protected function getArtisan()
    {
        if (\is_null($this->artisan)) {
            $artisan = new Artisan(
                $this->app,
                $this->events,
                $this->app->version()
            );
            $artisan->resolveCommands($this->commands);
            $artisan->setName(\implode(' - ', [
                config('app.name'),
                $artisan->getName(),
            ]));

            return $this->artisan = $artisan;
        }

        return $this->artisan;
    }

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     */
    protected function schedule(Schedule $schedule)
    {
        $schedulerLog = storage_path('logs'.DIRECTORY_SEPARATOR.'scheduler.log');

        if (file_exists($schedulerLog) && (filesize($schedulerLog) > 10485760)) {
            unlink($schedulerLog);
        }

        $schedule
            ->command('app:purge')
            ->withoutOverlapping()
            ->everyFifteenMinutes()
            ->appendOutputTo($schedulerLog)
        ;

        $schedule
            ->command('queue:flush')
            ->withoutOverlapping()
            ->weekly()
            ->appendOutputTo($schedulerLog)
        ;
    }

    /**
     * Register the commands for the application.
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        $this->load(__DIR__.'/Debug');
    }
}
