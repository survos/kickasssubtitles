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
use KickAssSubtitles\Encoding\Detector\MbEncodingDetector;
use KickAssSubtitles\Encoding\Encoding;
use Tests\Encoding\AbstractTest;
use Throwable;

/**
 * Class MbDetectorTest.
 */
class MbDetectorTest extends AbstractTest
{
    /**
     * @var MbEncodingDetector
     */
    protected $detector;

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->detector = new MbEncodingDetector();
    }

    /**
     * @throws Throwable
     */
    public function testDetect(): void
    {
        $result = $this->detector->detect($this->loadFilename('en.utf-8'));
        $this->assertEquals(Encoding::ASCII, $result->getValue());
    }

    /**
     * @throws Throwable
     */
    public function testDetectFailure(): void
    {
        $this->expectException(Exception::class);
        $this->detector->detect($this->loadFilename('pl.iso-8859-2'));
    }
}
