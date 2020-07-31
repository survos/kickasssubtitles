<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace KickAssSubtitles\Subtitle;

use KickAssSubtitles\Encoding\Encoding;
use KickAssSubtitles\Language\Language;
use KickAssSubtitles\Movie\ImdbId;
use KickAssSubtitles\Support\RepositoryInterface;
use Throwable;

/**
 * Interface SubtitleRepositoryInterface.
 */
interface SubtitleRepositoryInterface extends RepositoryInterface
{
    const DEFAULT_FILENAME_WITHOUT_EXTENSION = 'subtitle';

    /**
     * @throws Throwable
     */
    public function create(
        string $contents,
        SubtitleFormat $format,
        ?Language $language = null,
        ?Encoding $encoding = null,
        ?ImdbId $imdbId = null,
        ?SubtitleProvider $provider = null,
        string $filenameWithoutExtension = self::DEFAULT_FILENAME_WITHOUT_EXTENSION
    ): SubtitleInterface;

    /**
     * @throws Throwable
     */
    public function createFromFile(
        string $file,
        ?SubtitleFormat $format = null,
        ?Language $language = null,
        ?Encoding $encoding = null,
        ?ImdbId $imdbId = null,
        ?SubtitleProvider $provider = null,
        string $filenameWithoutExtension = self::DEFAULT_FILENAME_WITHOUT_EXTENSION
    ): SubtitleInterface;

    /**
     * @throws Throwable
     */
    public function createFromSubtitle(
        SubtitleInterface $subtitle,
        string $filenameWithoutExtension = self::DEFAULT_FILENAME_WITHOUT_EXTENSION
    ): SubtitleInterface;

    /**
     * @throws Throwable
     */
    public function findByProviderHashAndLanguage(
        string $hash,
        SubtitleProvider $provider,
        Language $language
    ): SubtitleCollectionInterface;
}
