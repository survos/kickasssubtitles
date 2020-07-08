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

use Throwable;

/**
 * Interface TaskErrorInterface.
 */
interface TaskErrorInterface
{
    const ERR_GENERIC = 'Something went wrong';

    const MESSAGE = 'message';

    const CLASS_NAME = 'class_name';

    const TRACE = 'trace';

    /**
     * @param Throwable $e
     *
     * @return self
     */
    public static function createFromThrowable(Throwable $e): self;

    /**
     * @param string      $class
     * @param string|null $message
     * @param array       $trace
     *
     * @return static
     */
    public static function create(string $class, ?string $message, array $trace = []): self;

    /**
     * @return string
     */
    public function getMessage(): string;

    /**
     * @return string
     */
    public function getClassName(): string;

    /**
     * @return array
     */
    public function getTrace(): array;
}
