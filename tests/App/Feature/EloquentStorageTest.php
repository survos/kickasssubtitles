<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace Tests\App\Feature;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use KickAssSubtitles\Storage\EloquentStorage;
use Tests\App\TestCase;
use Tests\Storage\StorageTestTrait;
use Throwable;

/**
 * Class EloquentStorageTest.
 */
class EloquentStorageTest extends TestCase
{
    use RefreshDatabase;
    use StorageTestTrait;

    /**
     * @var array
     */
    protected $connectionsToTransact = [];

    /**
     * @throws Throwable
     */
    public function setUp()
    {
        parent::setUp();
        $task = Task::createNew();
        $this->storage = $task->getStorage();
        $this->createTemporaryFile();
    }

    /**
     * @throws Throwable
     */
    public function tearDown()
    {
        $this->removeTemporaryFile();
        /** @var EloquentStorage $storage */
        $storage = $this->storage;
        /** @var Model $model */
        $model = $storage->getModel();
        $model->delete();
        parent::tearDown();
    }

    /**
     * @throws Throwable
     */
    public function testTmpFile(): void
    {
        $addedFile = $this->storage->tmpFile('test.txt');
        $dir = \dirname($addedFile);
        $this->assertEquals(true, file_exists($dir));
        $scannedDir = $this->scanDir($dir);
        $this->assertEquals(0, \count($scannedDir));
    }
}
