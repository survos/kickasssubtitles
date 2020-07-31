<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace Tests\LineEnding;

use PHPUnit\Framework\TestCase;
use function Safe\file_get_contents;
use function Safe\file_put_contents;
use Throwable;

/**
 * Class AbstractTest.
 */
abstract class AbstractTest extends TestCase
{
    /**
     * @throws Throwable
     */
    public function loadFilename(string $filename): string
    {
        $file = sys_get_temp_dir().\DIRECTORY_SEPARATOR.uniqid().'.txt';
        $contents = file_get_contents(
            __DIR__.
            \DIRECTORY_SEPARATOR.
            'Files'.
            \DIRECTORY_SEPARATOR.
            $filename.
            '.txt'
        );
        file_put_contents($file, $contents);

        return $file;
    }
}
