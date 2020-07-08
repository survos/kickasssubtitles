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
use function Safe\file_get_contents;
use Throwable;

/**
 * Trait EncodingDetectorTrait.
 */
trait EncodingDetectorTrait
{
    /**
     * @param string        $file
     * @param Language|null $language
     *
     * @return Encoding
     *
     * @throws Throwable
     */
    public function detectFile(string $file, ?Language $language = null): Encoding
    {
        $input = file_get_contents($file);

        return $this->detect($input, $language);
    }
}
