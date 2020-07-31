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

use App\Enums\Filesystem;
use App\Models\Collections\SubtitleCollection;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use KickAssSubtitles\Encoding\Encoding;
use KickAssSubtitles\Language\Language;
use KickAssSubtitles\Movie\ImdbId;
use KickAssSubtitles\Movie\VideoInterface;
use KickAssSubtitles\Storage\EloquentStorage;
use KickAssSubtitles\Storage\HasStorageInterface;
use KickAssSubtitles\Storage\HasStorageTrait;
use KickAssSubtitles\Storage\StorageInterface;
use KickAssSubtitles\Subtitle\SubtitleFormat;
use KickAssSubtitles\Subtitle\SubtitleInterface;
use KickAssSubtitles\Subtitle\SubtitleProvider;
use KickAssSubtitles\Subtitle\SubtitleTrait;
use KickAssSubtitles\Support\HydratableInterface;
use KickAssSubtitles\Support\HydratableTrait;
use KickAssSubtitles\Support\ModelInterface;
use KickAssSubtitles\Support\ModelTrait;
use KickAssSubtitles\Support\ObjectCastsTrait;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Throwable;

/**
 * Class Subtitle.
 */
class Subtitle extends Model implements ModelInterface, SubtitleInterface, HasStorageInterface, HasMedia, HydratableInterface
{
    use HasMediaTrait;
    use HasStorageTrait;
    use HydratableTrait;
    use ModelTrait;
    use ObjectCastsTrait;
    use SubtitleTrait;

    const VIDEO_ID = 'video_id';

    const VIDEO = 'video';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var array
     */
    protected $with = [
        EloquentStorage::MEDIA,
    ];

    /**
     * @var array
     */
    protected $hidden = [
        EloquentStorage::MEDIA,
    ];

    /**
     * @return mixed|string
     */
    public function getRouteKey()
    {
        return hashid_encode((int) $this->getId());
    }

    /**
     * {@inheritdoc}
     */
    public function getVideo(): ?VideoInterface
    {
        return $this->getAttribute(static::VIDEO);
    }

    /**
     * @throws Throwable
     */
    protected function createStorage(): StorageInterface
    {
        return EloquentStorage::create($this, Filesystem::SUBTITLES);
    }

    public function video(): BelongsTo
    {
        return $this->belongsTo(Video::class);
    }

    /**
     * @return Collection
     */
    public function newCollection(array $models = [])
    {
        return new SubtitleCollection($models);
    }

    protected function getObjectCasts(): array
    {
        return [
            static::FORMAT => SubtitleFormat::class,
            static::LANGUAGE => Language::class,
            static::ENCODING => Encoding::class,
            static::IMDB_ID => '?'.ImdbId::class,
            static::PROVIDER => '?'.SubtitleProvider::class,
            static::PROVIDER_PREVIOUS => '?'.SubtitleProvider::class,
        ];
    }
}
