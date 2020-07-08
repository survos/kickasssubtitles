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

use KickAssSubtitles\Support\Exception\NotImplementedException;

/**
 * Trait ImageTrait.
 */
trait ImageTrait
{
    /**
     * {@inheritdoc}
     */
    public function getImdbId(): ImdbId
    {
        return new ImdbId($this->getAttribute(static::IMDB_ID));
    }

    /**
     * {@inheritdoc}
     */
    public function getWidth(): int
    {
        return $this->getAttribute(static::WIDTH);
    }

    /**
     * {@inheritdoc}
     */
    public function getHeight(): int
    {
        return $this->getAttribute(static::HEIGHT);
    }

    /**
     * {@inheritdoc}
     */
    public function getType(): ImageType
    {
        return $this->getAttribute(static::TYPE);
    }

    /**
     * {@inheritdoc}
     */
    public function getFile(?int $element = null): string
    {
        return $this->getStorage()->getFile(static::STORAGE_IMAGE, $element);
    }

    /**
     * {@inheritdoc}
     */
    public function getUrl(): string
    {
        throw new NotImplementedException();
    }

    /**
     * {@inheritdoc}
     */
    public function getProvider(): ImageProvider
    {
        return $this->getAttribute(static::PROVIDER);
    }

    /**
     * {@inheritdoc}
     */
    public function setProvider($provider): void
    {
        $currentProvider = $this->getProvider();
        if (null !== $currentProvider) {
            $this->setAttribute(static::PROVIDER_PREVIOUS, $currentProvider);
        }

        $this->setAttribute(static::PROVIDER, $provider);
        $this->save();
    }

    /**
     * {@inheritdoc}
     */
    public function getProviderPrevious(): ?ImageProvider
    {
        return $this->getAttribute(static::PROVIDER_PREVIOUS);
    }
}
