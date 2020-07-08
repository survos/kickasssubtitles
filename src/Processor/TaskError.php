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

use KickAssSubtitles\Support\HydratableInterface;
use KickAssSubtitles\Support\HydratableTrait;
use KickAssSubtitles\Support\TablelessModel;
use Throwable;

/**
 * Class TaskError.
 */
class TaskError extends TablelessModel implements TaskErrorInterface, HydratableInterface
{
    use HydratableTrait;

    /**
     * @var array
     */
    protected $casts = [
        self::TRACE => 'array',
    ];

    /**
     * {@inheritdoc}
     */
    public static function createFromThrowable(
        Throwable $e
    ): TaskErrorInterface {
        return static::create(
            \get_class($e),
            $e->getMessage(),
            $e->getTrace()
        );
    }

    /**
     * {@inheritdoc}
     */
    public static function create(
        string $className,
        ?string $message,
        array $trace = []
    ): TaskErrorInterface {
        $error = new static();
        $error->setAttribute(static::CLASS_NAME, $className);
        $error->setAttribute(static::MESSAGE, (empty($message) ? self::ERR_GENERIC : $message));
        $error->setAttribute(static::TRACE, $trace);

        $error->save();

        return $error;
    }

    /**
     * {@inheritdoc}
     */
    public function getMessage(): string
    {
        return $this->getAttribute(static::MESSAGE);
    }

    /**
     * {@inheritdoc}
     */
    public function getClassName(): string
    {
        return $this->getAttribute(static::CLASS_NAME);
    }

    /**
     * {@inheritdoc}
     */
    public function getTrace(): array
    {
        return $this->getAttribute(static::TRACE);
    }
}
