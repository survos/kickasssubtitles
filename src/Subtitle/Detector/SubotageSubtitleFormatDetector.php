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

use Exception;
use KickAssSubtitles\Subtitle\SubotageTrait;
use KickAssSubtitles\Subtitle\SubtitleFormat;
use KickAssSubtitles\Subtitle\SubtitleFormatDetectorInterface;
use KickAssSubtitles\Support\Str;
use function Safe\realpath;
use Symfony\Component\Process\Process;

/**
 * Class SubotageSubtitleFormatDetector.
 */
class SubotageSubtitleFormatDetector implements SubtitleFormatDetectorInterface
{
    use SubotageTrait;

    /**
     * {@inheritdoc}
     */
    public function detectFile(string $file): SubtitleFormat
    {
        $path = realpath($file);

        $cmd = [
            'subotage.sh',
            '--input',
            $path,
            '--get-info',
        ];

        $process = new Process($cmd);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new Exception(static::ERR_DETECTION_FAILED);
        }

        $output = explode(PHP_EOL, $process->getOutput());

        foreach ($output as $line) {
            if (Str::contains($line, 'IN_FORMAT')) {
                $lineArray = explode('->', $line);
                $format = trim(end($lineArray));

                return $this->createFormatMapper()->from($format);
            }
        }

        throw new Exception(static::ERR_DETECTION_FAILED);
    }
}
