<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace Tests\Support;

use Jenssegers\Model\Model as BaseModel;
use KickAssSubtitles\Support\ObjectCastsTrait;

/**
 * Class Model.
 */
class Model extends BaseModel
{
    use ObjectCastsTrait;

    const OPTIONS = 'options';

    const FORMAT = 'format';

    const FORMAT_NULLABLE = 'format_nullable';

    protected $casts = [
        self::OPTIONS => 'array',
    ];

    protected function getObjectCasts(): array
    {
        return [
            self::FORMAT => Format::class,
            self::FORMAT_NULLABLE => '?'.Format::class,
        ];
    }
}
