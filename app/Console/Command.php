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

use Illuminate\Console\Command as BaseCommand;
use Illuminate\Support\Carbon;

/**
 * Class Command.
 */
class Command extends BaseCommand
{
    public function info($string, $verbosity = null)
    {
        parent::info(sprintf(
            '[%s] %s',
            Carbon::now()->toDateTimeString(),
            $string
        ), $verbosity);
    }

    public function error($string, $verbosity = null)
    {
        parent::error(sprintf(
            '[%s] %s',
            Carbon::now()->toDateTimeString(),
            $string
        ), $verbosity);
    }

    public function handle()
    {
        $info = sprintf('Running command [%s]', $this->signature);
        $this->info($info);
        $this->info(str_repeat('=', strlen($info)));
    }
}
