<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace App\Repositories;

use KickAssSubtitles\Movie\VideoInterface;
use KickAssSubtitles\Movie\VideoRepository as BaseVideoRepository;
use KickAssSubtitles\Subtitle\SubtitleProvider;
use KickAssSubtitles\Support\ModelInterface;

/**
 * Class VideoRepository.
 */
class VideoRepository extends BaseVideoRepository
{
    /**
     * {@inheritdoc}
     */
    public function findByIdOrFail(int $id): ModelInterface
    {
        $videoClass = $this->videoClass;

        return $videoClass::findOrFail($id);
    }

    /**
     * {@inheritdoc}
     */
    public function findByProviderHashOrFail(
        string $hash,
        SubtitleProvider $provider
    ): VideoInterface {
        $videoClass = $this->videoClass;

        return $videoClass::where($provider->getHashStorageField(), $hash)->firstOrFail();
    }
}
