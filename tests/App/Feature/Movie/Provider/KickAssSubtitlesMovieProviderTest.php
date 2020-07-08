<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace Tests\App\Feature\Movie\Provider;

use Illuminate\Foundation\Testing\RefreshDatabase;
use KickAssSubtitles\Movie\ImageInterface;
use KickAssSubtitles\Movie\ImageProvider;
use KickAssSubtitles\Movie\ImageType;
use KickAssSubtitles\Movie\MovieInterface;
use KickAssSubtitles\Movie\MovieProvider;
use KickAssSubtitles\Movie\MovieType;
use KickAssSubtitles\Movie\MovieYear;
use KickAssSubtitles\Movie\Provider\KickAssSubtitlesMovieProvider;
use Tests\App\TestCase;
use Throwable;

/**
 * Class KickAssSubtitleMovieProviderTest.
 */
class KickAssSubtitlesMovieProviderTest extends TestCase
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
    public function testProvider()
    {
        $this->task = $this->createTask();

        /** @var KickAssSubtitlesMovieProvider $movieProvider */
        $movieProvider = $this->app->make(KickAssSubtitlesMovieProvider::class);

        $movieRepository = $movieProvider->getMovieRepository();
        $movieRepository->create(
            static::$movieTitle,
            $this->task->getOptions()->getImdbId(),
            new MovieYear(static::$movieYear),
            MovieType::MOVIE(),
            MovieProvider::KICKASSSUBTITLES()
        );

        $imageRepository = $movieProvider->getImageRepository();
        $image = $imageRepository->createFromUrlOrPath(
            __DIR__.DIRECTORY_SEPARATOR.'tt0114436.poster.jpg',
            $this->task->getOptions()->getImdbId(),
            ImageType::POSTER(),
            ImageProvider::KICKASSSUBTITLES()
        );
        $imageFile = $image->getFile();

        $movieProvider->processOne($this->task);

        $foundMovie = $this->task->getResults()->first();
        $this->assertInstanceOf(MovieInterface::class, $foundMovie);
        $this->assertEquals(static::$movieTitle, $foundMovie->getTitle());

        $foundImage = $this->task->getResults()->last();
        $this->assertInstanceOf(ImageInterface::class, $foundImage);
        $this->assertNotEquals($imageFile, $foundImage->getFile());

        $image->tearDownStorage();
    }
}
