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

use KickAssSubtitles\Encoding\Converter\IconvEncodingConverter;
use KickAssSubtitles\Encoding\Converter\MbEncodingConverter;
use Throwable;

/**
 * Class Converter.
 */
class EncodingConverter implements EncodingConverterInterface
{
    use EncodingConverterTrait;

    /**
     * @var array
     */
    protected $converters = [];

    /**
     * @return EncodingConverterInterface
     *
     * @throws Throwable
     */
    public static function create(): EncodingConverterInterface
    {
        $converters = [];

        try {
            $converters[] = new IconvEncodingConverter();
        } catch (Throwable $e) {
        }

        try {
            $converters[] = new MbEncodingConverter();
        } catch (Throwable $e) {
            throw $e;
        }

        return new static($converters);
    }

    /**
     * @param array $converters
     */
    public function __construct(array $converters)
    {
        $this->converters = $converters;
    }

    /**
     * {@inheritdoc}
     */
    public function convert(string $input, Encoding $from, Encoding $to): string
    {
        $exception = null;
        foreach ($this->converters as $converter) {
            try {
                return $converter->convert($input, $from, $to);
            } catch (Throwable $e) {
                $exception = $e;
            }
        }

        if ($exception) {
            throw $exception;
        }
    }
}
