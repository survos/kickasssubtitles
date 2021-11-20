<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace Tests\App\Feature\Subtitle\Converter;

use Illuminate\Foundation\Testing\RefreshDatabase;
use KickAssSubtitles\Processor\TaskErrorInterface;
use KickAssSubtitles\Subtitle\Converter\PhpSubtitleConverter;
use KickAssSubtitles\Subtitle\SubtitleFormat;
use function Safe\file_get_contents;
use Tests\App\TestCase;
use Throwable;

/**
 * Class PhpSubtitleConverterTest.
 */
class PhpSubtitleConverterTest extends TestCase
{
    use CreatesTaskTrait;
    use RefreshDatabase;

    /**
     * @var array
     */
    protected $connectionsToTransact = [];

    /**
     * @var PhpSubtitleConverter
     */
    protected $converter;

    public function setUp(): void
    {
        parent::setUp();
        /* @var PhpSubtitleConverter converter */
        $this->converter = $this->app->make(PhpSubtitleConverter::class);
    }

    /**
     * @throws Throwable
     */
    public function testConversion()
    {
        $this->task = $this->createTask(
            static::$srt,
            SubtitleFormat::SUBRIP(),
            SubtitleFormat::SUBVIEWER2()
        );

        $this->converter->processOne($this->task);

        $output = $this->task
            ->getStorage()
            ->getFile($this->task::STORAGE_OUTPUT)
        ;

        $this->assertEquals(
            static::$sub,
            file_get_contents($output)
        );
    }

    /**
     * @throws Throwable
     */
    public function testUnsupportedFormat()
    {
        $this->task = $this->createTask(
            static::$srt,
            SubtitleFormat::SUBRIP(),
            SubtitleFormat::PINNACLEIMPRESSION()
        );

        $this->converter->processOne($this->task);

        $this->assertInstanceOf(
            TaskErrorInterface::class,
            $this->task->getError()
        );
    }
}
