<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace KickAssSubtitles\Subtitle;

use KickAssSubtitles\Subtitle\Detector\ExtensionSubtitleFormatDetector;
use KickAssSubtitles\Subtitle\Detector\SubotageSubtitleFormatDetector;
use Throwable;

/**
 * Class SubtitleFormatDetector.
 */
class SubtitleFormatDetector implements SubtitleFormatDetectorInterface
{
    /**
     * @var array
     */
    protected $detectors = [];

    /**
     * @return SubtitleFormatDetectorInterface
     *
     * @throws Throwable
     */
    public static function create(): SubtitleFormatDetectorInterface
    {
        $detectors = [];

        try {
            $detectors[] = new SubotageSubtitleFormatDetector();
        } catch (Throwable $e) {
        }

        try {
            $detectors[] = new ExtensionSubtitleFormatDetector();
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
    public function detectFile(string $file): SubtitleFormat
    {
        $exception = null;
        foreach ($this->detectors as $detector) {
            try {
                return $detector->detectFile($file);
            } catch (Throwable $e) {
                $exception = $e;
            }
        }

        if ($exception) {
            throw $exception;
        }
    }
}
