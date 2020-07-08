<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace KickAssSubtitles\Support;

use Illuminate\Support\Carbon;
use Throwable;

/**
 * Interface ModelInterface.
 */
interface ModelInterface
{
    const MYSQL_DATETIME = 'Y-m-d H:i:s';

    const ID = 'id';

    /**
     * @return string
     *
     * @throws Throwable
     */
    public static function getTableName(): string;

    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @return Carbon
     */
    public function getCreatedAt(): Carbon;
}
