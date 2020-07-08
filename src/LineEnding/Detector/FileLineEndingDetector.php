<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace KickAssSubtitles\LineEnding\Detector;

use Exception;
use KickAssSubtitles\LineEnding\LineEnding;
use KickAssSubtitles\LineEnding\LineEndingDetectorInterface;
use KickAssSubtitles\LineEnding\LineEndingDetectorTrait;
use function Safe\realpath;
use Symfony\Component\Process\Process;

/**
 * Class FileDetector.
 */
class FileLineEndingDetector implements LineEndingDetectorInterface
{
    use LineEndingDetectorTrait;

    const ERR_MISSING_EXECUTABLE = 'Missing file executable';

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $file = `which file`;

        if ($file) {
            return;
        }

        throw new Exception(static::ERR_MISSING_EXECUTABLE);
    }

    /**
     * {@inheritdoc}
     */
    public function detectFile(string $file): LineEnding
    {
        $file = realpath($file);

        $process = new Process([
            'file',
            $file,
        ]);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new Exception(static::ERR_DETECTION_FAILED);
        }

        $output = array_map(function ($el) {
            $el = trim($el);
            $el = str_replace(',', '', $el);

            return $el;
        }, explode(' ', $process->getOutput()));

        foreach ($output as $el) {
            if ($el === strtoupper(LineEnding::DOS()->getControlCode())) {
                return LineEnding::DOS();
            }
            if ($el === strtoupper(LineEnding::MAC()->getControlCode())) {
                return LineEnding::MAC();
            }
            if ($el === strtoupper(LineEnding::UNIX()->getControlCode())) {
                return LineEnding::UNIX();
            }
        }

        return LineEnding::UNIX();
    }
}
