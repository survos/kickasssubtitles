<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace Tests\Support;

use Exception;
use KickAssSubtitles\Support\EnumMapper;
use PHPUnit\Framework\TestCase;

/**
 * Class EnumMapperTest.
 */
class EnumMapperTest extends TestCase
{
    public function testMapper(): void
    {
        $mapper = EnumMapper::create([
            [Format::SUBRIP => 'srt'],
            [Format::WEBVTT => 'vtt'],
        ], Format::class);

        $this->assertEquals('srt', $mapper->to(Format::SUBRIP()));
        $this->assertEquals(true, $mapper->from('vtt')->equals(Format::WEBVTT()));
    }

    public function testSameValues(): void
    {
        $mapper = EnumMapper::create([
            [Format::TMPLAYER => 'txt'],
            [Format::TMPLAYER => 'tmp'],
        ], Format::class);

        $this->assertEquals(true, $mapper->from('txt')->equals(Format::TMPLAYER()));
        $this->assertEquals(true, $mapper->from('tmp')->equals(Format::TMPLAYER()));
    }

    public function testMappingNotFound(): void
    {
        $this->expectException(Exception::class);
        $mapper = EnumMapper::create([
            [Format::SUBRIP => 'srt'],
            [Format::WEBVTT => 'vtt'],
        ], Format::class);
        $mapper->from('sbv');
    }

    public function testInvalidEnumKey(): void
    {
        $this->expectException(Exception::class);
        $mapper = EnumMapper::create([
            [Format::SUBRIP => 'srt'],
            [Format::WEBVTT.'invalid' => 'vtt'],
        ], Format::class);
    }
}
