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
 * Trait ObjectCastsTrait.
 */
trait ObjectCastsTrait
{
    /**
     * @param string $key
     * @param mixed  $value
     *
     * @return $this
     */
    public function setAttribute($key, $value)
    {
        $objectCasts = $this->getObjectCasts();
        if (!isset($objectCasts[$key])) {
            return parent::setAttribute($key, $value);
        }

        list($class, $nullable) = $this->normalizeClass($objectCasts[$key]);

        if ($nullable && (null === $value)) {
            return parent::setAttribute($key, $value);
        }

        if ($value instanceof $class) {
            $value = (string) $value;
        } else {
            new $class($value);
        }

        return parent::setAttribute($key, $value);
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);
        $objectCasts = $this->getObjectCasts();
        if (!isset($objectCasts[$key])) {
            return $value;
        }

        [$class, $nullable] = $this->normalizeClass($objectCasts[$key]);

        if ($nullable && (null === $value)) {
            return $value;
        }

        return new $class($value);
    }

    protected function normalizeClass(string $class): array
    {
        if (Str::startsWith($class, '?')) {
            return [Str::after($class, '?'), true];
        }

        return [$class, false];
    }

    protected function getObjectCasts(): array
    {
        return [];
    }
}
