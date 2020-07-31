<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace KickAssSubtitles\Encoding;

use MyCLabs\Enum\Enum;
use function Safe\substr;
use Throwable;
use UnexpectedValueException;

/**
 * @method static Encoding WCHAR()
 * @method static Encoding BYTE2BE()
 * @method static Encoding BYTE2LE()
 * @method static Encoding BYTE4BE()
 * @method static Encoding BYTE4LE()
 * @method static Encoding BASE64()
 * @method static Encoding UUENCODE()
 * @method static Encoding HTML_ENTITIES()
 * @method static Encoding QUOTED_PRINTABLE()
 * @method static Encoding ENCODING_7BIT()
 * @method static Encoding ENCODING_8BIT()
 * @method static Encoding UCS_4()
 * @method static Encoding UCS_4BE()
 * @method static Encoding UCS_4LE()
 * @method static Encoding UCS_2()
 * @method static Encoding UCS_2BE()
 * @method static Encoding UCS_2LE()
 * @method static Encoding UTF_32()
 * @method static Encoding UTF_32BE()
 * @method static Encoding UTF_32LE()
 * @method static Encoding UTF_16()
 * @method static Encoding UTF_16BE()
 * @method static Encoding UTF_16LE()
 * @method static Encoding UTF_8()
 * @method static Encoding UTF_7()
 * @method static Encoding UTF7_IMAP()
 * @method static Encoding ASCII()
 * @method static Encoding EUC_JP()
 * @method static Encoding SJIS()
 * @method static Encoding EUCJP_WIN()
 * @method static Encoding EUC_JP_2004()
 * @method static Encoding SJIS_WIN()
 * @method static Encoding SJIS_MOBILE_DOCOMO()
 * @method static Encoding SJIS_MOBILE_KDDI()
 * @method static Encoding SJIS_MOBILE_SOFTBANK()
 * @method static Encoding SJIS_MAC()
 * @method static Encoding SJIS_2004()
 * @method static Encoding UTF_8_MOBILE_DOCOMO()
 * @method static Encoding UTF_8_MOBILE_KDDI_A()
 * @method static Encoding UTF_8_MOBILE_KDDI_B()
 * @method static Encoding UTF_8_MOBILE_SOFTBANK()
 * @method static Encoding CP932()
 * @method static Encoding CP51932()
 * @method static Encoding JIS()
 * @method static Encoding ISO_2022_JP()
 * @method static Encoding ISO_2022_JP_MS()
 * @method static Encoding GB18030()
 * @method static Encoding WINDOWS_1252()
 * @method static Encoding WINDOWS_1254()
 * @method static Encoding ISO_8859_1()
 * @method static Encoding ISO_8859_2()
 * @method static Encoding ISO_8859_3()
 * @method static Encoding ISO_8859_4()
 * @method static Encoding ISO_8859_5()
 * @method static Encoding ISO_8859_6()
 * @method static Encoding ISO_8859_7()
 * @method static Encoding ISO_8859_8()
 * @method static Encoding ISO_8859_9()
 * @method static Encoding ISO_8859_10()
 * @method static Encoding ISO_8859_13()
 * @method static Encoding ISO_8859_14()
 * @method static Encoding ISO_8859_15()
 * @method static Encoding ISO_8859_16()
 * @method static Encoding EUC_CN()
 * @method static Encoding CP936()
 * @method static Encoding HZ()
 * @method static Encoding EUC_TW()
 * @method static Encoding BIG_5()
 * @method static Encoding CP950()
 * @method static Encoding EUC_KR()
 * @method static Encoding UHC()
 * @method static Encoding ISO_2022_KR()
 * @method static Encoding WINDOWS_1251()
 * @method static Encoding CP866()
 * @method static Encoding KOI8_R()
 * @method static Encoding KOI8_U()
 * @method static Encoding ARMSCII_8()
 * @method static Encoding CP850()
 * @method static Encoding JIS_MS()
 * @method static Encoding ISO_2022_JP_2004()
 * @method static Encoding ISO_2022_JP_MOBILE_KDDI()
 * @method static Encoding CP50220()
 * @method static Encoding CP50220RAW()
 * @method static Encoding CP50221()
 * @method static Encoding CP50222()
 * @method static Encoding WINDOWS_1250()
 * @method static Encoding WINDOWS_1256()
 */
class Encoding extends Enum
{
    const WCHAR = 'wchar';

    const BYTE2BE = 'byte2be';

    const BYTE2LE = 'byte2le';

    const BYTE4BE = 'byte4be';

    const BYTE4LE = 'byte4le';

    const BASE64 = 'base64';

