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

use KickAssSubtitles\Movie\MovieSlugger;
use PHPUnit\Framework\TestCase;

/**
 * Class MovieYearTest.
 */
class MovieSluggerTest extends TestCase
{
    public function testSlugger(): void
    {
        $slugger = new MovieSlugger();
        $this->assertEquals('witcher-the', $slugger->slugify('The Witcher'));
        $this->assertEquals('star-is-born-a', $slugger->slugify('A Star Is Born'));
    }
}
