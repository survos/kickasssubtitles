<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace KickAssSubtitles\Encoding;

use function Safe\file_get_contents;
use function Safe\file_put_contents;
use Throwable;

/**
 * Trait EncodingConverterTrait.
 */
trait EncodingConverterTrait
{
    /**
     * @param string   $file
     * @param Encoding $from
     * @param Encoding $to
     *
     * @throws Throwable
     */
    public function convertFile(string $file, Encoding $from, Encoding $to): void
    {
        if ($to->equals($from)) {
            return;
        }

        $input = file_get_contents($file);
        $output = $this->convert($input, $from, $to);
        file_put_contents($file, $output);
    }
}
