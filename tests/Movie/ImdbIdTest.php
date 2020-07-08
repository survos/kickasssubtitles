<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace Tests\Movie;

use InvalidArgumentException;
use KickAssSubtitles\Movie\ImdbId;
use PHPUnit\Framework\TestCase;
use Throwable;

/**
 * Class IdTest.
 */
class ImdbIdTest extends TestCase
{
    /**
     * @throws Throwable
     */
    public function testCreate(): void
    {
        $this->assertSame('tt0114436', (new ImdbId(114436))->getValue());
        $this->assertSame('tt0114436', (new ImdbId('114436'))->getValue());
        $this->assertSame('tt0114436', (new ImdbId('0114436'))->getValue());
        $this->assertSame('tt0114436', (new ImdbId('tt0114436'))->getValue());
        $this->assertSame('tt0114436', (new ImdbId('tt114436'))->getValue());
        $this->assertSame('tt0114436', (new ImdbId('https://www.imdb.com/title/tt0114436/?ref_=nv_sr_1'))->getValue());
    }

    /**
     * @dataProvider provideInvalid
     */
    public function testInvalid($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        new ImdbId($value);
    }

    public function provideInvalid(): array
    {
        return [
            ['zz0114436'],
            ['tt'.null],
        ];
    }
}
