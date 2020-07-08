<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace App\Models\Collections;

use Illuminate\Database\Eloquent\Collection;
use KickAssSubtitles\Subtitle\SubtitleCollectionInterface;
use KickAssSubtitles\Subtitle\SubtitleCollectionTrait;

/**
 * Class SubtitleCollection.
 */
class SubtitleCollection extends Collection implements SubtitleCollectionInterface
{
    use SubtitleCollectionTrait;
}
