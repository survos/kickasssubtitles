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
use KickAssSubtitles\Subtitle\Converter\SubotageSubtitleConverter;
use KickAssSubtitles\Subtitle\SubtitleFormat;
use function Safe\file_get_contents;
use Tests\App\TestCase;
use Throwable;

/**
 * Class SubotageSubtitleConverterTest.
 */
class SubotageSubtitleConverterTest extends TestCase
{
    use RefreshDatabase;
    use CreatesTaskTrait;

    /**
     * @var array
     */
    protected $connectionsToTransact = [];

    /**
     * @var SubotageSubtitleConverter
     */
    protected $converter;

    public function setUp()
    {
        parent::setUp();
        /* @var SubotageSubtitleConverter converter */
        $this->converter = $this->app->make(SubotageSubtitleConverter::class);
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
            "[INFORMATION]\n[TITLE] none\n[AUTHOR] none\n[SOURCE]\n[FILEPATH]Media\n[DELAY]0\n[COMMENT] Created using subotage - universal subtitle converter for bash\n[END INFORMATION]\n[SUBTITLE]\n[COLF]&HFFFFFF,[STYLE]bd,[SIZE]18,[FONT]Arial\n00:01:02.89,00:01:06.17\nLOS ANGELES, ROK 2029\n\n",
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
