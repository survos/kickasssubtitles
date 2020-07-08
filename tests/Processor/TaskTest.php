<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace Tests\Processor;

use Illuminate\Support\Collection;
use KickAssSubtitles\Processor\TaskCollectionInterface;
use KickAssSubtitles\Processor\TaskInterface;
use KickAssSubtitles\Processor\TaskOptions;
use KickAssSubtitles\Processor\TaskStatus;
use KickAssSubtitles\Processor\TaskType;
use KickAssSubtitles\Storage\HasStorageInterface;
use KickAssSubtitles\Storage\StorageInterface;
use KickAssSubtitles\Support\Str;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Throwable;

/**
 * Class TaskTest.
 */
class TaskTest extends TestCase
{
    use CreatesTaskTrait;

    /**
     * @throws Throwable
     */
    public function testInstanceCreation(): void
    {
        $task = $this->createTask();

        $this->assertInstanceOf(TaskInterface::class, $task);
        $this->assertEquals(true, $task->getStatus()->equals(TaskStatus::PENDING()));
        $this->assertInstanceOf(TaskOptions::class, $task->getOptions());
        $this->assertEquals(false, $task->isProcessed());
        $this->assertEquals(true, Str::uuidValid($task->getIdentifier()));
        $this->assertEquals(true, $task->getType()->equals(TaskType::DEFAULT()));
        $this->assertInstanceOf(TaskCollectionInterface::class, $task->getChildren());
        /** @var Collection $children */
        $children = $task->getChildren();
        $this->assertEquals(0, $children->count());
        /** @var HasStorageInterface $taskWithStorage */
        $taskWithStorage = $task;
        $this->assertInstanceOf(StorageInterface::class, $taskWithStorage->getStorage());
    }

    /**
     * @throws Throwable
     */
    public function testAddChild(): void
    {
        $task = $this->createTask();
        $childTask = $this->createTask();
        $task->addChild($childTask);
        /** @var Collection $children */
        $children = $task->getChildren();
        $this->assertEquals(1, $children->count());
        $this->assertEquals(null, $task->getParent());
        $parent = $childTask->getParent();
        if (!$parent) {
            throw new RuntimeException();
        }
        $this->assertEquals($task->getIdentifier(), $parent->getIdentifier());
    }
}
