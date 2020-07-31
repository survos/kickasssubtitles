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

    public static function createFromThrowable(Throwable $e): self;

    /**
     * @return static
     */
    public static function create(string $class, ?string $message, array $trace = []): self;

    public function getMessage(): string;

    public function getClassName(): string;

    public function getTrace(): array;
}
