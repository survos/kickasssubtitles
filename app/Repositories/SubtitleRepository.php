<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace App\Repositories;

use KickAssSubtitles\Language\Language;
use KickAssSubtitles\Subtitle\SubtitleCollectionInterface;
use KickAssSubtitles\Subtitle\SubtitleProvider;
use KickAssSubtitles\Subtitle\SubtitleRepository as BaseSubtitleRepository;
use KickAssSubtitles\Support\ModelInterface;

/**
 * Class SubtitleRepository.
 */
class SubtitleRepository extends BaseSubtitleRepository
{
    /**
     * {@inheritdoc}
     */
    public function findByIdOrFail(int $id): ModelInterface
    {
        $subtitleClass = $this->subtitleClass;

        return $subtitleClass::findOrFail($id);
    }

    /**
     * {@inheritdoc}
     */
    public function findByProviderHashAndLanguage(
        string $hash,
        SubtitleProvider $provider,
        Language $language
    ): SubtitleCollectionInterface {
        $subtitleClass = $this->subtitleClass;

        return $subtitleClass::where($subtitleClass::LANGUAGE, $language->getValue())
        ->whereHas($subtitleClass::VIDEO, function ($q) use ($hash, $provider) {
            $q->where($provider->getHashStorageField(), $hash);
        })->get();
    }
}
