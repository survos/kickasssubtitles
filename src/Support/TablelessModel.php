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
use Jenssegers\Model\Model;
use Throwable;

/**
 * Class TablelessModel.
 */
class TablelessModel extends Model implements ModelInterface
{
    use ModelTrait;

    /**
     * @param array $options
     *
     * @throws Throwable
     */
    public function save(array $options = [])
    {
        if (null !== $this->getAttribute(static::ID)) {
            return;
        }

        $this->setAttribute(static::ID, Str::uuid());

        $createdAt = Carbon::now()->format(static::MYSQL_DATETIME);
        $this->setAttribute('created_at', $createdAt);
    }

    public function delete()
    {
        // noop
    }
}
