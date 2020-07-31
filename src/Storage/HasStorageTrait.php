<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace KickAssSubtitles\Storage;

use Throwable;

/**
 * Trait HasStorageTrait.
 */
trait HasStorageTrait
{
    /**
     * @var StorageInterface
     */
    protected $storage;

    /**
     * @throws Throwable
     */
    public function getStorage(): StorageInterface
    {
        if (null === $this->storage) {
            $this->storage = $this->createStorage();
        }

        return $this->storage;
    }

    public function setStorage(StorageInterface $storage): void
    {
        $this->storage = $storage;
    }

    /**
     * @throws Throwable
     */
    public function tearDownStorage(): void
    {
        $this->getStorage()->tearDown();
    }

    /**
     * @throws Throwable
     */
    protected function createStorage(): StorageInterface
    {
        return LocalStorage::create();
    }
}