    const UUENCODE = 'uuencode';

    const HTML_ENTITIES = 'html-entities';

    const QUOTED_PRINTABLE = 'quoted-printable';

    const ENCODING_7BIT = '7bit';

    const ENCODING_8BIT = '8bit';

    const UCS_4 = 'ucs-4';

    const UCS_4BE = 'ucs-4be';

    const UCS_4LE = 'ucs-4le';

    const UCS_2 = 'ucs-2';

    const UCS_2BE = 'ucs-2be';

    const UCS_2LE = 'ucs-2le';

    const UTF_32 = 'utf-32';

    const UTF_32BE = 'utf-32be';

    const UTF_32LE = 'utf-32le';

    const UTF_16 = 'utf-16';

    const UTF_16BE = 'utf-16be';

    const UTF_16LE = 'utf-16le';

    const UTF_8 = 'utf-8';

    const UTF_7 = 'utf-7';

    const UTF7_IMAP = 'utf7-imap';

    const ASCII = 'ascii';

    const EUC_JP = 'euc-jp';

    const SJIS = 'sjis';

    const EUCJP_WIN = 'eucjp-win';

    const EUC_JP_2004 = 'euc-jp-2004';

    const SJIS_WIN = 'sjis-win';

    const SJIS_MOBILE_DOCOMO = 'sjis-mobile#docomo';

    const SJIS_MOBILE_KDDI = 'sjis-mobile#kddi';

    const SJIS_MOBILE_SOFTBANK = 'sjis-mobile#softbank';

    const SJIS_MAC = 'sjis-mac';

    const SJIS_2004 = 'sjis-2004';

    const UTF_8_MOBILE_DOCOMO = 'utf-8-mobile#docomo';

    const UTF_8_MOBILE_KDDI_A = 'utf-8-mobile#kddi-a';

    const UTF_8_MOBILE_KDDI_B = 'utf-8-mobile#kddi-b';

    const UTF_8_MOBILE_SOFTBANK = 'utf-8-mobile#softbank';

    const CP932 = 'cp932';

    const CP51932 = 'cp51932';

    const JIS = 'jis';

    const ISO_2022_JP = 'iso-2022-jp';

    const ISO_2022_JP_MS = 'iso-2022-jp-ms';

    const GB18030 = 'gb18030';

    const WINDOWS_1252 = 'windows-1252';

    const WINDOWS_1254 = 'windows-1254';

    const ISO_8859_1 = 'iso-8859-1';

    const ISO_8859_2 = 'iso-8859-2';

    const ISO_8859_3 = 'iso-8859-3';

    const ISO_8859_4 = 'iso-8859-4';

    const ISO_8859_5 = 'iso-8859-5';

    const ISO_8859_6 = 'iso-8859-6';

    const ISO_8859_7 = 'iso-8859-7';

    const ISO_8859_8 = 'iso-8859-8';

    const ISO_8859_9 = 'iso-8859-9';

    const ISO_8859_10 = 'iso-8859-10';

    const ISO_8859_13 = 'iso-8859-13';

    const ISO_8859_14 = 'iso-8859-14';

    const ISO_8859_15 = 'iso-8859-15';

    const ISO_8859_16 = 'iso-8859-16';

    const EUC_CN = 'euc-cn';

    const CP936 = 'cp936';

    const HZ = 'hz';

    const EUC_TW = 'euc-tw';

    const BIG_5 = 'big-5';

    const CP950 = 'cp950';

    const EUC_KR = 'euc-kr';

    const UHC = 'uhc';

    const ISO_2022_KR = 'iso-2022-kr';

    const WINDOWS_1251 = 'windows-1251';

    const CP866 = 'cp866';

    const KOI8_R = 'koi8-r';

    const KOI8_U = 'koi8-u';

    const ARMSCII_8 = 'armscii-8';

    const CP850 = 'cp850';

    const JIS_MS = 'jis-ms';

    const ISO_2022_JP_2004 = 'iso-2022-jp-2004';

    const ISO_2022_JP_MOBILE_KDDI = 'iso-2022-jp-mobile#kddi';

    const CP50220 = 'cp50220';

    const CP50220RAW = 'cp50220raw';

    const CP50221 = 'cp50221';

    const CP50222 = 'cp50222';

    const WINDOWS_1250 = 'windows-1250';

    const WINDOWS_1256 = 'windows-1256';

