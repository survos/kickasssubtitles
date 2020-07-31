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

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Throwable;

/**
 * Interface RepositoryInterface.
 */
interface RepositoryInterface
{
    /**
     * @throws Throwable
     */
    public function findByIdOrFail(int $id): ModelInterface;

    /**
     * @throws Throwable
     */
    public function findAll(FiltersInterface $filters): LengthAwarePaginator;

    /**
     * @throws Throwable
     */
    public function delete(object $entity): void;
}
