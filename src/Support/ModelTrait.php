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
use ReflectionClass;
use ReflectionException;

/**
 * Trait ModelTrait.
 */
trait ModelTrait
{
    /**
     * @throws ReflectionException
     */
    public static function getTableName(): string
    {
        $reflection = new ReflectionClass(static::class);
        $shortName = $reflection->getShortName();

        return Str::snake(Str::pluralStudly($shortName));
    }

    public function getId(): string
    {
        return (string) $this->getAttribute(static::ID);
    }

    public function getCreatedAt(): Carbon
    {
        $createdAt = $this->getAttribute('created_at');
        if ($createdAt instanceof Carbon) {
            return $createdAt;
        }

        return Carbon::createFromFormat(
            ModelInterface::MYSQL_DATETIME,
            $createdAt
        );
    }
}
