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

use Exception;
use InvalidArgumentException;
use KickAssSubtitles\Subtitle\SubtitleProvider;
use function Safe\sprintf;

/**
 * Trait VideoHashesTrait.
 */
trait VideoHashesTrait
{
    /**
     * @param string|SubtitleProvider $provider
     * @param string                  $hash
     *
     * @throws Exception
     */
    public function setHash($provider, string $hash): void
    {
        if (!$provider instanceof SubtitleProvider) {
            $provider = new SubtitleProvider($provider);
        }

        $attribute = $provider->getHashStorageField();

        $this->setAttribute($attribute, $hash);

        $this->save();
    }

    /**
     * @return array
     */
    public function getHashes(): array
    {
        $hashes = [];
        foreach (SubtitleProvider::values() as $provider) {
            $attribute = $provider->getHashStorageField();
            if (null !== $this->getAttribute($attribute)) {
                $hashes[$provider->getValue()] = $this->getAttribute($attribute);
            }
        }

        return $hashes;
    }

    /**
     * @param array $hashes
     *
     * @throws Exception
     */
    public function setHashes(array $hashes): void
    {
        foreach ($hashes as $provider => $hash) {
            $this->setHash($provider, $hash);
        }
    }

    /**
     * @param string|SubtitleProvider $provider
     *
     * @return string
     *
     * @throws Exception
     */
    public function getHash($provider): string
    {
        if (!$provider instanceof SubtitleProvider) {
            $provider = new SubtitleProvider($provider);
        }

        $attribute = $provider->getHashStorageField();

        $value = $this->getAttribute($attribute);

        if (null === $value) {
            throw new InvalidArgumentException(sprintf('Attribute not defined: %s', $attribute));
        }

        return $value;
    }
}
