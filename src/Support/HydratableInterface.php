<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace KickAssSubtitles\Support;

/**
 * Interface HydratableInterface.
 */
interface HydratableInterface
{
    const CLASS_ATTRIBUTE = '@class';

    /**
     * Hydrate plain array into an instance.
     */
    public static function hydrateFromArray(array $array = []): object;
}
