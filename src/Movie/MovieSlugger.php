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

use Illuminate\Support\Str;
use KickAssSubtitles\Support\SluggerInterface;

/**
 * Class MovieSlugger.
 */
class MovieSlugger implements SluggerInterface
{
    const PREFIXES = [
        'the',
        'a',
    ];

    /**
     * {@inheritdoc}
     */
    public function slugify(string $input): string
    {
        $slug = Str::slug($input, static::SEPARATOR);

        $slugArr = \explode(static::SEPARATOR, $slug);
        if (\in_array($slugArr[0], static::PREFIXES, true)) {
            $prefix = \array_shift($slugArr);
            $slug = \implode(static::SEPARATOR, $slugArr).static::SEPARATOR.$prefix;
        }

        return $slug;
    }
}
