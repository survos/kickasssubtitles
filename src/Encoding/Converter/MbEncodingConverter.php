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

/**
 * Class MbConverter.
 */
class MbEncodingConverter implements EncodingConverterInterface
{
    use EncodingConverterTrait;

    const ERR_MISSING_EXTENSION = 'Missing mbstring extension';

    /**
     * @throws Exception
     */
    public function __construct()
    {
        if (!\function_exists('mb_convert_encoding')) {
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

        set_error_handler(function (int $errno, string $errstr) {
            throw new Exception($errstr, $errno);
        });
        $result = mb_convert_encoding(
            $input,
            $to->getValue(),
            $from->getValue()
        );
        restore_error_handler();

        return $result;
    }
}
