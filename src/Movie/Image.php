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

use Illuminate\Contracts\Support\Arrayable;
use KickAssSubtitles\Storage\HasStorageInterface;
use KickAssSubtitles\Storage\HasStorageTrait;
use KickAssSubtitles\Storage\LocalStorage;
use KickAssSubtitles\Storage\StorageInterface;
use KickAssSubtitles\Support\HydratableInterface;
use KickAssSubtitles\Support\ObjectCastsTrait;
use KickAssSubtitles\Support\TablelessModel;
use Throwable;

/**
 * Class Image.
 */
class Image extends TablelessModel implements ImageInterface, HasStorageInterface, HydratableInterface
{
    use ImageTrait;
    use ObjectCastsTrait;
    use HasStorageTrait {
        createStorage as createStorageTrait;
    }

    /**
     * {@inheritdoc}
     */
    public static function hydrateFromArray(array $array = []): object
    {
        if (isset($array[HydratableInterface::CLASS_ATTRIBUTE])) {
            unset($array[HydratableInterface::CLASS_ATTRIBUTE]);
        }

        if (isset($array[static::STORAGE_IMAGE])) {
            $storage = LocalStorage::hydrateFromArray($array[static::STORAGE_IMAGE]);
            unset($array[static::STORAGE_IMAGE]);
        }

        $instance = new static();
        $image = $instance->newInstance($array, true);
        $image->setStorage($storage);

        return $image;
    }

    /**
     * @return StorageInterface
     *
     * @throws Throwable
     */
    protected function createStorage(): StorageInterface
    {
        if (function_exists('app') && app()->has('path.storage')) {
            return LocalStorage::create(
                sprintf(
                    '%s%s%s%s%s',
                    LocalStorage::TEMPLATE_PREFIX_STORAGE,
                    LocalStorage::TEMPLATE_SEPARATOR,
                    'image',
                    LocalStorage::TEMPLATE_SEPARATOR,
                    $this->getId()
                ),
                storage_path('tmp')
            );
        }

        return $this->createStorageTrait();
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
        $array[static::STORAGE_IMAGE] = $storage->toArray();

        return $array;
    }
}
