<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace KickAssSubtitles\Processor;

use KickAssSubtitles\Storage\HasStorageInterface;
use KickAssSubtitles\Storage\HasStorageTrait;
use KickAssSubtitles\Storage\LocalStorage;
use KickAssSubtitles\Storage\StorageInterface;
use KickAssSubtitles\Support\ObjectCastsTrait;
use KickAssSubtitles\Support\TablelessModel;
use Throwable;

/**
 * Class Task.
 */
class Task extends TablelessModel implements TaskInterface, HasStorageInterface
{
    use TaskTrait;
    use HasStorageTrait {
        tearDownStorage as tearDownStorageTrait;
        createStorage as createStorageTrait;
    }
    use ObjectCastsTrait;

    /**
     * @var array
     */
    protected $casts = [
        self::OPTIONS => 'array',
        self::RESULTS => 'array',
        self::ERROR => 'array',
    ];

    /**
     * @var array
     */
    protected $attributes = [
        self::RESULTS => '[]',
    ];

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
                    'task',
                    LocalStorage::TEMPLATE_SEPARATOR,
                    $this->getId()
                ),
                storage_path('tmp')
            );
        }

        return $this->createStorageTrait();
    }

    /**
     * @throws Throwable
     */
    public function tearDownStorage(): void
    {
        foreach ($this->getResults() as $result) {
            if ($result instanceof HasStorageInterface) {
                $result->tearDownStorage();
            }
        }

        $this->tearDownStorageTrait();
    }

    /**
     * @return array
     */
    protected function getObjectCasts(): array
    {
        return [
            static::TYPE => TaskType::class,
            static::STATUS => TaskStatus::class,
            static::PROCESSOR_NAME => '?'.ProcessorName::class,
        ];
    }
}
