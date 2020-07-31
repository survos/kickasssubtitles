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

use function Safe\file_put_contents;
use function Safe\unlink;

/**
 * Trait LineEndingDetectorTrait.
 */
trait LineEndingDetectorTrait
{
    /**
     * {@inheritdoc}
     */
    public function detect(string $input): LineEnding
    {
        $file = sys_get_temp_dir().'/'.uniqid();
        file_put_contents($file, $input);
        $type = $this->detectFile($file);
        unlink($file);

        return $type;
    }
}
