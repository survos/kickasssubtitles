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

use KickAssSubtitles\Processor\BulkProcessor;
use KickAssSubtitles\Processor\Exception\AllChildTasksFailedException;
use KickAssSubtitles\Processor\ProcessorInterface;
use KickAssSubtitles\Processor\Task;
use KickAssSubtitles\Processor\TaskCollection;
use KickAssSubtitles\Processor\TaskRepository;
use KickAssSubtitles\Processor\TaskStatus;
use KickAssSubtitles\Processor\TaskType;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Tests\Processor\TestClasses\FailingProcessor;
use Tests\Processor\TestClasses\LowerProcessor;
use Tests\Processor\TestClasses\SnakeProcessor;
use Tests\Processor\TestClasses\UpperProcessor;
use Throwable;

/**
 * Class BulkProcessorTest.
 */
class BulkProcessorTest extends TestCase
{
    use CreatesTaskTrait;

    /**
     * @var ProcessorInterface
     */
    protected $processor;

    public function setUp()
    {
        parent::setUp();
        $taskRepository = new TaskRepository(Task::class);
        $this->processor = new BulkProcessor([
            new LowerProcessor(),
            new UpperProcessor(),
            new SnakeProcessor(),
        ], $taskRepository);
    }

    /**
     * @throws Throwable
     */
    public function testProcessOne(): void
    {
        $task = $this->createTask();

        ob_start();
        $this->processor->processOne($task);
        $contents = ob_get_clean();

        $this->assertEquals('lorem ipsum'.PHP_EOL.'LOREM IPSUM'.PHP_EOL.'lorem_ipsum'.PHP_EOL, $contents);
        $this->assertEquals(TaskStatus::COMPLETED, $task->getStatus()->getValue());
    }

    /**
     * @throws Throwable
     */
    public function testProcessOneStopAfterFirstCompletedTask(): void
    {
        $task = $this->createTask();

        $this->processor->setStopAfterFirstCompletedTask(true);

        ob_start();
        $this->processor->processOne($task);
        $contents = ob_get_clean();

        $this->assertEquals('lorem ipsum'.PHP_EOL, $contents);
        $this->assertEquals(TaskStatus::COMPLETED, $task->getStatus()->getValue());
    }

    /**
     * @throws Throwable
     */
    public function testProcessMany(): void
    {
        $collection = new TaskCollection();
        $collection->addTask($this->createTask());
        $collection->addTask($this->createTask());

        ob_start();
        $this->processor->processMany($collection);
        $contents = ob_get_clean();

        $expected = 'lorem ipsum'.PHP_EOL.'LOREM IPSUM'.PHP_EOL.'lorem_ipsum'.PHP_EOL.'lorem ipsum'.PHP_EOL.'LOREM IPSUM'.PHP_EOL.'lorem_ipsum'.PHP_EOL;
        $this->assertEquals($expected, $contents);
        $this->assertEquals(true, $collection->isInStatus(TaskStatus::COMPLETED()));
    }

    /**
     * @throws Throwable
     */
    public function testFailingProcessor(): void
    {
        $task = $this->createTask();

        $this->processor = new BulkProcessor([
            new FailingProcessor(),
        ], new TaskRepository(Task::class));

        $this->processor->processOne($task);
        $this->assertEquals(TaskStatus::FAILED, $task->getStatus()->getValue());
        $error = $task->getError();
        if (!$error) {
            throw new RuntimeException();
        }
        $this->assertEquals(AllChildTasksFailedException::class, $error->getClassName());
    }

    /**
     * @throws Throwable
     */
    public function testInvalidTaskType(): void
    {
        $task = $this->createTask(TaskType::TEST());

        ob_start();
        $this->processor->processOne($task);
        $contents = ob_get_clean();

        $this->assertEquals(TaskStatus::FAILED, $task->getStatus()->getValue());
        $error = $task->getError();
        if (!$error) {
            throw new RuntimeException();
        }
        $this->assertEquals(ProcessorInterface::ERR_INVALID_TASK_TYPE, $error->getMessage());
    }
}
