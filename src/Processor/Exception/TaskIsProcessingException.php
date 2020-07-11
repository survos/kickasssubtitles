<?php
declare(strict_types=1);

namespace KickAssSubtitles\Processor\Exception;

use Exception;
use Throwable;

/**
 * Class TaskIsProcessingException
 * @package KickAssSubtitles\Processor\Exception
 */
class TaskIsProcessingException extends Exception
{
    /**
     * @param string         $message
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct(
        $message = 'Task is processing',
        $code = 0,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
