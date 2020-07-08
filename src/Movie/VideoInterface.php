<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace KickAssSubtitles\Movie;

use App\Models\Collections\SubtitleCollection;
use KickAssSubtitles\Subtitle\SubtitleInterface;
use Throwable;

/**
 * Interface VideoInterface.
 */
interface VideoInterface
{
    const IMDB_ID = 'imdb_id';

    const FILESIZE = 'filesize';

    const FILENAMES = 'filenames';

    const UPDATE_HASHES = 'update_hashes';

    /**
     * @return bool
     */
    public function getUpdateHashes(): bool;

    /**
     * @param bool $flag
     */
    public function setUpdateHashes(bool $flag): void;

    /**
     * @return int
     */
    public function getFilesize(): int;

    /**
     * @return string
     */
    public function getFilesizeHumanReadable(): string;

    /**
     * @return array
     */
    public function getFilenames(): array;

    /**
     * @param string $filename
     *
     * @throws Throwable
     */
    public function addFilename(string $filename): void;

    /**
     * @param SubtitleInterface $subtitle
     *
     * @throws Throwable
     */
    public function addSubtitle(SubtitleInterface $subtitle): void;

    /**
     * @return SubtitleCollection
     *
     * @throws Throwable
     */
    public function getSubtitles(): SubtitleCollection;
}
