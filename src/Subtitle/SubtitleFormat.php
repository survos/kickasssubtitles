<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace KickAssSubtitles\Subtitle;

use InvalidArgumentException;
use MyCLabs\Enum\Enum;
use Throwable;

/**
 * @method static SubtitleFormat ADOBEENCOREDVD()
 * @method static SubtitleFormat AQTITLE()
 * @method static SubtitleFormat FABSUBTITLER()
 * @method static SubtitleFormat JACOSUB()
 * @method static SubtitleFormat MICRODVD()
 * @method static SubtitleFormat MPSUB()
 * @method static SubtitleFormat MPLAYER2()
 * @method static SubtitleFormat PHOENIXSUBTITLE()
 * @method static SubtitleFormat POWERDIVX()
 * @method static SubtitleFormat PINNACLEIMPRESSION()
 * @method static SubtitleFormat QUICKTIMETEXT()
 * @method static SubtitleFormat REALTEXT()
 * @method static SubtitleFormat SAMI()
 * @method static SubtitleFormat SONICDVDCREATOR()
 * @method static SubtitleFormat SPRUCESF()
 * @method static SubtitleFormat STRUCTUREDSF()
 * @method static SubtitleFormat SUBRIP()
 * @method static SubtitleFormat SUBVIEWER()
 * @method static SubtitleFormat SUBVIEWER2()
 * @method static SubtitleFormat TIMEDTEXT()
 * @method static SubtitleFormat TMPLAYER()
 * @method static SubtitleFormat SUBSTATIONALPHA()
 * @method static SubtitleFormat SUBSTATIONALPHAADVANCED()
 * @method static SubtitleFormat UNIVERSALSF()
 * @method static SubtitleFormat WEBVTT()
 * @method static SubtitleFormat YOUTUBESBV()
 */
class SubtitleFormat extends Enum
{
    const ADOBEENCOREDVD = 'adobeencoredvd';

    const AQTITLE = 'aqtitle';

    const FABSUBTITLER = 'fabsubtitler';

    const JACOSUB = 'jacosub';

    const MICRODVD = 'microdvd';

    const MPSUB = 'mpsub';

    const MPLAYER2 = 'mplayer2';

    const PHOENIXSUBTITLE = 'phoenixsubtitle';

    const POWERDIVX = 'powerdivx';

    const PINNACLEIMPRESSION = 'pinnacleimpression';

    const QUICKTIMETEXT = 'quicktimetext';

    const REALTEXT = 'realtext';

    const SAMI = 'sami';

    const SONICDVDCREATOR = 'sonicdvdcreator';

    const SPRUCESF = 'sprucesf';

    const STRUCTUREDSF = 'structuredsf';

    const SUBRIP = 'subrip';

    const SUBVIEWER = 'subviewer';

    const SUBVIEWER2 = 'subviewer2';

    const TIMEDTEXT = 'timedtext';

    const DFXP = 'dfxp';

    const TMPLAYER = 'tmplayer';

    const SUBSTATIONALPHA = 'substationalpha';

    const SUBSTATIONALPHAADVANCED = 'substationalphaadvanced';

    const UNIVERSALSF = 'universalsf';

    const WEBVTT = 'webvtt';

    const YOUTUBESBV = 'youtubesbv';

