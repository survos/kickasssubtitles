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

use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use KickAssSubtitles\Subtitle\SubtitleProvider;
use KickAssSubtitles\Support\Exception\NotImplementedException;
use KickAssSubtitles\Support\FiltersInterface;
use KickAssSubtitles\Support\ModelInterface;

/**
 * Class VideoRepository.
 */
class VideoRepository implements VideoRepositoryInterface
{
    /**
     * @var string
     */
    protected $videoClass;

    /**
     * @param string $videoClass
     */
    public function __construct(
        string $videoClass
    ) {
        $this->videoClass = $videoClass;
    }

    /**
     * {@inheritdoc}
     */
    public function create(
        array $hashes,
        array $filenames,
        int $filesize,
        ImdbId $imdbId
    ): VideoInterface {
        if ($filesize < 1) {
            throw new Exception(static::ERR_INVALID_FILESIZE);
        }

        if (empty($filenames)) {
            throw new Exception(static::ERR_MISSING_FILENAMES);
        }

        $videoClass = $this->videoClass;
        $video = new $videoClass();

        // validate that required hashes are set
        if (!isset($hashes[SubtitleProvider::OPENSUBTITLES])) {
            throw new Exception(static::ERR_MISSING_REQUIRED_HASHES);
        }
        if (!isset($hashes[SubtitleProvider::NAPIPROJEKT])) {
            throw new Exception(static::ERR_MISSING_REQUIRED_HASHES);
        }

        $video->setAttribute(VideoInterface::FILESIZE, $filesize);
        $video->setAttribute(VideoInterface::IMDB_ID, $imdbId);
        $video->save();

        $video->setHashes($hashes);

        foreach ($filenames as $filename) {
            $video->addFilename($filename);
        }

        return $video;
    }

    /**
     * {@inheritdoc}
     */
    public function findByIdOrFail(int $id): ModelInterface
    {
        throw new NotImplementedException();
    }

    /**
     * {@inheritdoc}
     */
    public function findAll(FiltersInterface $filters): LengthAwarePaginator
    {
        throw new NotImplementedException();
    }

    /**
     * {@inheritdoc}
     */
    public function delete(object $entity): void
    {
        throw new NotImplementedException();
    }

    /**
     * {@inheritdoc}
     */
    public function findByProviderHashOrFail(
        string $hash,
        SubtitleProvider $provider
    ): VideoInterface {
        throw new NotImplementedException();
    }
}
