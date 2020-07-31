<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace KickAssSubtitles\Encoding\Detector;

use Exception;
use KickAssSubtitles\Encoding\Encoding;
use KickAssSubtitles\Encoding\EncodingDetectorInterface;
use KickAssSubtitles\Encoding\EncodingDetectorTrait;
use KickAssSubtitles\Language\Language;
use function Safe\mb_detect_order;

/**
 * Class MbDetector.
 */
class MbEncodingDetector implements EncodingDetectorInterface
{
    use EncodingDetectorTrait;

    const ERR_MISSING_EXTENSION = 'Missing mbstring extension';

    /**
     * @throws Exception
     */
    public function __construct()
    {
        if (!\function_exists('mb_detect_encoding')) {
            throw new Exception(static::ERR_MISSING_EXTENSION);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function detect(string $input, ?Language $language = null): Encoding
    {
        $encoding = mb_detect_encoding($input, mb_detect_order(), true);
        if (false === $encoding) {
            throw new Exception(static::ERR_DETECTION_FAILED);
        }

        return new Encoding($encoding);
    }
}
