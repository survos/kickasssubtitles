<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace KickAssSubtitles\Processor;

use KickAssSubtitles\Support\HydratableInterface;
use KickAssSubtitles\Support\HydratableTrait;
use KickAssSubtitles\Support\TablelessModel;

/**
 * Class TaskOptions.
 */
class TaskOptions extends TablelessModel implements HydratableInterface
{
    use HydratableTrait;
}
