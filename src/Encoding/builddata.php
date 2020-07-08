<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

require __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

use KickAssSubtitles\Encoding\EncodingBuilder;
use Umpirsky\ListGenerator\Builder\Builder;

(new Builder(
    new EncodingBuilder(),
    __DIR__.DIRECTORY_SEPARATOR.'Data'
))->run();
