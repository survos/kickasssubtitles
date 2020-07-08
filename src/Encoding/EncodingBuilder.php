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

use function Safe\json_encode;
use Throwable;
use Umpirsky\ListGenerator\Importer\Importer;

/**
 * Class EncodingBuilder.
 */
class EncodingBuilder extends Importer
{
    /**
     * {@inheritdoc}
     */
    public function getLanguages()
    {
        return ['en'];
    }

    /**
     * Get data in given language.
     *
     * @param string $language
     *
     * @return string[]
     *
     * @throws Throwable
     */
    public function getData($language)
    {
        $encodings = [];
        foreach (Encoding::values() as $encoding) {
            $encodings[] = json_encode($encoding->asArray());
        }

        return $encodings;
    }
}