    /**
     * @var array
     */
    protected static $names = [
        self::WCHAR => 'wchar',
        self::BYTE2BE => 'byte2be',
        self::BYTE2LE => 'byte2le',
        self::BYTE4BE => 'byte4be',
        self::BYTE4LE => 'byte4le',
        self::BASE64 => 'BASE64',
        self::UUENCODE => 'UUENCODE',
        self::HTML_ENTITIES => 'HTML-ENTITIES',
        self::QUOTED_PRINTABLE => 'Quoted-Printable',
        self::ENCODING_7BIT => '7bit',
        self::ENCODING_8BIT => '8bit',
        self::UCS_4 => 'UCS-4',
        self::UCS_4BE => 'UCS-4BE',
        self::UCS_4LE => 'UCS-4LE',
        self::UCS_2 => 'UCS-2',
        self::UCS_2BE => 'UCS-2BE',
        self::UCS_2LE => 'UCS-2LE',
        self::UTF_32 => 'UTF-32',
        self::UTF_32BE => 'UTF-32BE',
        self::UTF_32LE => 'UTF-32LE',
        self::UTF_16 => 'UTF-16',
        self::UTF_16BE => 'UTF-16BE',
        self::UTF_16LE => 'UTF-16LE',
        self::UTF_8 => 'UTF-8',
        self::UTF_7 => 'UTF-7',
        self::UTF7_IMAP => 'UTF7-IMAP',
        self::ASCII => 'ASCII',
        self::EUC_JP => 'EUC-JP',
        self::SJIS => 'SJIS',
        self::EUCJP_WIN => 'eucJP-win',
        self::EUC_JP_2004 => 'EUC-JP-2004',
        self::SJIS_WIN => 'SJIS-win',
        self::SJIS_MOBILE_DOCOMO => 'SJIS-Mobile#DOCOMO',
        self::SJIS_MOBILE_KDDI => 'SJIS-Mobile#KDDI',
        self::SJIS_MOBILE_SOFTBANK => 'SJIS-Mobile#SOFTBANK',
        self::SJIS_MAC => 'SJIS-mac',
        self::SJIS_2004 => 'SJIS-2004',
        self::UTF_8_MOBILE_DOCOMO => 'UTF-8-Mobile#DOCOMO',
        self::UTF_8_MOBILE_KDDI_A => 'UTF-8-Mobile#KDDI-A',
        self::UTF_8_MOBILE_KDDI_B => 'UTF-8-Mobile#KDDI-B',
        self::UTF_8_MOBILE_SOFTBANK => 'UTF-8-Mobile#SOFTBANK',
        self::CP932 => 'CP932',
        self::CP51932 => 'CP51932',
        self::JIS => 'JIS',
        self::ISO_2022_JP => 'ISO-2022-JP',
        self::ISO_2022_JP_MS => 'ISO-2022-JP-MS',
        self::GB18030 => 'GB18030',
        self::WINDOWS_1252 => 'Windows-1252',
        self::WINDOWS_1254 => 'Windows-1254',
        self::ISO_8859_1 => 'ISO-8859-1',
        self::ISO_8859_2 => 'ISO-8859-2',
        self::ISO_8859_3 => 'ISO-8859-3',
        self::ISO_8859_4 => 'ISO-8859-4',
        self::ISO_8859_5 => 'ISO-8859-5',
        self::ISO_8859_6 => 'ISO-8859-6',
        self::ISO_8859_7 => 'ISO-8859-7',
        self::ISO_8859_8 => 'ISO-8859-8',
        self::ISO_8859_9 => 'ISO-8859-9',
        self::ISO_8859_10 => 'ISO-8859-10',
        self::ISO_8859_13 => 'ISO-8859-13',
        self::ISO_8859_14 => 'ISO-8859-14',
        self::ISO_8859_15 => 'ISO-8859-15',
        self::ISO_8859_16 => 'ISO-8859-16',
        self::EUC_CN => 'EUC-CN',
        self::CP936 => 'CP936',
        self::HZ => 'HZ',
        self::EUC_TW => 'EUC-TW',
        self::BIG_5 => 'BIG-5',
        self::CP950 => 'CP950',
        self::EUC_KR => 'EUC-KR',
        self::UHC => 'UHC',
        self::ISO_2022_KR => 'ISO-2022-KR',
        self::WINDOWS_1251 => 'Windows-1251',
        self::CP866 => 'CP866',
        self::KOI8_R => 'KOI8-R',
        self::KOI8_U => 'KOI8-U',
        self::ARMSCII_8 => 'ArmSCII-8',
        self::CP850 => 'CP850',
        self::JIS_MS => 'JIS-ms',
        self::ISO_2022_JP_2004 => 'ISO-2022-JP-2004',
        self::ISO_2022_JP_MOBILE_KDDI => 'ISO-2022-JP-MOBILE#KDDI',
        self::CP50220 => 'CP50220',
        self::CP50220RAW => 'CP50220raw',
        self::CP50221 => 'CP50221',
        self::CP50222 => 'CP50222',
        self::WINDOWS_1250 => 'Windows-1250',
        self::WINDOWS_1256 => 'Windows-1256',
    ];

