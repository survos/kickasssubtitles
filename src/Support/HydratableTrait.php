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

/**
 * Trait HydratableTrait.
 */
trait HydratableTrait
{
    /**
     * {@inheritdoc}
     */
    public static function hydrateFromArray(array $array = []): object
    {
        if (isset($array[HydratableInterface::CLASS_ATTRIBUTE])) {
            unset($array[HydratableInterface::CLASS_ATTRIBUTE]);
        }

        $instance = new static();

        return $instance->newInstance($array, true);
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        $array = parent::toArray();
        $array[HydratableInterface::CLASS_ATTRIBUTE] = static::class;

        return $array;
    }
}
