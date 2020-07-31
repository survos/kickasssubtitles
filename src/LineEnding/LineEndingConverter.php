<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace KickAssSubtitles\LineEnding;

use Exception;
use KickAssSubtitles\LineEnding\Converter\Dos2UnixLineEndingConverter;

/**
 * Class Converter.
 */
class LineEndingConverter implements LineEndingConverterInterface
{
    use LineEndingConverterTrait;

    /**
     * @var array
     */
    protected $converters = [];

    /**
     * @throws Exception
     */
    public static function create(): LineEndingConverterInterface
    {
        $converters = [];

        try {
            $converters[] = new Dos2UnixLineEndingConverter();
        } catch (Exception $e) {
            throw $e;
        }

        return new static($converters);
    }

    public function __construct(array $converters)
    {
        $this->converters = $converters;
    }

    /**
     * {@inheritdoc}
     */
    public function convertFile(string $file, LineEnding $from, LineEnding $to): void
    {
        $exception = null;
        foreach ($this->converters as $converter) {
            try {
                $converter->convertFile($file, $from, $to);

                return;
            } catch (Exception $e) {
                $exception = $e;
            }
        }

        if ($exception) {
            throw $exception;
        }
    }
}
