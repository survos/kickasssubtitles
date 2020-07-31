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
 * Interface StorageInterface.
 */
interface StorageInterface
{
    const ERR_FILE_NOT_FOUND = 'File not found';

    const ERR_FILE_ALREADY_ADDED = 'File already added';

    const TEMPLATE_SEPARATOR = '-';

    const TEMPLATE_SUFFIX_COPY = 'copy';

    const TEMPLATE_PREFIX_STORAGE = 'storage';

    const IDENTIFIER = 'identifier';

    const ROOT_PATH = 'root_path';

    const FILES = 'files';

    /**
     * Checks if file exists in storage under given `$key`.
     */
    public function hasFile(string $key): bool;

    /**
     * Returns full path to the file stored under given `$key`.
     * Returned path is passed to `pathinfo()` if `$element` is provided.
     *
     * @throws Throwable
     */
    public function getFile(string $key, ?int $element = null): string;

    /**
     * Adds file to the storage under given `$key`.
     * It will copy original file to storage destination path.
     * Returns destination path.
     *
     * @throws Throwable
     */
    public function addFile(string $key, string $file): string;

    /**
     * Creates file from contents and adds it to the storage under given `$key`.
     * It will generate random name if `$name` is not passed.
     * Returns destination path.
     *
     * @throws Throwable
     */
    public function addFileContents(
        string $key,
        string $contents,
        ?string $name = null
    ): string;

    /**
     * Adds "dummy" file to the storage under given `$name`.
     * It will generate random `$key` if not passed.
     * The file is not actually created on the disk.
     *
     * @throws Throwable
     */
    public function tmpFile(string $name, ?string $key = null): string;

    /**
     * Deletes file from storage under given `$key`.
     *
     * @throws Throwable
     */
    public function deleteFile(string $key): void;

    /**
     * Copies file under given `$key` to new "copy key".
     *
     * @throws Throwable
     */
    public function copyFile(string $key): void;

    /**
     * Copies file under "copy key" back to `$key` and deletes the copy.
     *
     * @throws Throwable
     */
    public function restoreFile(string $key): void;

    /**
     * Destroys all files tracked by this storage.
     *
     * @throws Throwable
     */
    public function tearDown(): void;
}
