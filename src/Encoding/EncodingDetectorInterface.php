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

use KickAssSubtitles\Language\Language;
use Throwable;

/**
 * Interface EncodingDetectorInterface.
 */
interface EncodingDetectorInterface
{
    const ERR_DETECTION_FAILED = 'Detection failed';

    /**
     * @param string        $input
     * @param Language|null $language
     *
     * @return Encoding
     *
     * @throws Throwable
     */
    public function detect(string $input, ?Language $language = null): Encoding;

    /**
     * @param string        $file
     * @param Language|null $language
     *
     * @return Encoding
     *
     * @throws Throwable
     */
    public function detectFile(string $file, ?Language $language = null): Encoding;
}
