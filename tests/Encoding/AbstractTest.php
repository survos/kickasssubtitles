<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace Tests\Encoding;

use Exception;
use PHPUnit\Framework\TestCase;
use function Safe\file_get_contents;

/**
 * Class AbstractTest.
 */
abstract class AbstractTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function loadFilename(string $filename): string
    {
        $contents = file_get_contents(
            __DIR__.
            \DIRECTORY_SEPARATOR.
            'Files'.
            \DIRECTORY_SEPARATOR.
            $filename.
            '.txt'
        );

        return $contents;
    }
}
