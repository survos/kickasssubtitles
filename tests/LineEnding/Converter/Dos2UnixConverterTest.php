<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace Tests\LineEnding\Converter;

use Exception;
use KickAssSubtitles\LineEnding\Converter\Dos2UnixLineEndingConverter;
use KickAssSubtitles\LineEnding\LineEnding;
use function Safe\file_get_contents;
use function Safe\unlink;
use Tests\LineEnding\AbstractTest;
use Throwable;

/**
 * Class Dos2UnixLineEndingConverterTest.
 */
class Dos2UnixConverterTest extends AbstractTest
{
    /**
     * @var Dos2UnixLineEndingConverter
     */
    protected $converter;

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->converter = new Dos2UnixLineEndingConverter();
    }

    /**
     * @dataProvider getConversions
     *
     * @throws Throwable
     */
    public function testConvert(LineEnding $from, LineEnding $to): void
    {
        $fromFile = $this->loadFilename($from->getValue());
        $toFile = $this->loadFilename($to->getValue());

        $this->converter->convertFile($fromFile, $from, $to);
        $this->assertEquals(file_get_contents($toFile), file_get_contents($fromFile));

        unlink($fromFile);
        unlink($toFile);
    }

    public function getConversions(): array
    {
        return [
            [LineEnding::DOS(), LineEnding::UNIX()],
            [LineEnding::UNIX(), LineEnding::DOS()],
            [LineEnding::DOS(), LineEnding::MAC()],
            [LineEnding::MAC(), LineEnding::DOS()],
            [LineEnding::MAC(), LineEnding::UNIX()],
            [LineEnding::UNIX(), LineEnding::MAC()],
            [LineEnding::UNIX(), LineEnding::UNIX()],
        ];
    }
}
