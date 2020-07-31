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

    public function getUpdateHashes(): bool;

    public function setUpdateHashes(bool $flag): void;

    public function getFilesize(): int;

    public function getFilesizeHumanReadable(): string;

    public function getFilenames(): array;

    /**
     * @throws Throwable
     */
    public function addFilename(string $filename): void;

    /**
     * @throws Throwable
     */
    public function addSubtitle(SubtitleInterface $subtitle): void;

    /**
     * @throws Throwable
     */
    public function getSubtitles(): SubtitleCollection;
}
