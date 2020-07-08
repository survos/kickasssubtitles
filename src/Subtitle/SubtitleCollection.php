<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace KickAssSubtitles\Subtitle;

use Illuminate\Support\Collection;

/**
 * Class SubtitleCollection.
 */
class SubtitleCollection extends Collection implements SubtitleCollectionInterface
{
    use SubtitleCollectionTrait;
}
