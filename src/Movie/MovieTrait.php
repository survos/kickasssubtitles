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
use KickAssSubtitles\Support\SluggerInterface;

/**
 * Trait MovieTrait.
 */
trait MovieTrait
{
    /**
     * {@inheritdoc}
     */
    public function getTitle(): string
    {
        return $this->getAttribute(static::TITLE);
    }

    /**
     * {@inheritdoc}
     */
    public function getSlug(): string
    {
        return $this->getAttribute(static::SLUG);
    }

    /**
     * {@inheritdoc}
     */
    public function getFolder(): string
    {
        $slugArray = explode(SluggerInterface::SEPARATOR, $this->getSlug());
        $folder = array_map(function ($el) {
            return ucfirst($el);
        }, $slugArray);

        return sprintf('%s (%s)', implode(' ', $folder), $this->getYear()->getFrom());
    }

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
    public function getYear(): MovieYear
    {
        $year = sprintf(
            '%s-%s',
            $this->getAttribute(static::YEAR_FROM),
            $this->getAttribute(static::YEAR_TO)
        );

        return new MovieYear($year);
    }

    /**
     * {@inheritdoc}
     */
    public function getType(): MovieType
    {
        return $this->getAttribute(static::TYPE);
    }

    /**
     * {@inheritdoc}
     */
    public function getProvider(): MovieProvider
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
    public function getProviderPrevious(): ?MovieProvider
    {
        return $this->getAttribute(static::PROVIDER_PREVIOUS);
    }

    /**
     * {@inheritdoc}
     */
    public function getPoster(): ?ImageInterface
    {
        throw new NotImplementedException();
    }
}
