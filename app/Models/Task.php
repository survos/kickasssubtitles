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
use App\Models\Collections\TaskCollection;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;
use InvalidArgumentException;
use KickAssSubtitles\Processor\ProcessorName;
use KickAssSubtitles\Processor\TaskCollectionInterface;
use KickAssSubtitles\Processor\TaskInterface;
use KickAssSubtitles\Processor\TaskStatus;
use KickAssSubtitles\Processor\TaskTrait;
use KickAssSubtitles\Processor\TaskType;
use KickAssSubtitles\Storage\EloquentStorage;
use KickAssSubtitles\Storage\HasStorageInterface;
use KickAssSubtitles\Storage\HasStorageTrait;
use KickAssSubtitles\Storage\StorageInterface;
use KickAssSubtitles\Support\DownloadableInterface;
use KickAssSubtitles\Support\ModelInterface;
use KickAssSubtitles\Support\ModelTrait;
use KickAssSubtitles\Support\ObjectCastsTrait;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Throwable;

/**
 * Class Task.
 */
class Task extends Model implements ModelInterface, TaskInterface, HasStorageInterface, HasMedia, DownloadableInterface
{
    use ModelTrait;
    use TaskTrait;
    use HasStorageTrait {
        tearDownStorage as tearDownStorageTrait;
    }
    use HasMediaTrait;
    use ObjectCastsTrait;

    const ERR_INVALID_GROUP = 'Invalid group';

    const USER_ID = 'user_id';

    const PARENT_ID = 'parent_id';

    const GROUP = 'group';

    const PARENT = 'parent';

    const CHILDREN = 'children';

    /**
     * @var array
     */
    protected $with = [
        EloquentStorage::MEDIA,
    ];

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
     * @return string
     */
    public function getRouteKey()
    {
        return hashid_encode((int) $this->getId());
    }

    /**
     * {@inheritdoc}
     */
    public function isDownloadable(): bool
    {
        return $this->getStatus()->equals(TaskStatus::COMPLETED());
    }

    /**
     * @return string|null
     */
    public function getGroup(): ?string
    {
        return $this->getAttribute(static::GROUP);
    }

    /**
     * @param string $group
     *
     * @throws Throwable
     */
    public function setGroup(string $group): void
    {
        if (empty($group)) {
            throw new InvalidArgumentException(static::ERR_INVALID_GROUP);
        }
        $this->setAttribute(static::GROUP, $group);
        $this->save();
    }

    /**
     * @return int|null
     */
    public function getUserId(): ?int
    {
        return $this->getAttribute(static::USER_ID);
    }

    /**
     * @param int|null $id
     *
     * @throws Throwable
     */
    public function setUserId(?int $id): void
    {
        $this->setAttribute(static::USER_ID, $id);
        $this->save();
    }

    /**
     * @return StorageInterface
     *
     * @throws Throwable
     */
    protected function createStorage(): StorageInterface
    {
        return EloquentStorage::create($this, Filesystem::TASKS);
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
     * {@inheritdoc}
     */
    public function getParent(): ?TaskInterface
    {
        return $this->getAttribute(static::PARENT);
    }

    /**
     * {@inheritdoc}
     */
    public function getChildren(): TaskCollectionInterface
    {
        return $this->getAttribute(static::CHILDREN);
    }

    /**
     * {@inheritdoc}
     */
    public function addChild(TaskInterface $task): void
    {
        /** @var Task $model */
        $model = $task;
        $this->children()->save($model);
    }

    /**
     * @return BelongsTo
     */
    public function parent(): BelongsTo
    {
        /** @var Builder $relation */
        $relation = $this->belongsTo(static::class, static::PARENT_ID);
        $relation->where(static::PARENT_ID, null);

        /** @var BelongsTo $belongsTo */
        $belongsTo = $relation;

        return $belongsTo;
    }

    /**
     * @return HasMany
     */
    public function children(): HasMany
    {
        return $this->hasMany(
            static::class,
            static::PARENT_ID
        );
    }

    /**
     * @param array $models
     *
     * @return Collection
     */
    public function newCollection(array $models = [])
    {
        return new TaskCollection($models);
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
