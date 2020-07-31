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

    public function getFormat(): SubtitleFormat;

    public function getLanguage(): Language;

    public function getEncoding(): Encoding;

    public function getImdbId(): ?ImdbId;

    public function getProvider(): ?SubtitleProvider;

    public function setProvider(SubtitleProvider $provider): void;

    public function getProviderPrevious(): ?SubtitleProvider;

    /**
     * @throws Throwable
     */
    public function getFile(?int $element = null): string;

    /**
     * @throws Throwable
     */
    public function getContents(): string;

    public function getHash(): string;

    public function getVideo(): ?VideoInterface;
}
