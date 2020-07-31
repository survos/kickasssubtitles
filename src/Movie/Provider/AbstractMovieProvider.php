<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace KickAssSubtitles\Movie\Provider;

use KickAssSubtitles\Movie\ImageRepositoryInterface;
use KickAssSubtitles\Movie\MovieRepositoryInterface;
use KickAssSubtitles\Processor\AbstractProcessor;
use KickAssSubtitles\Processor\TaskType;

/**
 * Class AbstractSearcher.
 */
abstract class AbstractMovieProvider extends AbstractProcessor
{
    const ERR_NOT_FOUND = 'Movie not found';

    /**
     * @var MovieRepositoryInterface
     */
    protected $movieRepository;

    /**
     * @var ImageRepositoryInterface
     */
    protected $imageRepository;

    public function __construct(
        MovieRepositoryInterface $movieRepository,
        ImageRepositoryInterface $imageRepository
    ) {
        parent::__construct();
        $this->movieRepository = $movieRepository;
        $this->imageRepository = $imageRepository;
    }

    public function getMovieRepository(): MovieRepositoryInterface
    {
        return $this->movieRepository;
    }

    public function getImageRepository(): ImageRepositoryInterface
    {
        return $this->imageRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function getSupportedTaskType(): TaskType
    {
        return TaskType::MOVIE_SEARCH();
    }
}
