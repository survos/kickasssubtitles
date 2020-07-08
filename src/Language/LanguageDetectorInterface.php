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

use Throwable;

/**
 * Interface LanguageDetectorInterface.
 */
interface LanguageDetectorInterface
{
    /**
     * @param string $input
     *
     * @return Language
     *
     * @throws Throwable
     */
    public function detect(string $input): Language;

    /**
     * @param string $file
     *
     * @return Language
     *
     * @throws Throwable
     */
    public function detectFile(string $file): Language;
}
