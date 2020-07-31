<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace Tests\App\Feature;

use Illuminate\Database\Eloquent\Model;
use KickAssSubtitles\Processor\TaskInterface;
use KickAssSubtitles\Processor\TaskStatus;
use KickAssSubtitles\Processor\TaskType;
use KickAssSubtitles\Storage\EloquentStorage;
use KickAssSubtitles\Storage\HasStorageTrait;
use KickAssSubtitles\Storage\StorageInterface;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

/**
 * Class Task.
 */
class Task extends Model implements HasMedia
{
    use HasMediaTrait;
    use HasStorageTrait;

    /**
     * @return static
     */
    public static function createNew(): self
    {
        $instance = new static();
        $instance->{TaskInterface::TYPE} = TaskType::TEST;
        $instance->{TaskInterface::IDENTIFIER} = uniqid();
        $instance->{TaskInterface::STATUS} = TaskStatus::PENDING;
        $instance->{TaskInterface::OPTIONS} = '[]';
        $instance->save();

        return $instance;
    }

    protected function createStorage(): StorageInterface
    {
        return EloquentStorage::create($this);
    }
}
