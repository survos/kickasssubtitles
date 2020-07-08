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

use KickAssSubtitles\Processor\TaskCollection;
use KickAssSubtitles\Processor\TaskErrorInterface;
use KickAssSubtitles\Processor\TaskStatus;
use KickAssSubtitles\Processor\TaskType;
use PHPUnit\Framework\TestCase;
use Tests\Processor\TestClasses\FailingProcessor;
use Tests\Processor\TestClasses\LowerProcessor;
use Throwable;

/**
 * Class AbstractProcessorTest.
 */
class AbstractProcessorTest extends TestCase
{
    use CreatesTaskTrait;

    /**
     * @throws Throwable
     */
    public function testProcessOne(): void
    {
        $task = $this->createTask();
        $processor = new LowerProcessor();

        \ob_start();
        $processor->processOne($task);
        $contents = \ob_get_clean();

        $this->assertEquals("lorem ipsum\n", $contents);
        $this->assertEquals(TaskStatus::COMPLETED, $task->getStatus()->getValue());
        $this->assertNotNull($task->getProcessorName());
    }

    /**
     * @throws Throwable
     */
    public function testProcessMany(): void
    {
        $collection = new TaskCollection();
        $collection->addTask($this->createTask());
        $collection->addTask($this->createTask());

        $processor = new LowerProcessor();

        \ob_start();
        $processor->processMany($collection);
        $contents = \ob_get_clean();

        $this->assertEquals("lorem ipsum\nlorem ipsum\n", $contents);
    }

    /**
     * @throws Throwable
     */
    public function testFailingProcessor(): void
    {
        $task = $this->createTask();
        $processor = new FailingProcessor();

        \ob_start();
        $processor->processOne($task);
        $contents = \ob_get_clean();

        $this->assertEquals(TaskStatus::FAILED, $task->getStatus()->getValue());
        $this->assertInstanceOf(TaskErrorInterface::class, $task->getError());
    }

    /**
     * @throws Throwable
     */
    public function testInvalidTaskType(): void
    {
        $task = $this->createTask(TaskType::TEST());
        $processor = new LowerProcessor();

        \ob_start();
        $processor->processOne($task);
        $contents = \ob_get_clean();

        $this->assertEquals(TaskStatus::FAILED, $task->getStatus()->getValue());
        $this->assertInstanceOf(TaskErrorInterface::class, $task->getError());
        $error = $task->getError();
        if ($error) {
            $this->assertEquals(LowerProcessor::ERR_INVALID_TASK_TYPE, $error->getMessage());
        }
    }
}
