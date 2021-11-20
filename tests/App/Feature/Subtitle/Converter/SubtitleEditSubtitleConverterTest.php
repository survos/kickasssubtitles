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
use KickAssSubtitles\Storage\HasStorageInterface;
use KickAssSubtitles\Subtitle\Converter\SubtitleEditSubtitleConverter;
use KickAssSubtitles\Subtitle\SubtitleFormat;
use function Safe\file_get_contents;
use Tests\App\TestCase;
use Throwable;

/**
 * Class SubtitleEditSubtitleConverterTest.
 */
class SubtitleEditSubtitleConverterTest extends TestCase
{
    use CreatesTaskTrait;
    use RefreshDatabase;

    /**
     * @var array
     */
    protected $connectionsToTransact = [];

    /**
     * @var SubtitleEditSubtitleConverter
     */
    protected $converter;

    public function setUp(): void
    {
        parent::setUp();
        /* @var SubtitleEditSubtitleConverter converter */
        $this->converter = $this->app->make(SubtitleEditSubtitleConverter::class);
    }

    /**
     * @throws Throwable
     */
    public function testConversion(): void
    {
        $this->task = $this->createTask(
            static::$srt,
            SubtitleFormat::SUBRIP(),
            SubtitleFormat::SUBVIEWER2()
        );

        $this->converter->processOne($this->task);

        /** @var HasStorageInterface $taskWithStorage */
        $taskWithStorage = $this->task;

        $output = $taskWithStorage
            ->getStorage()
            ->getFile($this->task::STORAGE_OUTPUT)
        ;

        $this->assertEquals(
            "[INFORMATION]\n[TITLE]input\n[AUTHOR]\n[SOURCE]\n[PRG]\n[FILEPATH]\n[DELAY]0\n[CD TRACK]0\n[COMMENT]\n[END INFORMATION]\n[SUBTITLE]\n[COLF]&H000000,[STYLE]bd,[SIZE]25,[FONT]Arial\n00:01:02.90,00:01:06.18\nLOS ANGELES, ROK 2029",
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
            SubtitleFormat::MPSUB()
        );

        $this->converter->processOne($this->task);

        $this->assertInstanceOf(
            TaskErrorInterface::class,
            $this->task->getError()
        );
    }
}
