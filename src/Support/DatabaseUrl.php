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

use InvalidArgumentException;
use function Safe\parse_url;
use Throwable;

/**
 * Class DatabaseUrl.
 */
class DatabaseUrl
{
    const ERR_MISSING_URL_PARTS = 'Missing URL parts';

    /**
     * @var string
     */
    protected $host = '';

    /**
     * @var string
     */
    protected $port = '';

    /**
     * @var string
     */
    protected $database = '';

    /**
     * @var string
     */
    protected $username = '';

    /**
     * @var string
     */
    protected $password = '';

    /**
     * @param string $url
     *
     * @throws Throwable
     */
    public function __construct(string $url)
    {
        $parts = parse_url($url);

        if (!isset($parts['user']) ||
            !isset($parts['pass']) ||
            !isset($parts['host']) ||
            !isset($parts['port'])
        ) {
            throw new InvalidArgumentException(static::ERR_MISSING_URL_PARTS);
        }

        $this->host = $parts['host'];
        $this->port = $parts['port'];
        $this->username = $parts['user'];
        $this->password = $parts['pass'];

        if (isset($parts['path'])) {
            $this->database = \trim($parts['path'], '/');
        }
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @return string
     */
    public function getPort(): string
    {
        return (string) $this->port;
    }

    /**
     * @return string
     */
    public function getDatabase(): string
    {
        return $this->database;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }
}
