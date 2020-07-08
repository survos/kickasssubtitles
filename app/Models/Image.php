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
use KickAssSubtitles\Movie\ImageInterface;
use KickAssSubtitles\Movie\ImageProvider;
use KickAssSubtitles\Movie\ImageTrait;
use KickAssSubtitles\Movie\ImageType;
use KickAssSubtitles\Storage\EloquentStorage;
use KickAssSubtitles\Storage\HasStorageInterface;
use KickAssSubtitles\Storage\HasStorageTrait;
use KickAssSubtitles\Storage\StorageInterface;
use KickAssSubtitles\Support\HydratableInterface;
use KickAssSubtitles\Support\HydratableTrait;
use KickAssSubtitles\Support\ModelInterface;
use KickAssSubtitles\Support\ModelTrait;
use KickAssSubtitles\Support\ObjectCastsTrait;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Throwable;

/**
 * Class Poster.
 */
class Image extends Model implements ModelInterface, ImageInterface, HasStorageInterface, HasMedia, HydratableInterface
{
    use ModelTrait;
    use ImageTrait;
    use ObjectCastsTrait;
    use HasStorageTrait;
    use HasMediaTrait;
    use HydratableTrait;

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
     * {@inheritdoc}
     */
    public function getUrl(): string
    {
        return $this->getMedia(EloquentStorage::COLLECTION)->first()->getUrl();
    }

    /**
     * @return StorageInterface
     *
     * @throws Throwable
     */
    protected function createStorage(): StorageInterface
    {
        return EloquentStorage::create($this);
    }

    /**
     * @return array
     */
    protected function getObjectCasts(): array
    {
        return [
            static::TYPE => ImageType::class,
            static::PROVIDER => ImageProvider::class,
            static::PROVIDER_PREVIOUS => '?'.ImageProvider::class,
        ];
    }
}
