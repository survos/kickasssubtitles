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

use Exception;
use KickAssSubtitles\Storage\StorageInterface;
use function Safe\file_get_contents;
use function Safe\scandir;
use function Safe\touch;
use function Safe\unlink;
use Throwable;

/**
 * Trait StorageTestTrait.
 */
trait StorageTestTrait
{
    /**
     * @var string
     */
    protected static $keyInput = 'input';

    /**
     * @var string
     */
    protected static $ext = 'txt';

    /**
     * @var StorageInterface
     */
    protected $storage;

    /**
     * @var string
     */
    protected $file;

    /**
     * @throws Throwable
     */
    public function testHasFile(): void
    {
        $this->assertEquals(false, $this->storage->hasFile(static::$keyInput));
        $this->storage->addFile(static::$keyInput, $this->file);
        $this->assertEquals(true, $this->storage->hasFile(static::$keyInput));
    }

    /**
     * @throws Throwable
     */
    public function testGetFile(): void
    {
        $addedFile = $this->storage->addFile(static::$keyInput, $this->file);
        $this->assertEquals($addedFile, $this->storage->getFile(static::$keyInput));
        $this->assertEquals(static::$ext, $this->storage->getFile(static::$keyInput, \PATHINFO_EXTENSION));
    }

    /**
     * @throws Throwable
     */
    public function testAddMissingFile(): void
    {
        $this->expectException(Exception::class);
        unlink($this->file);
        $this->storage->addFile(static::$keyInput, $this->file);
    }

    /**
     * @throws Throwable
     */
    public function testAddFile(): void
    {
        $addedFile = $this->storage->addFile(static::$keyInput, $this->file);
        $this->assertEquals(true, file_exists($addedFile));
        $this->assertEquals(true, file_exists($this->file));
    }

    /**
     * @throws Throwable
     */
    public function testAddFileContents(): void
    {
        $contents = 'lorem ipsum';
        $addedFile = $this->storage->addFileContents(static::$keyInput, $contents);
        $this->assertEquals(true, file_exists($addedFile));
        $this->assertEquals($contents, file_get_contents($addedFile));
    }

    /**
     * @throws Throwable
     */
    public function testTearDown(): void
    {
        $addedFile = $this->storage->addFile(static::$keyInput, $this->file);
        $this->assertEquals(true, file_exists($addedFile));
        $this->storage->tearDown();
        $this->assertEquals(false, file_exists($addedFile));
    }

    /**
     * @throws Throwable
     */
    protected function createTemporaryFile(): void
    {
        $this->file = sys_get_temp_dir().\DIRECTORY_SEPARATOR.uniqid().'.'.static::$ext;
        touch($this->file);
    }

    /**
     * @throws Throwable
     */
    protected function removeTemporaryFile(): void
    {
        if (file_exists($this->file)) {
            unlink($this->file);
        }
    }

    /**
     * @throws Throwable
     */
    protected function scanDir(string $dir): array
    {
        $scandir = scandir($dir);

        return array_values(array_diff($scandir, ['..', '.']));
    }
}
