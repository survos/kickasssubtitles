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

use KickAssSubtitles\Support\Exception\EnumMapperException;
use MyCLabs\Enum\Enum;

/**
 * Class EnumMapper.
 */
class EnumMapper
{
    const ERR_INVALID_ENUM_KEY = 'Invalid enum key';

    const ERR_MAPPING_NOT_FOUND = 'Mapping not found';

    /**
     * @var array
     */
    protected $map = [];

    /**
     * @var string
     */
    protected $enumClass;

    /**
     * @param array  $map
     * @param string $enumClass
     *
     * @return EnumMapper
     */
    public static function create(array $map, string $enumClass): self
    {
        return new static($map, $enumClass);
    }

    /**
     * @param array  $map
     * @param string $enumClass
     */
    protected function __construct(array $map, string $enumClass)
    {
        foreach ($map as $mapping) {
            $key = \array_key_first($mapping);
            /** @var Enum $enumClass */
            if (!$enumClass::isValid($key)) {
                throw new EnumMapperException(static::ERR_INVALID_ENUM_KEY);
            }
        }

        $this->map = $map;
        $this->enumClass = $enumClass;
    }

    /**
     * @param Enum $enum
     *
     * @return string
     *
     * @throws EnumMapperException
     */
    public function to(Enum $enum): string
    {
        foreach ($this->map as $mapping) {
            if (isset($mapping[$enum->getValue()])) {
                return $mapping[$enum->getValue()];
            }
        }

        throw new EnumMapperException(static::ERR_MAPPING_NOT_FOUND);
    }

    /**
     * @param mixed $value
     *
     * @return Enum
     *
     * @throws EnumMapperException
     */
    public function from($value): Enum
    {
        $enumClass = $this->enumClass;

        foreach ($this->map as $mapping) {
            $key = \array_key_first($mapping);
            $mappingValue = $mapping[$key];

            if ($value === $mappingValue) {
                return new $enumClass($key);
            }
        }

        throw new EnumMapperException(static::ERR_MAPPING_NOT_FOUND);
    }
}
