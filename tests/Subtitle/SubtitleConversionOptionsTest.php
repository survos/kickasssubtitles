<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace Tests\Subtitle;

use KickAssSubtitles\Encoding\Encoding;
use KickAssSubtitles\Subtitle\SubtitleConversionOptions;
use KickAssSubtitles\Subtitle\SubtitleFormat;
use KickAssSubtitles\Subtitle\SubtitleOptionsInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class SubtitleConversionDataTest.
 */
class SubtitleConversionOptionsTest extends TestCase
{
    public function testDefaults(): void
    {
        /** @var SubtitleConversionOptions $options */
        $options = new SubtitleConversionOptions();
        $this->assertEquals(SubtitleOptionsInterface::DEFAULT_FILENAME, $options->getFilename());
        $this->assertEquals(0, $options->getFilesize());
        $this->assertEquals(Encoding::UTF_8(), $options->getEncoding());
        $this->assertEquals(SubtitleFormat::SUBRIP(), $options->getFormat());
        $this->assertEquals($options::DEFAULT_FILE, $options->getFile());
        $this->assertEquals(null, $options->getInputEncoding());
        $this->assertEquals(null, $options->getLanguage());
        $this->assertEquals(null, $options->getFps());
    }
}
