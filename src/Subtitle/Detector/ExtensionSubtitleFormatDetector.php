<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace KickAssSubtitles\Subtitle\Detector;

use KickAssSubtitles\Subtitle\SubtitleFormat;
use KickAssSubtitles\Subtitle\SubtitleFormatDetectorInterface;
use function Safe\realpath;

/**
 * Class ExtensionSubtitleFormatDetector.
 */
class ExtensionSubtitleFormatDetector implements SubtitleFormatDetectorInterface
{
    /**
     * {@inheritdoc}
     */
    public function detectFile(string $file): SubtitleFormat
    {
        $path = realpath($file);

        $extension = pathinfo($path, \PATHINFO_EXTENSION);

        return SubtitleFormat::createFromExtension($extension);
    }
}
