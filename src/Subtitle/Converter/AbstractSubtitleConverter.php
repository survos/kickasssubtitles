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

use KickAssSubtitles\Encoding\Encoding;
use KickAssSubtitles\Encoding\EncodingConverterDetectorInterface;
use KickAssSubtitles\LineEnding\LineEndingConverterDetectorInterface;
use KickAssSubtitles\Processor\AbstractProcessor;
use KickAssSubtitles\Processor\TaskInterface;
use KickAssSubtitles\Processor\TaskType;
use KickAssSubtitles\Storage\HasStorageInterface;
use KickAssSubtitles\Subtitle\SubtitleConversionOptions;
use KickAssSubtitles\Subtitle\SubtitleFormatDetectorInterface;
use KickAssSubtitles\Support\EnumMapper;

/**
 * Class AbstractSubtitleConverter.
 */
abstract class AbstractSubtitleConverter extends AbstractProcessor
{
    /**
     * @var null|EnumMapper
     */
    protected $formatMapper;

    /**
     * @var bool
     */
    protected $supportsFps = false;

    /**
     * @var LineEndingConverterDetectorInterface
     */
    protected $lineEndingConverterDetector;

    /**
     * @var EncodingConverterDetectorInterface
     */
    protected $encodingConverterDetector;

    /**
     * @var SubtitleFormatDetectorInterface
     */
    protected $subtitleFormatDetector;

    /**
     * @param LineEndingConverterDetectorInterface $lineEndingConverterDetector
     * @param EncodingConverterDetectorInterface   $encodingConverterDetector
     * @param SubtitleFormatDetectorInterface      $subtitleFormatDetector
     */
    public function __construct(
        LineEndingConverterDetectorInterface $lineEndingConverterDetector,
        EncodingConverterDetectorInterface $encodingConverterDetector,
        SubtitleFormatDetectorInterface $subtitleFormatDetector
    ) {
        parent::__construct();
        $this->lineEndingConverterDetector = $lineEndingConverterDetector;
        $this->encodingConverterDetector = $encodingConverterDetector;
        $this->subtitleFormatDetector = $subtitleFormatDetector;
    }

    /**
     * @return EnumMapper
     */
    public function getFormatMapper(): EnumMapper
    {
        if (null === $this->formatMapper) {
            $this->formatMapper = $this->createFormatMapper();
        }

        return $this->formatMapper;
    }

    /**
     * @return EnumMapper
     */
    abstract public function createFormatMapper(): EnumMapper;

    /**
     * {@inheritdoc}
     */
    public function getSupportedTaskType(): TaskType
    {
        return TaskType::SUBTITLE_CONVERSION();
    }

    /**
     * {@inheritdoc}
     */
    protected function beforeProcessTask(TaskInterface $task): void
    {
        parent::beforeProcessTask($task);

        /** @var SubtitleConversionOptions $options */
        $options = $task->getOptions();

        // this will throw an exception if format is not supported by the converter
        $this->getFormatMapper()->to($options->getFormat());

        /** @var HasStorageInterface $taskWithStorage */
        $taskWithStorage = $task;

        $taskWithStorage->getStorage()->copyFile($task::STORAGE_INPUT);

        $inputFile = $taskWithStorage
            ->getStorage()
            ->getFile($task::STORAGE_INPUT)
        ;

        // if there is no input encoding specified - try to detect it
        $inputEncoding = $options->getInputEncoding();
        if (null === $inputEncoding) {
            $inputEncoding = $this->encodingConverterDetector->detectFile(
                $inputFile,
                $options->getLanguage()
            );
        }

        // convert to UTF-8 for conversion process
        $this->encodingConverterDetector->convertFile(
            $inputFile,
            $inputEncoding,
            Encoding::UTF_8()
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function afterProcessTask(TaskInterface $task): void
    {
        parent::afterProcessTask($task);

        /** @var SubtitleConversionOptions $options */
        $options = $task->getOptions();
        /** @var HasStorageInterface $taskWithStorage */
        $taskWithStorage = $task;
        $outputFile = $taskWithStorage
            ->getStorage()
            ->getFile($task::STORAGE_OUTPUT)
        ;

        // convert from UTF-8 to desired encoding
        $this->encodingConverterDetector->convertFile(
            $outputFile,
            Encoding::UTF_8(),
            $options->getEncoding()
        );

        $taskWithStorage->getStorage()->restoreFile($task::STORAGE_INPUT);
        $inputFile = $taskWithStorage
            ->getStorage()
            ->getFile($task::STORAGE_INPUT)
        ;

        $inputLineEnding = $this->lineEndingConverterDetector->detectFile($inputFile);
        $outputLineEnding = $this->lineEndingConverterDetector->detectFile($outputFile);

        // fix line-endings as some converters are messing those
        $this->lineEndingConverterDetector->convertFile(
            $outputFile,
            $outputLineEnding,
            $inputLineEnding
        );
    }
}
