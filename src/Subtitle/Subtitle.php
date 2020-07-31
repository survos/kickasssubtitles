<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace KickAssSubtitles\Subtitle;

use Illuminate\Contracts\Support\Arrayable;
use KickAssSubtitles\Encoding\Encoding;
use KickAssSubtitles\Language\Language;
use KickAssSubtitles\Movie\ImdbId;
use KickAssSubtitles\Storage\HasStorageInterface;
use KickAssSubtitles\Storage\HasStorageTrait;
use KickAssSubtitles\Storage\LocalStorage;
use KickAssSubtitles\Storage\StorageInterface;
use KickAssSubtitles\Support\HydratableInterface;
use KickAssSubtitles\Support\ObjectCastsTrait;
use KickAssSubtitles\Support\TablelessModel;
use Throwable;

/**
 * Class Subtitle.
 */
class Subtitle extends TablelessModel implements SubtitleInterface, HasStorageInterface, HydratableInterface
{
    use HasStorageTrait {
        createStorage as createStorageTrait;
    }
    use ObjectCastsTrait;
    use SubtitleTrait;

    /**
     * {@inheritdoc}
     */
    public static function hydrateFromArray(array $array = []): object
    {
        if (isset($array[HydratableInterface::CLASS_ATTRIBUTE])) {
            unset($array[HydratableInterface::CLASS_ATTRIBUTE]);
        }

        if (isset($array[static::STORAGE_SUBTITLE])) {
            $storage = LocalStorage::hydrateFromArray($array[static::STORAGE_SUBTITLE]);
            unset($array[static::STORAGE_SUBTITLE]);
        }

        $instance = new static();
        $subtitle = $instance->newInstance($array, true);
        $subtitle->setStorage($storage);

        return $subtitle;
    }

    /**
     * @throws Throwable
     */
    protected function createStorage(): StorageInterface
    {
        if (\function_exists('app') && app()->has('path.storage')) {
            return LocalStorage::create(
                sprintf(
                    '%s%s%s%s%s',
                    LocalStorage::TEMPLATE_PREFIX_STORAGE,
                    LocalStorage::TEMPLATE_SEPARATOR,
                    'subtitle',
                    LocalStorage::TEMPLATE_SEPARATOR,
                    $this->getId()
                ),
                storage_path('tmp')
            );
        }

        return $this->createStorageTrait();
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

    /**
     * @return array
     *
     * @throws Throwable
     */
    public function toArray()
    {
        $array = parent::toArray();
        $array[HydratableInterface::CLASS_ATTRIBUTE] = static::class;

        /** @var Arrayable $storage */
        $storage = $this->getStorage();
        $array[static::STORAGE_SUBTITLE] = $storage->toArray();

        return $array;
    }
}
