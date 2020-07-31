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

use MyCLabs\Enum\Enum;
use function Safe\getimagesize;
use function Safe\realpath;
use Throwable;

/**
 * @method static ImageType POSTER()
 */
class ImageType extends Enum
{
    const POSTER = 'poster';

    /**
     * Array format: [min-width, min-height, max-width, max-height].
     *
     * @var array
     */
    protected static $constraints = [
        self::POSTER => [75, 75, 300, 300],
    ];

    public function getMaxWidth(): int
    {
        return static::$constraints[$this->getValue()][2];
    }

    public function getMaxHeight(): int
    {
        return static::$constraints[$this->getValue()][3];
    }

    public function getMinWidth(): int
    {
        return static::$constraints[$this->getValue()][0];
    }

    public function getMinHeight(): int
    {
        return static::$constraints[$this->getValue()][1];
    }

    /**
     * @throws Throwable
     */
    public function matchesConstraints(string $file): bool
    {
        $file = realpath($file);
        $info = getimagesize($file);
        $width = $info[0];
        $height = $info[1];
        if (($width > $this->getMaxWidth()) || ($height > $this->getMaxHeight())) {
            return false;
        }
        if (($width < $this->getMinWidth()) || ($height < $this->getMinHeight())) {
            return false;
        }

        return true;
    }
}
