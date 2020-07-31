<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace KickAssSubtitles\LineEnding;

use MyCLabs\Enum\Enum;

/**
 * @method static LineEnding UNIX()
 * @method static LineEnding DOS()
 * @method static LineEnding MAC()
 */
class LineEnding extends Enum
{
    const UNIX = 'unix';

    const DOS = 'dos';

    const MAC = 'mac';

    /**
     * @var array
     */
    protected static $controlCodes = [
        self::UNIX => 'lf',
        self::DOS => 'crlf',
        self::MAC => 'cr',
    ];

    public function getControlCode(): string
    {
        return static::$controlCodes[$this->getValue()];
    }
}
