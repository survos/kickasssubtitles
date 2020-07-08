<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace KickAssSubtitles\Encoding;

use Throwable;

/**
 * Interface EncodingConverterInterface.
 */
interface EncodingConverterInterface
{
    const ERR_CONVERSION_FAILED = 'Conversion failed';

    /**
     * @param string   $input
     * @param Encoding $from
     * @param Encoding $to
     *
     * @return string
     *
     * @throws Throwable
     */
    public function convert(string $input, Encoding $from, Encoding $to): string;

    /**
     * @param string   $file
     * @param Encoding $from
     * @param Encoding $to
     *
     * @throws Throwable
     */
    public function convertFile(string $file, Encoding $from, Encoding $to): void;
}
