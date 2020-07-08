<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace KickAssSubtitles\LineEnding\Converter;

use Exception;
use KickAssSubtitles\LineEnding\LineEnding;
use KickAssSubtitles\LineEnding\LineEndingConverterInterface;
use KickAssSubtitles\LineEnding\LineEndingConverterTrait;
use function Safe\realpath;
use Symfony\Component\Process\Process;

/**
 * Class Dos2UnixConverter.
 */
class Dos2UnixLineEndingConverter implements LineEndingConverterInterface
{
    use LineEndingConverterTrait;

    const ERR_MISSING_EXECUTABLE = 'Missing dos2unix executable';

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $executable = `which dos2unix`;

        if (!$executable) {
            throw new Exception(static::ERR_MISSING_EXECUTABLE);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function convertFile(string $file, LineEnding $from, LineEnding $to): void
    {
        if ($to->equals($from)) {
            return;
        }

        $file = realpath($file);

        if (!$from->equals(LineEnding::UNIX())) {
            $process = new Process([$from->getValue().'2unix', $file]);
            $process->run();
            if (!$process->isSuccessful()) {
                throw new Exception(static::ERR_CONVERSION_FAILED);
            }
        }

        if (!$to->equals(LineEnding::UNIX())) {
            $process = new Process(['unix2'.$to->getValue(), $file]);
            $process->run();
            if (!$process->isSuccessful()) {
                throw new Exception(static::ERR_CONVERSION_FAILED);
            }
        }
    }
}
