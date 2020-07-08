<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace App\Models;

use App\Models\Collections\SubtitleCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use KickAssSubtitles\Movie\VideoHashesTrait;
use KickAssSubtitles\Movie\VideoInterface;
use KickAssSubtitles\Movie\VideoTrait;
use KickAssSubtitles\Subtitle\SubtitleInterface;
use KickAssSubtitles\Support\ModelInterface;
use KickAssSubtitles\Support\ModelTrait;

/**
 * Class Video.
 */
class Video extends Model implements ModelInterface, VideoInterface
{
    use ModelTrait;
    use VideoTrait;
    use VideoHashesTrait;

    const SUBTITLES = 'subtitles';

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

    /**
     * {@inheritdoc}
     */
    public function addSubtitle(SubtitleInterface $subtitle): void
    {
        $this->subtitles()->save($subtitle);
        $this->load(static::SUBTITLES);
    }

    /**
     * {@inheritdoc}
     */
    public function getSubtitles(): SubtitleCollection
    {
        return $this->getAttribute(static::SUBTITLES);
    }

    /**
     * {@inheritdoc}
     */
    public function subtitles(): HasMany
    {
        return $this->hasMany(Subtitle::class);
    }
}
