<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use KickAssSubtitles\Movie\ImageInterface;
use KickAssSubtitles\Movie\ImageType;
use KickAssSubtitles\Movie\MovieInterface;
use KickAssSubtitles\Movie\MovieProvider;
use KickAssSubtitles\Movie\MovieTrait;
use KickAssSubtitles\Movie\MovieType;
use KickAssSubtitles\Support\HydratableInterface;
use KickAssSubtitles\Support\HydratableTrait;
use KickAssSubtitles\Support\ModelInterface;
use KickAssSubtitles\Support\ModelTrait;
use KickAssSubtitles\Support\ObjectCastsTrait;

/**
 * Class Movie.
 */
class Movie extends Model implements MovieInterface, ModelInterface, HydratableInterface
{
    use MovieTrait;
    use ModelTrait;
    use ObjectCastsTrait;
    use HydratableTrait;

    const SEARCHED_AT = 'searched_at';

    const IMAGES = 'images';

    const VIDEOS = 'videos';

    /**
     * @var array
     */
    protected $with = [
        self::IMAGES,
    ];

    /**
     * @var array
     */
    protected $hidden = [
        self::IMAGES,
    ];

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @return mixed|string
     */
    public function getRouteKey()
    {
        return hashid_encode((int) $this->getId());
    }

    public function touchSearchedAt(): void
    {
        $this->setAttribute(static::SEARCHED_AT, Carbon::now()->format(static::MYSQL_DATETIME));
        $this->save();
    }

    /**
     * @return ImageInterface|null
     */
    public function getPoster(): ?ImageInterface
    {
        $images = $this->getAttribute(static::IMAGES)->filter(function ($image) {
            return $image->getType()->equals(ImageType::POSTER());
        });

        if ($images->isEmpty()) {
            return null;
        }

        return $images->first();
    }

    /**
     * @return Collection
     */
    public function getVideos(): Collection
    {
        return $this->getAttribute(static::VIDEOS);
    }

    /**
     * @return HasMany
     */
    public function images(): HasMany
    {
        return $this->hasMany(
            Image::class,
            Image::IMDB_ID,
            static::IMDB_ID
        );
    }

    /**
     * @return HasMany
     */
    public function videos(): HasMany
    {
        return $this->hasMany(
            Video::class,
            Video::IMDB_ID,
            static::IMDB_ID
        );
    }

    /**
     * @return array
     */
    protected function getObjectCasts(): array
    {
        return [
            static::TYPE => MovieType::class,
            static::PROVIDER => MovieProvider::class,
            static::PROVIDER_PREVIOUS => '?'.MovieProvider::class,
        ];
    }
}
