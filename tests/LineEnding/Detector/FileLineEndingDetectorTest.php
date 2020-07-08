<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace Tests\LineEnding\Detector;

use Exception;
use KickAssSubtitles\LineEnding\Detector\FileLineEndingDetector;
use KickAssSubtitles\LineEnding\LineEnding;
use function Safe\file_get_contents;
use function Safe\unlink;
use Tests\LineEnding\AbstractTest;
use Throwable;

/**
 * Class FileDetectorTest.
 */
class FileLineEndingDetectorTest extends AbstractTest
{
    /**
     * @var FileLineEndingDetector
     */
    protected $detector;

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->detector = new FileLineEndingDetector();
    }

    /**
     * @param LineEnding $type
     *
     * @throws Throwable
     * @dataProvider getDetections
     */
    public function testDetect(LineEnding $type): void
    {
        $file = $this->loadFilename($type->getValue());
        $detectedType = $this->detector->detectFile($file);
        $this->assertEquals(true, $detectedType->equals($type));
        $detectedType = $this->detector->detect(file_get_contents($file));
        $this->assertEquals(true, $detectedType->equals($type));
        unlink($file);
    }

    /**
     * @return array
     */
    public function getDetections(): array
    {
        return [
            [LineEnding::DOS()],
            [LineEnding::UNIX()],
            [LineEnding::MAC()],
        ];
    }

    /**
     * @throws Throwable
     */
    public function testDetectMixedLineEndings(): void
    {
        $file = $this->loadFilename('dosunix');
        $detectedType = $this->detector->detectFile($file);
        $this->assertEquals(true, $detectedType->equals(LineEnding::DOS()));
        unlink($file);
    }
}
