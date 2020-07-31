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

/**
 * Class LineEndingConverterDetector.
 */
class LineEndingConverterDetector implements LineEndingConverterDetectorInterface
{
    /**
     * @var LineEndingConverterInterface
     */
    protected $converter;

    /**
     * @var LineEndingDetectorInterface
     */
    protected $detector;

    public function __construct(
        LineEndingConverterInterface $converter,
        LineEndingDetectorInterface $detector
    ) {
        $this->converter = $converter;
        $this->detector = $detector;
    }

    /**
     * {@inheritdoc}
     */
    public function convert(string $input, LineEnding $from, LineEnding $to): string
    {
        return $this->converter->convert($input, $from, $to);
    }

    /**
     * {@inheritdoc}
     */
    public function convertFile(string $file, LineEnding $from, LineEnding $to): void
    {
        $this->converter->convertFile($file, $from, $to);
    }

    /**
     * {@inheritdoc}
     */
    public function detect(string $input): LineEnding
    {
        return $this->detector->detect($input);
    }

    /**
     * {@inheritdoc}
     */
    public function detectFile(string $file): LineEnding
    {
        return $this->detector->detectFile($file);
    }
}
