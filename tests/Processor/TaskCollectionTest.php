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

use Exception;
use Illuminate\Support\Collection;
use KickAssSubtitles\Processor\TaskCollection;
use KickAssSubtitles\Processor\TaskStatus;
use PHPUnit\Framework\TestCase;
use Throwable;

/**
 * Class TaskCollectionTest.
 */
class TaskCollectionTest extends TestCase
{
    use CreatesTaskTrait;

    /**
     * @throws Throwable
     */
    public function testContainsTask(): void
    {
        $taskA = $this->createTask();
        $taskB = $this->createTask();

        $collection = new TaskCollection();
        $collection
            ->addTask($taskA)
        ;

        $this->assertEquals(true, $collection->containsTask($taskA));
        $this->assertEquals(false, $collection->containsTask($taskB));
    }

    /**
     * @throws Throwable
     */
    public function testAddTask(): void
    {
        $this->expectException(Exception::class);

        $taskA = $this->createTask();

        $collection = new TaskCollection();
        $collection
            ->addTask($taskA)
            ->addTask($taskA)
        ;
    }

    /**
     * @throws Throwable
     */
    public function testFilterByStatus(): void
    {
        $taskA = $this->createTask();
        $taskA->setStatus(TaskStatus::PROCESSING());

        $taskB = $this->createTask();
        $taskC = $this->createTask();

        $collection = new TaskCollection();
        $collection
            ->addTask($taskA)
            ->addTask($taskB)
            ->addTask($taskC)
        ;

        /** @var Collection $collectionPending */
        $collectionPending = $collection->filterByStatus(TaskStatus::PENDING());
        $this->assertEquals(2, $collectionPending->count());

        /** @var Collection $collectionProcessing */
        $collectionProcessing = $collection->filterByStatus(TaskStatus::PROCESSING());
        $this->assertEquals(1, $collectionProcessing->count());
    }

    /**
     * @throws Throwable
     */
    public function testIsInStatus(): void
    {
        $taskA = $this->createTask();
        $taskA->setStatus(TaskStatus::PROCESSING());

        $taskB = $this->createTask();
        $taskB->setStatus(TaskStatus::PROCESSING());

        $collection = new TaskCollection();
        $collection
            ->addTask($taskA)
            ->addTask($taskB)
        ;

        $this->assertEquals(true, $collection->isInStatus(TaskStatus::PROCESSING()));
        $this->assertEquals(false, $collection->isInStatus(TaskStatus::FAILED()));
    }
}
