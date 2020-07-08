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
use KickAssSubtitles\Support\Exception\NotImplementedException;
use function Safe\file_get_contents;

/**
 * Trait SubtitleTrait.
 */
trait SubtitleTrait
{
    /**
     * @var string
     */
    public $contents;

    /**
     * {@inheritdoc}
     */
    public function getFormat(): SubtitleFormat
    {
        return $this->getAttribute(static::FORMAT);
    }

    /**
     * {@inheritdoc}
     */
    public function getLanguage(): Language
    {
        return $this->getAttribute(static::LANGUAGE);
    }

    /**
     * {@inheritdoc}
     */
    public function getEncoding(): Encoding
    {
        return $this->getAttribute(static::ENCODING);
    }

    /**
     * {@inheritdoc}
     */
    public function getImdbId(): ?ImdbId
    {
        return $this->getAttribute(static::IMDB_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function getProvider(): ?SubtitleProvider
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
    public function getProviderPrevious(): ?SubtitleProvider
    {
        return $this->getAttribute(static::PROVIDER_PREVIOUS);
    }

    /**
     * {@inheritdoc}
     */
    public function getFile(?int $element = null): string
    {
        return $this->getStorage()->getFile(static::STORAGE_SUBTITLE, $element);
    }

    /**
     * {@inheritdoc}
     */
    public function getContents(): string
    {
        if (null === $this->contents) {
            $file = $this->getFile();
            $contents = file_get_contents($file);
            $this->contents = $contents;
        }

        return $this->contents;
    }

    /**
     * {@inheritdoc}
     */
    public function getHash(): string
    {
        return $this->getAttribute(static::HASH);
    }

    /**
     * {@inheritdoc}
     */
    public function getVideo(): ?VideoInterface
    {
        throw new NotImplementedException();
    }
}
