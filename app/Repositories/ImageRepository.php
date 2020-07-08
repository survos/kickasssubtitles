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

use KickAssSubtitles\Movie\ImageInterface;
use KickAssSubtitles\Movie\ImageRepository as BaseImageRepository;
use KickAssSubtitles\Movie\ImdbId;

/**
 * Class ImageRepository.
 */
class ImageRepository extends BaseImageRepository
{
    /**
     * {@inheritdoc}
     */
    public function findByImdbIdOrFail(ImdbId $imdbId): ImageInterface
    {
        $imageClass = $this->imageClass;
        $image = $imageClass::where(ImageInterface::IMDB_ID, $imdbId->getValue())->firstOrFail();

        return $image;
    }
}
