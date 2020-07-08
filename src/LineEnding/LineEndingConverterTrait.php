<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace KickAssSubtitles\LineEnding;

use function Safe\file_get_contents;
use function Safe\file_put_contents;
use function Safe\unlink;
use Throwable;

/**
 * Trait LineEndingConverterTrait.
 */
trait LineEndingConverterTrait
{
    /**
     * @param string     $input
     * @param LineEnding $from
     * @param LineEnding $to
     *
     * @return string
     *
     * @throws Throwable
     */
    public function convert(string $input, LineEnding $from, LineEnding $to): string
    {
        if ($to->equals($from)) {
            return $input;
        }

        $file = \sys_get_temp_dir().'/'.\uniqid();
        file_put_contents($file, $input);
        $this->convertFile($file, $from, $to);
        $result = file_get_contents($file);
        unlink($file);

        return $result;
    }
}
