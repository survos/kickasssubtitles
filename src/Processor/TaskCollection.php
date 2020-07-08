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

use Illuminate\Support\Collection;

/**
 * Class TaskCollection.
 */
class TaskCollection extends Collection implements TaskCollectionInterface
{
    use TaskCollectionTrait;
}
