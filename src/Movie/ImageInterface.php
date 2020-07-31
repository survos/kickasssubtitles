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

/**
 * Interface ImageInterface.
 */
interface ImageInterface
{
    const STORAGE_IMAGE = 'image';

    const IMDB_ID = 'imdb_id';

    const TYPE = 'type';

    const WIDTH = 'width';

    const HEIGHT = 'height';

    const PROVIDER = 'provider';

    const PROVIDER_PREVIOUS = 'provider_previous';

    public function getImdbId(): ImdbId;

    public function getType(): ImageType;

    public function getFile(?int $element = null): string;

    public function getUrl(): string;

    public function getWidth(): int;

    public function getHeight(): int;

    public function getProvider(): ImageProvider;

    public function setProvider(ImageProvider $provider): void;

    public function getProviderPrevious(): ?ImageProvider;
}
