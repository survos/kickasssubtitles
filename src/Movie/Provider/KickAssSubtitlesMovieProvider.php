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

use Illuminate\Contracts\Support\Arrayable;
use KickAssSubtitles\Movie\ImageRepository;
use KickAssSubtitles\Movie\ImageRepositoryInterface;
use KickAssSubtitles\Movie\MovieRepository;
use KickAssSubtitles\Movie\MovieRepositoryInterface;
use KickAssSubtitles\Movie\MovieSearchOptions;
use KickAssSubtitles\Processor\TaskInterface;

/**
 * Class KickAssSubtitlesMovieProvider.
 */
class KickAssSubtitlesMovieProvider extends AbstractMovieProvider
{
    /**
     * @var MovieRepository
     */
    protected $tablelessMovieRepository;

    /**
     * @var ImageRepository
     */
    protected $tablelessImageRepository;

    /**
     * @param MovieRepositoryInterface $movieRepository
     * @param ImageRepositoryInterface $imageRepository
     * @param MovieRepository          $tablelessMovieRepository
     * @param ImageRepository          $tablelessImageRepository
     */
    public function __construct(
        MovieRepositoryInterface $movieRepository,
        ImageRepositoryInterface $imageRepository,
        MovieRepository $tablelessMovieRepository,
        ImageRepository $tablelessImageRepository
    ) {
        parent::__construct($movieRepository, $imageRepository);
        $this->tablelessMovieRepository = $tablelessMovieRepository;
        $this->tablelessImageRepository = $tablelessImageRepository;
    }

    /**
     * {@inheritdoc}
     */
    protected function processTask(TaskInterface $task): void
    {
        /** @var MovieSearchOptions $options */
        $options = $task->getOptions();
        $imdbId = $options->getImdbId();

        $movie = $this->movieRepository->findByImdbIdOrFail($imdbId);
        $movie->touchSearchedAt();
        $image = $movie->getPoster();

        /** @var Arrayable $movieCopy */
        $movieCopy = $this->tablelessMovieRepository->createFromMovie($movie);
        $task->addResult($movieCopy);
        if ($image) {
            /** @var Arrayable $imageCopy */
            $imageCopy = $this->tablelessImageRepository->createFromImage($image);
            $task->addResult($imageCopy);
        }
    }
}
