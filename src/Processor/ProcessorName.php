<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace KickAssSubtitles\Processor;

use KickAssSubtitles\Support\Str;
use ReflectionClass;
use ReflectionException;

/**
 * Class ProcessorName.
 */
class ProcessorName
{
    const NAMESPACE_SEPARATOR = '\\';

    const NAMESPACE_SEPARATOR_SNAKE_CASE = '.';

    /**
     * @var string
     */
    protected $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getNameSnakeCase(): string
    {
        return $this->snakeCase($this->getName());
    }

    /**
     * @throws ReflectionException
     */
    public function getShortName(): string
    {
        $reflection = new ReflectionClass($this->getName());

        return $reflection->getShortName();
    }

    /**
     * @throws ReflectionException
     */
    public function getShortNameSnakeCase(): string
    {
        return $this->snakeCase($this->getShortName());
    }

    protected function snakeCase(string $name): string
    {
        $nameArray = array_map(function ($el) {
            return Str::snake($el);
        }, explode(static::NAMESPACE_SEPARATOR, $name));

        return implode(static::NAMESPACE_SEPARATOR_SNAKE_CASE, $nameArray);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }
}
