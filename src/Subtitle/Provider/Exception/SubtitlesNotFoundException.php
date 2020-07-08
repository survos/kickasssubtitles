<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace KickAssSubtitles\Subtitle\Provider\Exception;

use Exception;
use Throwable;

/**
 * Class SubtitlesNotFoundException.
 */
class SubtitlesNotFoundException extends Exception
{
    public function __construct(
        $message = 'Subtitles not found',
        $code = 0,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
