<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace KickAssSubtitles\Processor\Exception;

use Exception;
use Throwable;

/**
 * Class TaskIsProcessingException.
 */
class TaskIsProcessingException extends Exception
{
    /**
     * @param string $message
     * @param int    $code
     */
    public function __construct(
        $message = 'Task is processing',
        $code = 0,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
