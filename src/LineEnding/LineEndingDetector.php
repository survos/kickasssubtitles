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

use KickAssSubtitles\LineEnding\Detector\FileLineEndingDetector;
use Throwable;

/**
 * Class LineEndingDetector.
 */
class LineEndingDetector implements LineEndingDetectorInterface
{
    use LineEndingDetectorTrait;

    /**
     * @var array
     */
    protected $detectors = [];

    /**
     * @return LineEndingDetectorInterface
     *
     * @throws Throwable
     */
    public static function create()
    {
        $detectors = [];

        try {
            $detectors[] = new FileLineEndingDetector();
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
    public function detectFile(string $file): LineEnding
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