    /**
     * @var array
     */
    protected static $aliases = [
        self::WCHAR => [
        ],
        self::BYTE2BE => [
        ],
        self::BYTE2LE => [
        ],
        self::BYTE4BE => [
        ],
        self::BYTE4LE => [
        ],
        self::BASE64 => [
        ],
        self::UUENCODE => [
        ],
        self::HTML_ENTITIES => [
            'HTML',
            'html',
        ],
        self::QUOTED_PRINTABLE => [
            'qprint',
        ],
        self::ENCODING_7BIT => [
        ],
        self::ENCODING_8BIT => [
            'binary',
        ],
        self::UCS_4 => [
            'ISO-10646-UCS-4',
            'UCS4',
        ],
        self::UCS_4BE => [
        ],
        self::UCS_4LE => [
        ],
        self::UCS_2 => [
            'ISO-10646-UCS-2',
            'UCS2',
            'UNICODE',
        ],
        self::UCS_2BE => [
        ],
        self::UCS_2LE => [
        ],
        self::UTF_32 => [
            'utf32',
        ],
        self::UTF_32BE => [
        ],
        self::UTF_32LE => [
        ],
        self::UTF_16 => [
            'utf16',
        ],
        self::UTF_16BE => [
        ],
        self::UTF_16LE => [
        ],
        self::UTF_8 => [
            'utf8',
        ],
        self::UTF_7 => [
            'utf7',
        ],
        self::UTF7_IMAP => [
        ],
        self::ASCII => [
            'ANSI_X3.4-1968',
            'iso-ir-6',
            'ANSI_X3.4-1986',
            'ISO_646.irv:1991',
            'US-ASCII',
            'ISO646-US',
            'us',
            'IBM367',
            'IBM-367',
            'cp367',
            'csASCII',
        ],
        self::EUC_JP => [
            'EUC',
            'EUC_JP',
            'eucJP',
            'x-euc-jp',
        ],
        self::SJIS => [
            'x-sjis',
            'SHIFT-JIS',
        ],
        self::EUCJP_WIN => [
            'eucJP-open',
            'eucJP-ms',
        ],
        self::EUC_JP_2004 => [
            'EUC_JP-2004',
        ],
        self::SJIS_WIN => [
            'SJIS-open',
            'SJIS-ms',
        ],
        self::SJIS_MOBILE_DOCOMO => [
            'SJIS-DOCOMO',
            'shift_jis-imode',
            'x-sjis-emoji-docomo',
        ],
        self::SJIS_MOBILE_KDDI => [
            'SJIS-KDDI',
            'shift_jis-kddi',
            'x-sjis-emoji-kddi',
        ],
        self::SJIS_MOBILE_SOFTBANK => [
            'SJIS-SOFTBANK',
            'shift_jis-softbank',
            'x-sjis-emoji-softbank',
        ],
        self::SJIS_MAC => [
            'MacJapanese',
            'x-Mac-Japanese',
        ],
        self::SJIS_2004 => [
            'SJIS2004',
            'Shift_JIS-2004',
        ],
        self::UTF_8_MOBILE_DOCOMO => [
            'UTF-8-DOCOMO',
            'UTF8-DOCOMO',
        ],
        self::UTF_8_MOBILE_KDDI_A => [
        ],
        self::UTF_8_MOBILE_KDDI_B => [
            'UTF-8-Mobile#KDDI',
            'UTF-8-KDDI',
            'UTF8-KDDI',
        ],
        self::UTF_8_MOBILE_SOFTBANK => [
            'UTF-8-SOFTBANK',
            'UTF8-SOFTBANK',
        ],
        self::CP932 => [
            'MS932',
            'Windows-31J',
            'MS_Kanji',
        ],
        self::CP51932 => [
            'cp51932',
        ],
        self::JIS => [
        ],
        self::ISO_2022_JP => [
        ],
        self::ISO_2022_JP_MS => [
            'ISO2022JPMS',
        ],
        self::GB18030 => [
            'gb-18030',
            'gb-18030-2000',
        ],
        self::WINDOWS_1252 => [
            'cp1252',
        ],
        self::WINDOWS_1254 => [
            'CP1254',
            'CP-1254',
            'WINDOWS-1254',
        ],
        self::ISO_8859_1 => [
            'ISO_8859-1',
            'latin1',
        ],
        self::ISO_8859_2 => [
            'ISO_8859-2',
            'latin2',
        ],
        self::ISO_8859_3 => [
            'ISO_8859-3',
            'latin3',
        ],
        self::ISO_8859_4 => [
            'ISO_8859-4',
            'latin4',
        ],
        self::ISO_8859_5 => [
            'ISO_8859-5',
            'cyrillic',
        ],
        self::ISO_8859_6 => [
            'ISO_8859-6',
            'arabic',
        ],
        self::ISO_8859_7 => [
            'ISO_8859-7',
            'greek',
        ],
        self::ISO_8859_8 => [
            'ISO_8859-8',
            'hebrew',
        ],
        self::ISO_8859_9 => [
            'ISO_8859-9',
            'latin5',
        ],
        self::ISO_8859_10 => [
            'ISO_8859-10',
            'latin6',
        ],
        self::ISO_8859_13 => [
            'ISO_8859-13',
        ],
        self::ISO_8859_14 => [
            'ISO_8859-14',
            'latin8',
        ],
        self::ISO_8859_15 => [
            'ISO_8859-15',
        ],
        self::ISO_8859_16 => [
            'ISO_8859-16',
        ],
        self::EUC_CN => [
            'CN-GB',
            'EUC_CN',
            'eucCN',
            'x-euc-cn',
            'gb2312',
        ],
        self::CP936 => [
            'CP-936',
            'GBK',
        ],
        self::HZ => [
        ],
        self::EUC_TW => [
            'EUC_TW',
            'eucTW',
            'x-euc-tw',
        ],
        self::BIG_5 => [
            'CN-BIG5',
            'BIG-FIVE',
            'BIGFIVE',
        ],
        self::CP950 => [
        ],
        self::EUC_KR => [
            'EUC_KR',
            'eucKR',
            'x-euc-kr',
        ],
        self::UHC => [
            'CP949',
        ],
        self::ISO_2022_KR => [
        ],
        self::WINDOWS_1251 => [
            'CP1251',
            'CP-1251',
            'WINDOWS-1251',
        ],
        self::CP866 => [
            'CP866',
            'CP-866',
            'IBM866',
            'IBM-866',
        ],
        self::KOI8_R => [
            'KOI8-R',
            'KOI8R',
        ],
        self::KOI8_U => [
            'KOI8-U',
            'KOI8U',
        ],
        self::ARMSCII_8 => [
            'ArmSCII-8',
            'ArmSCII8',
            'ARMSCII-8',
            'ARMSCII8',
        ],
        self::CP850 => [
            'CP850',
            'CP-850',
            'IBM850',
            'IBM-850',
        ],
        self::JIS_MS => [
        ],
        self::ISO_2022_JP_2004 => [
        ],
        self::ISO_2022_JP_MOBILE_KDDI => [
            'ISO-2022-JP-KDDI',
        ],
        self::CP50220 => [
        ],
        self::CP50220RAW => [
        ],
        self::CP50221 => [
        ],
        self::CP50222 => [
        ],
        self::WINDOWS_1250 => [
            'CP1250',
            'CP-1250',
            'WINDOWS-1250',
        ],
        self::WINDOWS_1256 => [
            'CP1256',
            'CP-1256',
            'WINDOWS-1256',
            'WinArabic',
        ],
    ];

