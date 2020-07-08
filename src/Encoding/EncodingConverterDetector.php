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

/**
 * Class EncodingConverterDetector.
 */
class EncodingConverterDetector implements EncodingConverterDetectorInterface
{
    /**
     * @var EncodingConverterInterface
     */
    protected $converter;

    /**
     * @var EncodingDetectorInterface
     */
    protected $detector;

    /**
     * @param EncodingConverterInterface $converter
     * @param EncodingDetectorInterface  $detector
     */
    public function __construct(
        EncodingConverterInterface $converter,
        EncodingDetectorInterface $detector
    ) {
        $this->converter = $converter;
        $this->detector = $detector;
    }

    /**
     * {@inheritdoc}
     */
    public function convert(string $input, Encoding $from, Encoding $to): string
    {
        return $this->converter->convert($input, $from, $to);
    }

    /**
     * {@inheritdoc}
     */
    public function convertFile(string $file, Encoding $from, Encoding $to): void
    {
        $this->converter->convertFile($file, $from, $to);
    }

    /**
     * {@inheritdoc}
     */
    public function detect(string $input, ?Language $language = null): Encoding
    {
        return $this->detector->detect($input, $language);
    }

    /**
     * {@inheritdoc}
     */
    public function detectFile(string $file, ?Language $language = null): Encoding
    {
        return $this->detector->detectFile($file, $language);
    }
}
