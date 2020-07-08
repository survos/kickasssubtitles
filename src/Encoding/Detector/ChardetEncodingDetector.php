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
use function Safe\fclose;
use function Safe\fwrite;
use function Safe\preg_match;
use function Safe\tmpfile;
use Symfony\Component\Process\Process;
use Throwable;

/**
 * Class ChardetDetector.
 */
class ChardetEncodingDetector implements EncodingDetectorInterface
{
    use EncodingDetectorTrait;

    const ERR_MISSING_EXECUTABLE = 'Missing chardetect or chardet executable';

    protected $executable;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $chardetect = `which chardetect`;
        $chardet = `which chardet`;

        if ($chardetect) {
            $this->executable = 'chardetect';

            return;
        }

        if ($chardet) {
            $this->executable = 'chardet';

            return;
        }

        throw new Exception(static::ERR_MISSING_EXECUTABLE);
    }

    /**
     * {@inheritdoc}
     */
    public function detect(string $input, ?Language $language = null): Encoding
    {
        $file = tmpfile();

        try {
            fwrite($file, $input);
            $path = stream_get_meta_data($file)['uri'];

            $process = new Process([
                $this->executable,
                $path,
            ]);
            $process->run();

            if (!$process->isSuccessful()) {
                throw new Exception(static::ERR_DETECTION_FAILED);
            }

            $output = $process->getOutput();

            if ((0 === preg_match('/(.+): (.+) .+confidence:? ([^\)]+)/', $output, $matches))
                || (isset($matches[2]) && 'None' === $matches[2])
            ) {
                throw new Exception(static::ERR_DETECTION_FAILED);
            }

            return new Encoding($matches[2]);
        } catch (Throwable $e) {
            fclose($file);

            throw $e;
        }
    }
}
