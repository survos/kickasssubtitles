<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace KickAssSubtitles\Encoding\Converter;

use Exception;
use KickAssSubtitles\Encoding\Encoding;
use KickAssSubtitles\Encoding\EncodingConverterInterface;
use KickAssSubtitles\Encoding\EncodingConverterTrait;
use function Safe\iconv;

/**
 * Class IconvConverter.
 */
class IconvEncodingConverter implements EncodingConverterInterface
{
    use EncodingConverterTrait;

    const ERR_MISSING_EXTENSION = 'Missing iconv extension';

    /**
     * @throws Exception
     */
    public function __construct()
    {
        if (!\function_exists('iconv')) {
            throw new Exception(static::ERR_MISSING_EXTENSION);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function convert(string $input, Encoding $from, Encoding $to): string
    {
        if ($to->equals($from)) {
            return $input;
        }

        $result = iconv($from->getValue(), $to->getValue().'//TRANSLIT//IGNORE', $input);

        return $result;
    }
}
