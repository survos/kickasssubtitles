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

use KickAssSubtitles\Processor\TaskStatus;
use PHPUnit\Framework\TestCase;

/**
 * Class TaskStatusTest.
 */
class TaskStatusTest extends TestCase
{
    public function testIsValidStatusChange(): void
    {
        $this->assertEquals(
            true,
            TaskStatus::PENDING()->isValidStatusChange(TaskStatus::PROCESSING())
        );
        $this->assertEquals(
            false,
            TaskStatus::PENDING()->isValidStatusChange(TaskStatus::FAILED())
        );
        $this->assertEquals(
            false,
            TaskStatus::PENDING()->isValidStatusChange(TaskStatus::PENDING())
        );
        $this->assertEquals(
            true,
            TaskStatus::PROCESSING()->isValidStatusChange(TaskStatus::COMPLETED())
        );
    }
}
