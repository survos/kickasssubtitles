<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace Tests\Storage;

use Illuminate\Contracts\Support\Arrayable;
use KickAssSubtitles\Storage\LocalStorage;
use KickAssSubtitles\Storage\StorageInterface;
use KickAssSubtitles\Support\Str;
use PHPUnit\Framework\TestCase;
use Throwable;

/**
 * Class LocalStorageTest.
 */
class LocalStorageTest extends TestCase
{
    use StorageTestTrait;

    /**
     * @throws Throwable
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->storage = LocalStorage::create();
        $this->createTemporaryFile();
    }

    /**
     * @throws Throwable
     */
    public function tearDown(): void
    {
        $this->storage->tearDown();
        $this->removeTemporaryFile();
        parent::tearDown();
    }

    /**
     * @throws Throwable
     */
    public function testTmpFile(): void
    {
        $addedFile = $this->storage->tmpFile('test.txt');
        $dir = \dirname($addedFile);
        $dirArr = explode(\DIRECTORY_SEPARATOR, $dir);
        $this->assertEquals(true, file_exists($dir));
        $this->assertEquals(true, Str::uuidValid($dirArr[\count($dirArr) - 1]));
        $scannedDir = $this->scanDir($dir);
        $this->assertEquals(0, \count($scannedDir));
    }

    /**
     * @throws Throwable
     */
    public function testToArrayCreateFromArray(): void
    {
        $addedFile = $this->storage->addFileContents(
            static::$keyInput,
            'lorem ipsum',
            'test.txt'
        );

        /** @var Arrayable $arrayable */
        $arrayable = $this->storage;

        $array = $arrayable->toArray();
        $this->assertEquals([
            StorageInterface::IDENTIFIER => $array[StorageInterface::IDENTIFIER],
            StorageInterface::ROOT_PATH => LocalStorage::getDefaultRootPath(),
            StorageInterface::FILES => [
                static::$keyInput => 'test.txt',
            ],
        ], $array);

        $storage = LocalStorage::hydrateFromArray($array);
        $this->assertInstanceOf(StorageInterface::class, $storage);
    }
}
