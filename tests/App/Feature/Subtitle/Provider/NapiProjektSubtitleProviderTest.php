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
use KickAssSubtitles\Processor\TaskStatus;
use KickAssSubtitles\Subtitle\Provider\NapiProjektSubtitleProvider;
use KickAssSubtitles\Subtitle\SubtitleInterface;
use Tests\App\TestCase;
use Throwable;

/**
 * Class NapiProjektSubtitleProviderTest.
 */
class NapiProjektSubtitleProviderTest extends TestCase
{
    use RefreshDatabase;
    use CreatesTaskTrait;

    /**
     * @var array
     */
    protected $connectionsToTransact = [];

    /**
     * @throws Throwable
     */
    public function testProvider(): void
    {
        /** @var NapiProjektSubtitleProvider $subtitleProvider */
        $subtitleProvider = $this->app->make(NapiProjektSubtitleProvider::class);

        $this->task = $this->createTask();

        $subtitleProvider->processOne($this->task);

        $this->assertEquals(true, $this->task->getStatus()->equals(TaskStatus::COMPLETED()));

        $results = $this->task->getResults();

        $this->assertGreaterThan(0, $results->count());

        foreach ($results as $subtitle) {
            $this->assertInstanceOf(SubtitleInterface::class, $subtitle);
        }
    }
}
