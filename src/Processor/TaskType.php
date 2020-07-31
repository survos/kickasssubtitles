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

use MyCLabs\Enum\Enum;

/**
 * @method static TaskType DEFAULT()
 * @method static TaskType TEST()
 * @method static TaskType MOVIE_SEARCH()
 * @method static TaskType SUBTITLE_SEARCH()
 * @method static TaskType SUBTITLE_CONVERSION()
 */
class TaskType extends Enum
{
    const DEFAULT = 'default';

    const TEST = 'test';

    const MOVIE_SEARCH = 'movie_search';

    const SUBTITLE_SEARCH = 'subtitle_search';

    const SUBTITLE_CONVERSION = 'subtitle_conversion';

    protected static function getOptionsClassNameMap(): array
    {
        $map = [];

        if (class_exists('KickAssSubtitles\Movie\MovieSearchOptions')) {
            $map[static::MOVIE_SEARCH] = \KickAssSubtitles\Movie\MovieSearchOptions::class;
        }

        if (class_exists('KickAssSubtitles\Subtitle\SubtitleSearchOptions')) {
            $map[static::SUBTITLE_SEARCH] = \KickAssSubtitles\Subtitle\SubtitleSearchOptions::class;
        }

        if (class_exists('KickAssSubtitles\Subtitle\SubtitleConversionOptions')) {
            $map[static::SUBTITLE_CONVERSION] = \KickAssSubtitles\Subtitle\SubtitleConversionOptions::class;
        }

        return $map;
    }

    public function getOptionsClassName(): string
    {
        $value = $this->getValue();
        $map = static::getOptionsClassNameMap();

        if (isset($map[$value])) {
            return $map[$value];
        }

        return TaskOptions::class;
    }
}
