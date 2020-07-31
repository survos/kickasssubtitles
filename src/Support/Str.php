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

use Illuminate\Support\Str as BaseStr;
use Ramsey\Uuid\Uuid;
use function Safe\parse_url;
use Throwable;

/**
 * Class Str.
 */
class Str extends BaseStr
{
    /**
     * @throws Throwable
     */
    public static function uuid(): string
    {
        return Uuid::uuid4()->toString();
    }

    public static function uuidFragment(string $uuid, int $fragment = 0): string
    {
        $fragments = explode('-', $uuid);

        return $fragments[$fragment];
    }

    public static function uuidValid(string $uuid): bool
    {
        return Uuid::isValid($uuid);
    }

    public static function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, \count($units) - 1);

        $bytes /= 1024 ** $pow;

        return round($bytes, $precision).' '.$units[$pow];
    }

    /**
     * @throws Throwable
     */
    public static function parseDomain(string $url): string
    {
        $host = parse_url($url, PHP_URL_HOST);

        return str_ireplace('www.', '', $host);
    }

    public static function wrap(string $text, string $tag): string
    {
        $text = str_replace(' ', '', $text);
        $textArr = explode('_', static::snake($text));
        $return = '';
        foreach ($textArr as $el) {
            $return = $return.$tag.ucfirst($el).str_replace('<', '</', $tag);
        }

        return $return;
    }
}
