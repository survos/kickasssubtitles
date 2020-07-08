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

use KickAssSubtitles\Movie\ImageInterface;
use KickAssSubtitles\Movie\MovieInterface;
use KickAssSubtitles\Movie\Provider\TmdbMovieProvider;
use Tests\App\TestCase;
use Throwable;

/**
 * Class TmdbMovieProviderTest.
 */
class TmdbMovieProviderTest extends TestCase
{
    use CreatesTaskTrait;

    /**
     * @throws Throwable
     */
    public function testProvider()
    {
        $this->task = $this->createTask();

        /** @var TmdbMovieProvider $movieProvider */
        $movieProvider = $this->app->make(TmdbMovieProvider::class);

        $movieProvider->processOne($this->task);

        $foundMovie = $this->task->getResults()->first();
        $this->assertInstanceOf(MovieInterface::class, $foundMovie);
        $this->assertEquals(static::$movieTitle, $foundMovie->getTitle());

        $foundImage = $this->task->getResults()->last();
        $this->assertInstanceOf(ImageInterface::class, $foundImage);
    }
}
