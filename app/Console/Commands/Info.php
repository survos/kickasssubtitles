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

/**
 * Class Info.
 */
class Info extends Command
{
    /**
     * @var string
     */
    protected $signature = 'app:info';

    /**
     * @var string
     */
    protected $description = 'Displays basic application info';

    public function handle()
    {
        parent::handle();

        $info = [
            'name' => config('app.name'),
            'environment' => app()->environment(),
            'debug' => config('app.debug'),
        ];

        foreach ($info as $k => $v) {
            $this->info(sprintf('%s: %s', $k, $v));
        }
    }
}
