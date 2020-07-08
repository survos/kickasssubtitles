<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace KickAssSubtitles\Movie;

use KickAssSubtitles\Support\HydratableInterface;
use KickAssSubtitles\Support\HydratableTrait;
use KickAssSubtitles\Support\ObjectCastsTrait;
use KickAssSubtitles\Support\TablelessModel;

/**
 * Class Movie.
 */
class Movie extends TablelessModel implements MovieInterface, HydratableInterface
{
    use MovieTrait;
    use ObjectCastsTrait;
    use HydratableTrait;

    /**
     * @return array
     */
    protected function getObjectCasts(): array
    {
        return [
            static::TYPE => MovieType::class,
            static::PROVIDER => MovieProvider::class,
            static::PROVIDER_PREVIOUS => '?'.MovieProvider::class,
        ];
    }
}
