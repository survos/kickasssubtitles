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

use KickAssSubtitles\Movie\MovieYear;
use PHPUnit\Framework\TestCase;

/**
 * Class MovieYearTest.
 */
class MovieYearTest extends TestCase
{
    public function testCreate(): void
    {
        $y = new MovieYear('2000-2006');
        $this->assertSame(2000, $y->getFrom());
        $this->assertSame(2006, $y->getTo());

        $y = new MovieYear('2006-2000');
        $this->assertSame(2000, $y->getFrom());
        $this->assertSame(2006, $y->getTo());

        $y = new MovieYear('2006---2000');
        $this->assertSame(2000, $y->getFrom());
        $this->assertSame(2006, $y->getTo());

        $y = new MovieYear('2000-');
        $this->assertSame(2000, $y->getFrom());
        $this->assertSame(null, $y->getTo());

        $y = new MovieYear('2000-09-21');
        $this->assertSame(2000, $y->getFrom());
        $this->assertSame(null, $y->getTo());

        $y = new MovieYear(2000);
        $this->assertSame(2000, $y->getFrom());
        $this->assertSame(null, $y->getTo());

        $y = new MovieYear('2000');
        $this->assertSame(2000, $y->getFrom());
        $this->assertSame(null, $y->getTo());
    }
}
