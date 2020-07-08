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
use KickAssSubtitles\Encoding\Detector\CharedEncodingDetector;
use KickAssSubtitles\Encoding\Encoding;
use KickAssSubtitles\Language\Language;
use Tests\Encoding\AbstractTest;
use Throwable;

/**
 * Class CharedDetectorTest.
 */
class CharedDetectorTest extends AbstractTest
{
    /**
     * @var CharedEncodingDetector
     */
    protected $detector;

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->detector = new CharedEncodingDetector();
    }

    /**
     * @throws Throwable
     */
    public function testDetect(): void
    {
        $result = $this->detector->detect($this->loadFilename('pl.windows-1250'), Language::PL());
        $this->assertEquals(Encoding::WINDOWS_1250, $result->getValue());

        $result = $this->detector->detect($this->loadFilename('pl.iso-8859-2'), Language::PL());
        $this->assertEquals(Encoding::ISO_8859_2, $result->getValue());
    }

    /**
     * @throws Throwable
     */
    public function testDetectFailure(): void
    {
        $this->expectException(Exception::class);
        $this->detector->detect($this->loadFilename('pl.windows-1250'));
    }
}
