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

use KickAssSubtitles\Support\EnumMapper;

/**
 * Trait SubotageTrait.
 */
trait SubotageTrait
{
    /**
     * @return EnumMapper
     */
    public function createFormatMapper(): EnumMapper
    {
        return EnumMapper::create([
            [SubtitleFormat::MICRODVD => SubtitleFormat::MICRODVD],
            [SubtitleFormat::MPLAYER2 => 'mpl2'],
            [SubtitleFormat::SUBRIP => SubtitleFormat::SUBRIP],
            [SubtitleFormat::SUBVIEWER2 => SubtitleFormat::SUBVIEWER2],
            [SubtitleFormat::TMPLAYER => SubtitleFormat::TMPLAYER],
        ], SubtitleFormat::class);
    }
}
