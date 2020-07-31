<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace KickAssSubtitles\Movie;

use MyCLabs\Enum\Enum;

/**
 * Class VideoFormat.
 *
 * @see https://en.wikipedia.org/wiki/Video_file_format
 *
 * @method static VideoFormat WEBM()
 * @method static VideoFormat MATROSKA()
 * @method static VideoFormat FLASHVIDEO()
 * @method static VideoFormat F4V()
 * @method static VideoFormat VOB()
 * @method static VideoFormat OGG()
 * @method static VideoFormat DIRAC()
 * @method static VideoFormat GIF()
 * @method static VideoFormat GIFV()
 * @method static VideoFormat MNG()
 * @method static VideoFormat AVI()
 * @method static VideoFormat QUICKTIME()
 * @method static VideoFormat WMV()
 * @method static VideoFormat RAWVIDEOFORMAT()
 * @method static VideoFormat REALMEDIA()
 * @method static VideoFormat REALMEDIAVB()
 * @method static VideoFormat ASF()
 * @method static VideoFormat AMV()
 * @method static VideoFormat MP4()
 * @method static VideoFormat MPEG1()
 * @method static VideoFormat MPEG2()
 * @method static VideoFormat M4V()
 * @method static VideoFormat SVI()
 * @method static VideoFormat GP3()
 * @method static VideoFormat GP32()
 * @method static VideoFormat MXF()
 * @method static VideoFormat NSV()
 */
class VideoFormat extends Enum
{
    const WEBM = 'webm';

    const MATROSKA = 'matroska';

    const FLASHVIDEO = 'flashvideo';

    const F4V = 'f4v';

    const VOB = 'vob';

    const OGG = 'ogg';

    const DIRAC = 'dirac';

    const GIF = 'gif';

    const GIFV = 'gifv';

    const MNG = 'mng';

    const AVI = 'avi';

    const QUICKTIME = 'quicktime';

    const WMV = 'wmv';

    const RAWVIDEOFORMAT = 'rawwideoformat';

    const REALMEDIA = 'realmedia';

    const REALMEDIAVB = 'realmediavb';

    const ASF = 'asf';

    const AMV = 'amv';

    const MP4 = 'mp4';

    const MPEG1 = 'mpeg1';

    const MPEG2 = 'mpeg2';

    const M4V = 'm4v';

    const SVI = 'svi';

    const GP3 = 'gp3';

    const GP32 = 'gp32';

    const MXF = 'mxf';

    const NSV = 'nsv';

    /**
     * @var array
     */
    protected static $names = [
        self::WEBM => 'WebM',
        self::MATROSKA => 'Matroska',
        self::FLASHVIDEO => 'Flash Video',
        self::F4V => 'F4V',
        self::VOB => 'Vob',
        self::OGG => 'Ogg Video',
        self::DIRAC => 'Dirac',
        self::GIFV => 'Video alternative to GIF',
        self::MNG => 'Multiple-image Network Graphics',
        self::AVI => 'AVI',
        self::QUICKTIME => 'QuickTime File Format',
        self::WMV => 'Windows Media Video',
        self::RAWVIDEOFORMAT => 'Raw video format',
        self::REALMEDIA => 'RealMedia',
        self::REALMEDIAVB => 'RealMedia Variable Bitrate',
        self::ASF => 'Advanced Systems Format',
        self::AMV => 'AMV video format',
        self::MP4 => 'MPEG-4 Part 14 (MP4)',
        self::MPEG1 => 'MPEG-1',
        self::MPEG2 => 'MPEG-2 – Video',
        self::M4V => 'M4V',
        self::SVI => 'SVI',
        self::GP3 => '3GPP',
        self::GP32 => '3GPP2',
        self::MXF => 'Material Exchange Format',
        self::NSV => 'Nullsoft Streaming Video',
    ];

    /**
     * @var array
     */
    protected static $extensions = [
        self::WEBM => ['webm'],
        self::MATROSKA => ['mkv'],
        self::FLASHVIDEO => ['flv', 'f4v'],
        self::F4V => ['flv'],
        self::VOB => ['vob'],
        self::OGG => ['ogv', 'ogg'],
        self::DIRAC => ['drc'],
        self::GIF => ['gif'],
        self::GIFV => ['gifv'],
        self::MNG => ['mng'],
        self::AVI => ['avi'],
        self::QUICKTIME => ['mov', 'qt'],
        self::WMV => ['wmv'],
        self::RAWVIDEOFORMAT => ['yuv'],
        self::REALMEDIA => ['rm'],
        self::REALMEDIAVB => ['rmvb'],
        self::ASF => ['asf'],
        self::AMV => ['amv'],
        self::MP4 => ['mp4', 'm4p', 'm4v'],
        self::MPEG1 => ['mpg', 'mp2', 'mpeg', 'mpe', 'mpv'],
        self::MPEG2 => ['mpg', 'mpeg', 'm2v'],
        self::M4V => ['m4v'],
        self::SVI => ['svi'],
        self::GP3 => ['3gp'],
        self::GP32 => ['3g2'],
        self::MXF => ['mxf'],
        self::NSV => ['nsv'],
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
