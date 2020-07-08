<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace KickAssSubtitles\Subtitle\Converter;

use Done\Subtitles\Subtitles;
use KickAssSubtitles\Processor\TaskInterface;
use KickAssSubtitles\Storage\HasStorageInterface;
use KickAssSubtitles\Subtitle\SubtitleConversionOptions;
use KickAssSubtitles\Subtitle\SubtitleFormat;
use KickAssSubtitles\Support\EnumMapper;
use function Safe\file_get_contents;
use function Safe\sprintf;

/**
 * Class PhpConverter.
 */
class PhpSubtitleConverter extends AbstractSubtitleConverter
{
    const SRT = 'srt';

    const VTT = 'vtt';

    const STL = 'stl';

    const SBV = 'sbv';

    const SUB = 'sub';

    const ASS = 'ass';

    const DFXP = 'dfxp';

    const TTML = 'ttml';

    const QUICK_TIME = 'qt.txt';

    /**
     * {@inheritdoc}
     */
    public function createFormatMapper(): EnumMapper
    {
        return EnumMapper::create([
            [SubtitleFormat::SUBRIP => static::SRT],
            [SubtitleFormat::WEBVTT => static::VTT],
            [SubtitleFormat::SAMI => static::STL],
            [SubtitleFormat::YOUTUBESBV => static::SBV],
            [SubtitleFormat::SUBVIEWER => static::SUB],
            [SubtitleFormat::SUBVIEWER2 => static::SUB],
            [SubtitleFormat::SUBSTATIONALPHAADVANCED => static::ASS],
            [SubtitleFormat::DFXP => static::DFXP],
            [SubtitleFormat::TIMEDTEXT => static::TTML],
            [SubtitleFormat::QUICKTIMETEXT => static::QUICK_TIME],
        ], SubtitleFormat::class);
    }

    /**
     * {@inheritdoc}
     */
    protected function processTask(TaskInterface $task): void
    {
        /** @var SubtitleConversionOptions $options */
        $options = $task->getOptions();

        $outputSubtitleFormat = $this->getFormatMapper()->to($options->getFormat());

        /** @var HasStorageInterface $taskWithStorage */
        $taskWithStorage = $task;
        $storage = $taskWithStorage->getStorage();

        $inputFile = $storage->getFile($task::STORAGE_INPUT);

        $inputSubtitleFormat = $this->subtitleFormatDetector->detectFile($inputFile);

        $subtitles = Subtitles::load(
            file_get_contents($inputFile),
            $this->getFormatMapper()->to($inputSubtitleFormat)
        );

        $outputFileName = sprintf(
            '%s.%s',
            $storage->getFile(
                $task::STORAGE_INPUT,
                PATHINFO_FILENAME
            ),
            $outputSubtitleFormat
        );
        $outputFile = $storage->tmpFile($outputFileName);

        $subtitles->save($outputFile);

        $storage->addFile($task::STORAGE_OUTPUT, $outputFile);
    }
}
