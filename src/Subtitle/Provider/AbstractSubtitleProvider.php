<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace KickAssSubtitles\Subtitle\Provider;

use KickAssSubtitles\Processor\AbstractProcessor;
use KickAssSubtitles\Processor\TaskType;
use KickAssSubtitles\Subtitle\SubtitleRepositoryInterface;

/**
 * Class AbstractSubtitleProvider.
 */
abstract class AbstractSubtitleProvider extends AbstractProcessor
{
    const ERR_MISSING_FILESIZE = 'Filesize is required';

    /**
     * @var SubtitleRepositoryInterface
     */
    protected $subtitleRepository;

    /**
     * @param SubtitleRepositoryInterface $subtitleRepository
     */
    public function __construct(SubtitleRepositoryInterface $subtitleRepository)
    {
        parent::__construct();
        $this->subtitleRepository = $subtitleRepository;
    }

    /**
     * @return SubtitleRepositoryInterface
     */
    public function getSubtitleRepository(): SubtitleRepositoryInterface
    {
        return $this->subtitleRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function getSupportedTaskType(): TaskType
    {
        return TaskType::SUBTITLE_SEARCH();
    }
}
