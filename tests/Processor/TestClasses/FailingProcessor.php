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

use Exception;
use KickAssSubtitles\Processor\AbstractProcessor;
use KickAssSubtitles\Processor\ProcessorInterface;
use KickAssSubtitles\Processor\TaskInterface;

/**
 * Class FailingProcessor.
 */
class FailingProcessor extends AbstractProcessor implements ProcessorInterface
{
    const ERR_GENERIC = 'Error processing task';

    /**
     * {@inheritdoc}
     */
    protected function processTask(TaskInterface $task): void
    {
        throw new Exception(static::ERR_GENERIC);
    }
}
