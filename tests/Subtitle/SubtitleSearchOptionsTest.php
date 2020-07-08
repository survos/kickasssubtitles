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
use KickAssSubtitles\Language\Language;
use KickAssSubtitles\Subtitle\SubtitleFormat;
use KickAssSubtitles\Subtitle\SubtitleSearchOptions;
use PHPUnit\Framework\TestCase;

/**
 * Class SubtitleSearchDataTest.
 */
class SubtitleSearchOptionsTest extends TestCase
{
    public function testDefaults(): void
    {
        /** @var SubtitleSearchOptions $options */
        $options = new SubtitleSearchOptions();
        $this->assertEquals(SubtitleSearchOptions::DEFAULT_FILENAME, $options->getFilename());
        $this->assertEquals(0, $options->getFilesize());
        $this->assertEquals(Encoding::UTF_8(), $options->getEncoding());
        $this->assertEquals(SubtitleFormat::SUBRIP(), $options->getFormat());
        $this->assertEquals(Language::EN(), $options->getLanguage());
        $this->assertEquals([], $options->getHashes());
    }
}
