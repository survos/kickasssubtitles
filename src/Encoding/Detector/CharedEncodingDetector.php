<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace KickAssSubtitles\Encoding\Detector;

use Exception;
use KickAssSubtitles\Encoding\Encoding;
use KickAssSubtitles\Encoding\EncodingDetectorInterface;
use KickAssSubtitles\Encoding\EncodingDetectorTrait;
use KickAssSubtitles\Language\Language;
use Symfony\Component\Process\Process;

/**
 * Class CharedDetector.
 */
class CharedEncodingDetector implements EncodingDetectorInterface
{
    use EncodingDetectorTrait;

    const ERR_MISSING_EXECUTABLE = 'Missing chared executable';

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $chared = shell_exec('which chared');

        if (!$chared) {
            throw new Exception(static::ERR_MISSING_EXECUTABLE);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function detect(string $input, ?Language $language = null): Encoding
    {
        if (null === $language) {
            throw new Exception(static::ERR_DETECTION_FAILED);
        }

        $process = new Process(['chared', '-m ', $language->getName()]);
        $process->setInput($input);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new Exception(static::ERR_DETECTION_FAILED);
        }

        $output = $process->getOutput();
        $output = str_replace("\n", '', $output);

        return new Encoding($output);
    }
}
