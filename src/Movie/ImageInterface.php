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

    /**
     * @return ImdbId
     */
    public function getImdbId(): ImdbId;

    /**
     * @return ImageType
     */
    public function getType(): ImageType;

    /**
     * @param int|null $element
     *
     * @return string
     */
    public function getFile(?int $element = null): string;

    /**
     * @return string
     */
    public function getUrl(): string;

    /**
     * @return int
     */
    public function getWidth(): int;

    /**
     * @return int
     */
    public function getHeight(): int;

    /**
     * @return ImageProvider
     */
    public function getProvider(): ImageProvider;

    /**
     * @param ImageProvider $provider
     */
    public function setProvider(ImageProvider $provider): void;

    /**
     * @return null|ImageProvider
     */
    public function getProviderPrevious(): ?ImageProvider;
}
