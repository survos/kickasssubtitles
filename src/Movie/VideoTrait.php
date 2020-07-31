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

use KickAssSubtitles\Movie\Exception\InvalidVideoFilenameException;
use KickAssSubtitles\Support\Str;

/**
 * Trait VideoTrait.
 */
trait VideoTrait
{
    /**
     * {@inheritdoc}
     */
    public function getUpdateHashes(): bool
    {
        return $this->getAttribute(static::UPDATE_HASHES);
    }

    /**
     * {@inheritdoc}
     */
    public function setUpdateHashes(bool $flag): void
    {
        $this->setAttribute(static::UPDATE_HASHES, $flag);
        $this->save();
    }

    /**
     * {@inheritdoc}
     */
    public function getFilesize(): int
    {
        return $this->getAttribute(static::FILESIZE);
    }

    /**
     * {@inheritdoc}
     */
    public function getFilesizeHumanReadable(): string
    {
        return Str::formatBytes($this->getFilesize());
    }

    /**
     * {@inheritdoc}
     */
    public function getFilenames(): array
    {
        return $this->getAttribute(static::FILENAMES);
    }

    /**
     * {@inheritdoc}
     */
    public function addFilename(string $filename): void
    {
        $filename = trim($filename);
        if (empty($filename)) {
            throw new InvalidVideoFilenameException('Filename is empty string');
        }
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        if (!VideoFormat::isValidExtension($extension)) {
            throw new InvalidVideoFilenameException(sprintf('Filename has invalid extension [%s]', $filename));
        }
        $filenames = $this->getFilenames();
        $existing = [];
        foreach ($filenames as $fn) {
            $existing[] = mb_strtolower($fn);
        }
        if (\in_array(mb_strtolower($filename), $existing, true)) {
            throw new InvalidVideoFilenameException(sprintf('Filename already added [%s]', $filename));
        }
        $filenames[] = $filename;

        $this->setAttribute(static::FILENAMES, $filenames);

        $this->save();
    }
}
