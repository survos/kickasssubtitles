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

use Illuminate\Http\Request;

/**
 * Interface FiltersInterface.
 */
interface FiltersInterface
{
    public static function createFromRequest(Request $request): self;

    public function setLimit(int $limit): self;

    public function getLimit(): int;
}
