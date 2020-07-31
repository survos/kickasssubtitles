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

use KickAssSubtitles\Encoding\Encoding;
use KickAssSubtitles\Language\Language;
use KickAssSubtitles\Movie\VideoHashesTrait;
use KickAssSubtitles\Processor\TaskOptions;
use KickAssSubtitles\Support\ObjectCastsTrait;

/**
 * Class SubtitleSearchOptions.
 */
class SubtitleSearchOptions extends TaskOptions implements SubtitleOptionsInterface
{
    use ObjectCastsTrait;
    use SubtitleOptionsTrait;
    use VideoHashesTrait;

    /**
     * @var array
     */
    protected $casts = [
        self::FILESIZE => 'integer',
    ];

    /**
     * @var array
     */
    protected $attributes = [
        self::FILENAME => self::DEFAULT_FILENAME,
        self::FILESIZE => 0,
        SubtitleInterface::ENCODING => Encoding::UTF_8,
        SubtitleInterface::FORMAT => SubtitleFormat::SUBRIP,
        SubtitleInterface::LANGUAGE => Language::EN,
    ];

    public function getLanguage(): Language
    {
        return $this->{SubtitleInterface::LANGUAGE};
    }

    public function needsConversion(SubtitleInterface $subtitle): bool
    {
        if (!$subtitle->getFormat()->equals($this->getFormat())) {
            return true;
        }

        if (!$subtitle->getEncoding()->equals($this->getEncoding())) {
            return true;
        }

        return false;
    }

    protected function getObjectCasts(): array
    {
        return [
            SubtitleInterface::ENCODING => Encoding::class,
            SubtitleInterface::FORMAT => SubtitleFormat::class,
            SubtitleInterface::LANGUAGE => Language::class,
        ];
    }
}
