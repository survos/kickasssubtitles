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

use KickAssSubtitles\Processor\TaskInterface;
use KickAssSubtitles\Storage\HasStorageInterface;
use KickAssSubtitles\Subtitle\Converter\Exception\ConversionFailedException;
use KickAssSubtitles\Subtitle\SubotageTrait;
use KickAssSubtitles\Subtitle\SubtitleConversionOptions;
use KickAssSubtitles\Subtitle\SubtitleFormat;
use function Safe\sprintf;
use Symfony\Component\Process\Process;

/**
 * Class SubotageConverter.
 */
class SubotageSubtitleConverter extends AbstractSubtitleConverter
{
    use SubotageTrait;

    /**
     * {@inheritdoc}
     */
    protected $supportsFps = true;

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

        $outputFileName = sprintf(
            '%s.%s',
            $storage->getFile(
                $task::STORAGE_INPUT,
                PATHINFO_FILENAME
            ),
            $options->getFormat()->getExtensions()[0]
        );
        $outputFile = $storage->tmpFile($outputFileName);

        $cmd = [
            'subotage.sh',
            '--input',
            $storage->getFile($task::STORAGE_INPUT),
            '--output',
            $outputFile,
            '--output-format',
            $outputSubtitleFormat,
        ];

        if (null !== $options->getFps()) {
            if (SubtitleFormat::MICRODVD === $options->getFormat()->getValue()) {
                $cmd[] = '--fps-output';
                $cmd[] = $options->getFps()->getValue();
            } else {
                $cmd[] = '--fps-input';
                $cmd[] = $options->getFps()->getValue();
            }
        }

        $process = new Process($cmd);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ConversionFailedException();
        }

        $storage->addFile($task::STORAGE_OUTPUT, $outputFile);
    }
}
