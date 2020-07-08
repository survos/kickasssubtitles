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

use KickAssSubtitles\Encoding\Detector\ChardetEncodingDetector;
use KickAssSubtitles\Encoding\Detector\CharedEncodingDetector;
use KickAssSubtitles\Encoding\Detector\MbEncodingDetector;
use KickAssSubtitles\Language\Language;
use Throwable;

/**
 * Class EncodingDetector.
 */
class EncodingDetector implements EncodingDetectorInterface
{
    use EncodingDetectorTrait;

    /**
     * @var array
     */
    protected $detectors = [];

    /**
     * @return EncodingDetectorInterface
     *
     * @throws Throwable
     */
    public static function create()
    {
        $detectors = [];

        try {
            $detectors[] = new ChardetEncodingDetector();
        } catch (Throwable $e) {
        }

        try {
            $detectors[] = new MbEncodingDetector();
        } catch (Throwable $e) {
        }

        try {
            $detectors[] = new CharedEncodingDetector();
        } catch (Throwable $e) {
            throw $e;
        }

        return new static($detectors);
    }

    /**
     * @param array $detectors
     */
    public function __construct(array $detectors)
    {
        $this->detectors = $detectors;
    }

    /**
     * {@inheritdoc}
     */
    public function detect(string $input, ?Language $language = null): Encoding
    {
        $exception = null;
        foreach ($this->detectors as $detector) {
            try {
                return $detector->detect($input, $language);
            } catch (Throwable $e) {
                $exception = $e;
            }
        }

        if ($exception) {
            throw $exception;
        }
    }
}
