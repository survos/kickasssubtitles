<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace Tests\Encoding;

use Exception;
use KickAssSubtitles\Encoding\Encoding;

/**
 * Class EncodingTest.
 */
class EncodingTest extends AbstractTest
{
    /**
     * @dataProvider validEncodings
     */
    public function testValidEncodings(string $encoding): void
    {
        $encoding = new Encoding($encoding);
        $this->assertInstanceOf(Encoding::class, $encoding);
    }

    public function validEncodings(): array
    {
        return [
            ['ISO-8859-2'],
            ['iso8859_2'],
            ['UTF-8'],
            ['utf-8'],
            ['utf8'],
        ];
    }

    /**
     * @dataProvider invalidEncodings
     */
    public function testInvalidEncodings(string $encoding): void
    {
        $this->expectException(Exception::class);
        new Encoding($encoding);
    }

    public function invalidEncodings(): array
    {
        return [
            ['xxx'],
        ];
    }
}
