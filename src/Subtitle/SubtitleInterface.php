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
use KickAssSubtitles\Movie\VideoInterface;
use Throwable;

/**
 * Interface SubtitleInterface.
 */
interface SubtitleInterface
{
    const STORAGE_SUBTITLE = 'subtitle';

    const FORMAT = 'format';

    const LANGUAGE = 'language';

    const ENCODING = 'encoding';

    const HASH = 'hash';

    const PROVIDER = 'provider';

    const PROVIDER_PREVIOUS = 'provider_previous';

    const IMDB_ID = 'imdb_id';

    const CONTENTS = 'contents';

    /**
     * @return SubtitleFormat
     */
    public function getFormat(): SubtitleFormat;

    /**
     * @return Language
     */
    public function getLanguage(): Language;

    /**
     * @return Encoding
     */
    public function getEncoding(): Encoding;

    /**
     * @return ImdbId|null
     */
    public function getImdbId(): ?ImdbId;

    /**
     * @return SubtitleProvider|null
     */
    public function getProvider(): ?SubtitleProvider;

    /**
     * @param SubtitleProvider $provider
     */
    public function setProvider(SubtitleProvider $provider): void;

    /**
     * @return SubtitleProvider|null
     */
    public function getProviderPrevious(): ?SubtitleProvider;

    /**
     * @param int|null $element
     *
     * @return string
     *
     * @throws Throwable
     */
    public function getFile(?int $element = null): string;

    /**
     * @return string
     *
     * @throws Throwable
     */
    public function getContents(): string;

    /**
     * @return string
     */
    public function getHash(): string;

    /**
     * @return VideoInterface|null
     */
    public function getVideo(): ?VideoInterface;
}
