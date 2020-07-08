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

use KickAssSubtitles\Movie\VideoFps;
use KickAssSubtitles\Processor\TaskInterface;
use KickAssSubtitles\Storage\HasStorageInterface;
use KickAssSubtitles\Subtitle\Converter\Exception\ConversionFailedException;
use KickAssSubtitles\Subtitle\SubtitleConversionOptions;
use KickAssSubtitles\Subtitle\SubtitleFormat;
use KickAssSubtitles\Support\EnumMapper;
use function Safe\scandir;
use Symfony\Component\Process\Process;

/**
 * Class SubtitleEditConverter.
 */
class SubtitleEditSubtitleConverter extends AbstractSubtitleConverter
{
    const ADOBEENCOREDVD = 'AdobeEncore';

    const AQTITLE = 'AQTitle';

    const FABSUBTITLER = 'FABSubtitler';

    const JACOSUB = 'JACOsub';

    const MICRODVD = 'MicroDVD';

    const MPLAYER2 = 'MPlayer2';

    const PHOENIXSUBTITLE = 'PhoenixSubtitle';

    const PINNACLEIMPRESSION = 'PinnacleImpression';

    const QUICKTIMETEXT = 'QuickTimetext';

    const REALTEXT = 'RealTime';

    const SAMI = 'SAMI';

    const SPRUCESF = 'SpruceSubtitleFile';

    const STRUCTUREDSF = 'Structuredtitles';

    const SUBRIP = 'SubRip';

    const SUBVIEWER = 'SubViewer1.0';

    const SUBVIEWER2 = 'SubViewer2.0';

    const TIMEDTEXT = 'TimedText1.0';

    const TMPLAYER = 'TMPlayer';

    const SUBSTATIONALPHA = 'SubStationAlpha';

    const UNIVERSALSF = 'UniversalSubtitleFormat';

    const WEBVTT = 'WebVTT';

    const YOUTUBESBV = 'YouTubesbv';

    /**
     * {@inheritdoc}
     */
    protected $supportsFps = true;

    /**
     * @var string
     */
    protected $subtitleEditPath;

    /**
     * @param string $path
     */
    public function setSubtitleEditPath(string $path): void
    {
        $this->subtitleEditPath = $path;
    }

    /**
     * @return string
     */
    public function getSubtitleEditPath(): string
    {
        if (null === $this->subtitleEditPath) {
            $defaultPath = \implode(DIRECTORY_SEPARATOR, [
                'subtitleedit',
                'SubtitleEdit.exe',
            ]);

            return DIRECTORY_SEPARATOR.$defaultPath;
        }

        return $this->subtitleEditPath;
    }

    /**
     * {@inheritdoc}
     */
    public function createFormatMapper(): EnumMapper
    {
        return EnumMapper::create([
            [SubtitleFormat::ADOBEENCOREDVD => static::ADOBEENCOREDVD],
            [SubtitleFormat::AQTITLE => static::AQTITLE],
            [SubtitleFormat::FABSUBTITLER => static::FABSUBTITLER],
            [SubtitleFormat::JACOSUB => static::JACOSUB],
            [SubtitleFormat::MICRODVD => static::MICRODVD],
            [SubtitleFormat::MPLAYER2 => static::MPLAYER2],
            [SubtitleFormat::PHOENIXSUBTITLE => static::PHOENIXSUBTITLE],
            [SubtitleFormat::PINNACLEIMPRESSION => static::PINNACLEIMPRESSION],
            [SubtitleFormat::QUICKTIMETEXT => static::QUICKTIMETEXT],
            [SubtitleFormat::REALTEXT => static::REALTEXT],
            [SubtitleFormat::SAMI => static::SAMI],
            [SubtitleFormat::SPRUCESF => static::SPRUCESF],
            [SubtitleFormat::STRUCTUREDSF => static::STRUCTUREDSF],
            [SubtitleFormat::SUBRIP => static::SUBRIP],
            [SubtitleFormat::SUBVIEWER => static::SUBVIEWER],
            [SubtitleFormat::SUBVIEWER2 => static::SUBVIEWER2],
            [SubtitleFormat::TIMEDTEXT => static::TIMEDTEXT],
            [SubtitleFormat::DFXP => static::TIMEDTEXT],
            [SubtitleFormat::TMPLAYER => static::TMPLAYER],
            [SubtitleFormat::SUBSTATIONALPHA => static::SUBSTATIONALPHA],
            [SubtitleFormat::UNIVERSALSF => static::UNIVERSALSF],
            [SubtitleFormat::WEBVTT => static::WEBVTT],
            [SubtitleFormat::YOUTUBESBV => static::YOUTUBESBV],
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

        $outputFolder = \dirname($storage->tmpFile('empty'));

        $cmd = [
            'xvfb-run',
            '-e',
            '/dev/stdout',
            '-a',
            'mono',
            $this->getSubtitleEditPath(),
            '/convert',
            $storage->getFile($task::STORAGE_INPUT),
            $outputSubtitleFormat,
            '/outputfolder:'.$outputFolder,
        ];

        if ($options->getFps() instanceof VideoFps) {
            $cmd[] = '/fps:'.$options->getFps()->getValue();
        }

        $process = new Process($cmd);
        $process->run();
        $output = \strtolower($process->getOutput());

        if (str_contains($output, 'error')) {
            throw new ConversionFailedException();
        }

        $files = [];

        foreach (scandir($outputFolder) as $file) {
            if (\is_file($outputFolder.DIRECTORY_SEPARATOR.$file)) {
                $files[] = $file;
            }
        }

        $outputFile = $outputFolder.DIRECTORY_SEPARATOR.$files[0];

        $storage->addFile($task::STORAGE_OUTPUT, $outputFile);
    }
}
