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

use MyCLabs\Enum\Enum;

/**
 * @method static SubtitleProvider OPENSUBTITLES()
 * @method static SubtitleProvider NAPIPROJEKT()
 * @method static SubtitleProvider KICKASSSUBTITLES()
 */
class SubtitleProvider extends Enum
{
    const OPENSUBTITLES = 'opensubtitles';

    const NAPIPROJEKT = 'napiprojekt';

    const KICKASSSUBTITLES = 'kickasssubtitles';

    /**
     * @var array
     */
    protected static $names = [
        self::OPENSUBTITLES => 'OpenSubtitles',
        self::NAPIPROJEKT => 'NapiProjekt',
        self::KICKASSSUBTITLES => 'KickAssSubtitles',
    ];

    public function getName(): string
    {
        return self::$names[$this->getValue()];
    }

    public function getHashStorageField(): string
    {
        return 'hash_'.$this->getValue();
    }
}
