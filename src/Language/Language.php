<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace KickAssSubtitles\Language;

use MyCLabs\Enum\Enum;

/**
 * @method static Language EN()
 * @method static Language PL()
 * @method static Language ES()
 * @method static Language RU()
 * @method static Language HI()
 * @method static Language AR()
 * @method static Language JA()
 * @method static Language DE()
 * @method static Language FR()
 * @method static Language IT()
 * @method static Language UK()
 */
class Language extends Enum
{
    /**
     * ISO6391.
     *
     * @see https://en.wikipedia.org/wiki/List_of_ISO_639-2_codes
     */
    const EN = 'en';

    const PL = 'pl';

    const ES = 'es';

    const RU = 'ru';

    const HI = 'hi';

    const AR = 'ar';

    const JA = 'ja';

    const DE = 'de';

    const FR = 'fr';

    const IT = 'it';

    const UK = 'uk';

    /**
     * Map ISO6391 to ISO6392.
     *
     * @var array
     */
    protected static $iso6392 = [
        self::EN => 'eng',
        self::PL => 'pol',
        self::ES => 'spa',
        self::RU => 'rus',
        self::HI => 'hin',
        self::AR => 'ara',
        self::JA => 'jpn',
        self::DE => 'deu',
        self::FR => 'fra',
        self::IT => 'ita',
        self::UK => 'ukr',
    ];

    /**
     * Map ISO6391 to english name.
     *
     * @var array
     */
    protected static $names = [
        self::EN => 'english',
        self::PL => 'polish',
        self::ES => 'spanish',
        self::RU => 'russian',
        self::HI => 'hindi',
        self::AR => 'arabic',
        self::JA => 'japanese',
        self::DE => 'german',
        self::FR => 'french',
        self::IT => 'italian',
        self::UK => 'ukrainian',
    ];

    /**
     * @return string
     */
    public function getIso6392(): string
    {
        return static::$iso6392[$this->getValue()];
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return static::$names[$this->getValue()];
    }
}
