<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace KickAssSubtitles\Movie;

use InvalidArgumentException;
use function Safe\parse_url;
use function Safe\sprintf;
use function Safe\substr;
use Throwable;

/**
 * Class ImdbId.
 */
class ImdbId
{
    const PREFIX_TT = 'tt';

    const PREFIX_NM = 'nm';

    /**
     * @var string
     */
    protected $prefix;

    /**
     * @var int
     */
    protected $numericId;

    /**
     * @param string $url
     *
     * @return self
     *
     * @throws Throwable
     */
    public static function createFromUrl(string $url): self
    {
        $path = parse_url($url, PHP_URL_PATH);

        $path = \trim($path, '/');
        $pathArr = \explode('/', $path);

        foreach ($pathArr as $k => $el) {
            if (\in_array($el, ['title', 'name'], true)) {
                return new static($pathArr[$k + 1]);
            }
        }

        throw new InvalidArgumentException($url);
    }

    /**
     * @param string|int $value
     *
     * @throws Throwable
     */
    public function __construct($value)
    {
        $this->prefix = self::PREFIX_TT;

        if (empty($value)) {
            throw new InvalidArgumentException();
        }

        if (\is_int($value)) {
            $this->numericId = $value;

            return;
        }

        if (!\is_string($value)) {
            throw new InvalidArgumentException();
        }

        if (\filter_var($value, FILTER_VALIDATE_URL)) {
            $imdbId = static::createFromUrl($value);
            $this->numericId = $imdbId->getNumericId();
            $this->prefix = $imdbId->getPrefix();

            return;
        }

        $intval = \intval($value);
        if (0 !== $intval) {
            $this->numericId = $intval;

            return;
        }

        $prefix = substr($value, 0, 2);

        if (!\defined(static::class.'::PREFIX_'.\strtoupper($prefix))) {
            throw new InvalidArgumentException();
        }

        $this->prefix = $prefix;
        $value = \ltrim($value, $prefix);
        $this->numericId = \intval($value);

        if (0 === $this->numericId) {
            throw new InvalidArgumentException();
        }
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->prefix.\str_pad((string) $this->numericId, 7, '0', STR_PAD_LEFT);
    }

    /**
     * @return string
     */
    public function getPrefix(): string
    {
        return $this->prefix;
    }

    /**
     * @return int
     */
    public function getNumericId(): int
    {
        return $this->numericId;
    }

    /**
     * @return string
     *
     * @throws Throwable
     */
    public function getUrl(): string
    {
        return sprintf('https://www.imdb.com/title/%s/', $this->getValue());
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getValue();
    }
}
