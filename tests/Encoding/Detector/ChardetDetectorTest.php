<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace Tests\Encoding\Detector;

use Exception;
use KickAssSubtitles\Encoding\Detector\ChardetEncodingDetector;
use KickAssSubtitles\Encoding\Encoding;
use Tests\Encoding\AbstractTest;
use Throwable;

/**
 * Class ChardetDetectorTest.
 */
class ChardetDetectorTest extends AbstractTest
{
    /**
     * @var ChardetEncodingDetector
     */
    protected $detector;

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->detector = new ChardetEncodingDetector();
    }

    /**
     * @throws Throwable
     */
    public function testDetect(): void
    {
        $result = $this->detector->detect($this->loadFilename('pl.utf-8'));
        $this->assertEquals(Encoding::UTF_8, $result->getValue());
    }

    /**
     * @throws Throwable
     */
    public function testDetectFailure(): void
    {
        $this->expectException(Exception::class);
        $this->detector->detect($this->loadFilename('gif'));
    }
}
