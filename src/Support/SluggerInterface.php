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
 * Interface SluggerInterface.
 */
interface SluggerInterface
{
    const SEPARATOR = '-';

    /**
     * @param string $input
     *
     * @return string
     */
    public function slugify(string $input): string;
}
