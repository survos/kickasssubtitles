<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace Tests\Processor\TestClasses;

use KickAssSubtitles\Processor\AbstractProcessor;
use KickAssSubtitles\Processor\ProcessorInterface;
use KickAssSubtitles\Processor\TaskInterface;

/**
 * Class SnakeProcessor.
 */
class SnakeProcessor extends AbstractProcessor implements ProcessorInterface
{
    /**
     * {@inheritdoc}
     */
    protected function processTask(TaskInterface $task): void
    {
        $options = $task->getOptions();
        echo snake_case($options['text']).\PHP_EOL;
    }
}
