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

use MyCLabs\Enum\Enum;

/**
 * @method static TaskStatus PENDING()
 * @method static TaskStatus PROCESSING()
 * @method static TaskStatus COMPLETED()
 * @method static TaskStatus FAILED()
 */
class TaskStatus extends Enum
{
    /**
     * Awaiting processing.
     */
    const PENDING = 'pending';

    /**
     * Processing.
     */
    const PROCESSING = 'processing';

    /**
     * Processed successfully.
     */
    const COMPLETED = 'completed';

    /**
     * Processed with errors.
     */
    const FAILED = 'failed';

    /**
     * @return TaskStatus|null
     */
    public function getPreviousStatus(): ?self
    {
        $map = [
            static::PENDING => null,
            static::PROCESSING => static::PENDING,
            static::COMPLETED => static::PROCESSING,
            static::FAILED => static::PROCESSING,
        ];
        $previous = $map[$this->getValue()];
        if ($previous) {
            return new static($previous);
        }

        return $previous;
    }

    /**
     * @param TaskStatus $status
     *
     * @return bool
     */
    public function isValidStatusChange(self $status): bool
    {
        if ($status->equals($this)) {
            return false;
        }

        if (null !== $status->getPreviousStatus() &&
            $status->getPreviousStatus()->equals($this)
        ) {
            return true;
        }

        return false;
    }
}
