<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace Tests\App\Feature\Subtitle\Provider;

use Illuminate\Foundation\Testing\RefreshDatabase;
use KickAssSubtitles\Encoding\Encoding;
use KickAssSubtitles\Language\Language;
use KickAssSubtitles\Movie\ImdbId;
use KickAssSubtitles\Movie\VideoInterface;
use KickAssSubtitles\Movie\VideoRepositoryInterface;
use KickAssSubtitles\Processor\TaskStatus;
use KickAssSubtitles\Subtitle\Provider\KickAssSubtitlesSubtitleProvider;
use KickAssSubtitles\Subtitle\SubtitleFormat;
use KickAssSubtitles\Subtitle\SubtitleInterface;
use KickAssSubtitles\Subtitle\SubtitleProvider;
use KickAssSubtitles\Subtitle\SubtitleRepositoryInterface;
use Tests\App\TestCase;
use Throwable;

/**
 * Class KickAssSubtitlesSubtitleProviderTest.
 */
class KickAssSubtitlesSubtitleProviderTest extends TestCase
{
    use CreatesTaskTrait;
    use RefreshDatabase;

    /**
     * @var array
     */
    protected $connectionsToTransact = [];

    /**
     * @throws Throwable
     */
    public function testProvider(): void
    {
        /** @var VideoRepositoryInterface $videoRepository */
        $videoRepository = app(VideoRepositoryInterface::class);
        $video = $videoRepository->create(
            [
                SubtitleProvider::OPENSUBTITLES => '163ce22b6261f50a_INVALID',
                SubtitleProvider::NAPIPROJEKT => 'b7d32bdb4ad75e00178259ad4c11b9a1',
            ],
            ['Showgirls (1995).mp4'],
            2094235131,
            new ImdbId('tt0114436')
        );
        $video->setUpdateHashes(true);

        /** @var SubtitleRepositoryInterface $subtitleRepository */
        $subtitleRepository = app(SubtitleRepositoryInterface::class);
        $subtitle = $subtitleRepository->create(
            'lorem ipsum',
            SubtitleFormat::SUBRIP(),
            Language::PL(),
            Encoding::UTF_8(),
            new ImdbId('tt0114436'),
            SubtitleProvider::OPENSUBTITLES()
        );

        $video->addSubtitle($subtitle);

        $subtitleFile = $subtitle->getFile();

        /** @var KickAssSubtitlesSubtitleProvider $subtitleProvider */
        $subtitleProvider = $this->app->make(KickAssSubtitlesSubtitleProvider::class);

        $this->task = $this->createTask();

        $subtitleProvider->processOne($this->task);

        /** @var VideoInterface $videoSaved */
        $videoSaved = $videoRepository->findByIdOrFail((int) $video->getId());

        $this->assertEquals('163ce22b6261f50a', $videoSaved->getHash(SubtitleProvider::OPENSUBTITLES()));
        $this->assertEquals(false, $videoSaved->getUpdateHashes());

        $this->assertEquals(true, $this->task->getStatus()->equals(TaskStatus::COMPLETED()));

        $results = $this->task->getResults();

        $this->assertGreaterThan(0, $results->count());
        $this->assertInstanceOf(SubtitleInterface::class, $results->first());
        $this->assertNotEquals($subtitleFile, $results->first()->getFile());

        $subtitle->tearDownStorage();
    }
}
