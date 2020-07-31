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

use App\Models\Collections\SubtitleCollection;
use KickAssSubtitles\Subtitle\SubtitleInterface;
use KickAssSubtitles\Support\Exception\NotImplementedException;
use KickAssSubtitles\Support\ObjectCastsTrait;
use KickAssSubtitles\Support\TablelessModel;

/**
 * Class Video.
 */
class Video extends TablelessModel implements VideoInterface
{
    use ObjectCastsTrait;
    use VideoHashesTrait;
    use VideoTrait;

    /**
     * @var array
     */
    protected $casts = [
        self::FILENAMES => 'array',
        self::UPDATE_HASHES => 'boolean',
    ];

    /**
     * @var array
     */
    protected $attributes = [
        self::FILENAMES => '[]',
    ];

    protected function getObjectCasts(): array
    {
        return [
            static::IMDB_ID => ImdbId::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function addSubtitle(SubtitleInterface $subtitle): void
    {
        throw new NotImplementedException();
    }

    /**
     * {@inheritdoc}
     */
    public function getSubtitles(): SubtitleCollection
    {
        throw new NotImplementedException();
    }
}