    /**
     * {@inheritdoc}
     */
    public function __construct($value)
    {
        try {
            parent::__construct(strtolower($value));
        } catch (UnexpectedValueException $e) {
            foreach (self::values() as $encoding) {
                if ($encoding->hasAlias($value)) {
                    parent::__construct($encoding->getValue());

                    return;
                }
            }

            throw $e;
        }
    }

    public function getName(): string
    {
        return self::$names[$this->getValue()];
    }

    public function getAliases(): array
    {
        return self::$aliases[$this->getValue()];
    }

    public function hasAlias(string $alias): bool
    {
        $normalize = function (string $value): string {
            $value = strtolower($value);
            $value = str_replace('-', '', $value);
            $value = str_replace('_', '', $value);

            return $value;
        };

        $aliases = array_map($normalize, $this->getAliases());

        return \in_array($normalize($alias), $aliases, true);
    }

    /**
     * @throws Throwable
     */
    public function isWindows(): bool
    {
        return 'WINDOWS' === substr($this->getKey(), 0, 7);
    }

    /**
     * @throws Throwable
     */
    public function asArray(): array
    {
        return [
            'key' => $this->getKey(),
            'value' => $this->getValue(),
            'name' => $this->getName(),
            'aliases' => $this->getAliases(),
            'is_windows' => $this->isWindows(),
        ];
    }
}
