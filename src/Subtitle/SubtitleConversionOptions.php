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
use KickAssSubtitles\Movie\VideoFps;
use KickAssSubtitles\Processor\TaskOptions;
use KickAssSubtitles\Support\ObjectCastsTrait;
use function Safe\realpath;
use Throwable;

/**
 * Class SubtitleConversionOptions.
 */
class SubtitleConversionOptions extends TaskOptions implements SubtitleOptionsInterface
{
    use SubtitleOptionsTrait;
    use ObjectCastsTrait;

    const DEFAULT_FILE = DIRECTORY_SEPARATOR.'tmp'.DIRECTORY_SEPARATOR.'nonexistentfile';

    const FILE = 'file';

    const FPS = 'fps';

    const INPUT_ENCODING = 'input_encoding';

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
        self::FILE => self::DEFAULT_FILE,
        self::INPUT_ENCODING => null,
        SubtitleInterface::LANGUAGE => null,
        self::FPS => null,
    ];

    /**
     * @return string
     */
    public function getFile(): string
    {
        return $this->getAttribute(static::FILE);
    }

    /**
     * @param string $file
     *
     * @throws Throwable
     */
    public function setFile(string $file): void
    {
        $this->setAttribute(static::FILE, realpath($file));
    }

    /**
     * @return Encoding|null
     */
    public function getInputEncoding(): ?Encoding
    {
        return $this->getAttribute(static::INPUT_ENCODING);
    }

    /**
     * @return Language|null
     */
    public function getLanguage(): ?Language
    {
        return $this->getAttribute(SubtitleInterface::LANGUAGE);
    }

    /**
     * @return VideoFps|null
     */
    public function getFps(): ?VideoFps
    {
        return $this->getAttribute(static::FPS);
    }

    /**
     * @return array
     */
    protected function getObjectCasts(): array
    {
        return [
            SubtitleInterface::ENCODING => Encoding::class,
            static::INPUT_ENCODING => '?'.Encoding::class,
            SubtitleInterface::FORMAT => SubtitleFormat::class,
            SubtitleInterface::LANGUAGE => '?'.Language::class,
            static::FPS => '?'.VideoFps::class,
        ];
    }
}