    /**
     * @var array
     */
    protected static $names = [
        self::ADOBEENCOREDVD => 'Adobe Encore DVD',
        self::AQTITLE => 'AQTitle',
        self::FABSUBTITLER => 'FAB Subtitler',
        self::JACOSUB => 'JACOsub',
        self::MICRODVD => 'MicroDVD',
        self::MPSUB => 'MP Sub',
        self::MPLAYER2 => 'MPlayer2',
        self::PHOENIXSUBTITLE => 'Phoenix Subtitle',
        self::POWERDIVX => 'PowerDivX',
        self::PINNACLEIMPRESSION => 'Pinnacle Impression',
        self::QUICKTIMETEXT => 'QuickTime',
        self::REALTEXT => 'RealText',
        self::SAMI => 'SAMI',
        self::SONICDVDCREATOR => 'SonicDVD Creator',
        self::SPRUCESF => 'Spruce Subtitle Format',
        self::STRUCTUREDSF => 'Structured Subtitle Format',
        self::SUBRIP => 'SubRip',
        self::SUBVIEWER => 'SubViewer',
        self::SUBVIEWER2 => 'SubViewer (version 2)',
        self::TIMEDTEXT => 'MPEG-4 Timed Text',
        self::DFXP => 'Distribution Format Exchange Profile',
        self::TMPLAYER => 'TMPlayer',
        self::SUBSTATIONALPHA => 'SubStation Alpha',
        self::SUBSTATIONALPHAADVANCED => 'SubStation Alpha Advanced',
        self::UNIVERSALSF => 'Universal Subtitle Format',
        self::WEBVTT => 'WebVTT',
        self::YOUTUBESBV => 'YouTube sbv',
    ];

    /**
     * @var array
     */
    protected static $extensions = [
        self::ADOBEENCOREDVD => ['txt'],
        self::AQTITLE => ['aqt'],
        self::FABSUBTITLER => ['txt'],
        self::JACOSUB => ['jss', 'js'],
        self::MICRODVD => ['sub'],
        self::MPSUB => ['sub'],
        self::MPLAYER2 => ['mpl', 'txt'],
        self::PHOENIXSUBTITLE => ['pjs'],
        self::POWERDIVX => ['psb'],
        self::PINNACLEIMPRESSION => ['txt'],
        self::QUICKTIMETEXT => ['txt', 'qt.txt'],
        self::REALTEXT => ['rt'],
        self::SAMI => ['smi'],
        self::SONICDVDCREATOR => ['sub'],
        self::SPRUCESF => ['stl'],
        self::STRUCTUREDSF => ['ssf'],
        self::SUBRIP => ['srt'],
        self::SUBVIEWER => ['sub'],
        self::SUBVIEWER2 => ['sub'],
        self::TIMEDTEXT => ['ttml', 'xml'],
        self::DFXP => ['dfxp', 'xml'],
        self::TMPLAYER => ['txt'],
        self::SUBSTATIONALPHA => ['ssa'],
        self::SUBSTATIONALPHAADVANCED => ['ass'],
        self::UNIVERSALSF => ['usf'],
        self::WEBVTT => ['vtt'],
        self::YOUTUBESBV => ['sbv'],
    ];

    /**
     * Check if passed extension (case-insensitive) is valid.
     */
    public static function isValidExtension(string $extension): bool
    {
        $extensions = [];
        foreach (static::$extensions as $k => $v) {
            foreach ($v as $value) {
                $extensions[] = $value;
            }
        }

        return \in_array(strtolower($extension), array_unique($extensions), true);
    }

    /**
     * @throws Throwable
     */
    public static function createFromExtension(string $extension): self
    {
        $map = [];
        foreach (static::$extensions as $k => $v) {
            foreach ($v as $value) {
                if (!isset($map[$value])) {
                    $map[$value] = [1, $k];
                } else {
                    $map[$value] = [$map[$value][0] + 1, $k];
                }
            }
        }
        if (!isset($map[$extension])) {
            throw new InvalidArgumentException('Invalid extension');
        }
        if ($map[$extension][0] > 1) {
            throw new InvalidArgumentException('Cannot determine format from extension alone');
        }

        return new static($map[$extension][1]);
    }

    public function getName(): string
    {
        return self::$names[$this->getValue()];
    }

    public function getExtensions(): array
    {
        return self::$extensions[$this->getValue()];
    }

    public function asArray(): array
    {
        return [
            'key' => $this->getKey(),
            'value' => $this->getValue(),
            'name' => $this->getName(),
            'extensions' => $this->getExtensions(),
        ];
    }
}
