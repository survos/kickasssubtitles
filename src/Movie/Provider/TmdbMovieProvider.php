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

use Exception;
use Illuminate\Contracts\Support\Arrayable;
use KickAssSubtitles\Movie\ImageInterface;
use KickAssSubtitles\Movie\ImageProvider;
use KickAssSubtitles\Movie\ImageRepositoryInterface;
use KickAssSubtitles\Movie\ImageType;
use KickAssSubtitles\Movie\ImdbId;
use KickAssSubtitles\Movie\MovieProvider;
use KickAssSubtitles\Movie\MovieRepositoryInterface;
use KickAssSubtitles\Movie\MovieSearchOptions;
use KickAssSubtitles\Movie\MovieType;
use KickAssSubtitles\Movie\MovieYear;
use KickAssSubtitles\Processor\TaskInterface;
use Throwable;
use Tmdb\Client as TmdbClient;

/**
 * Class TmdbSearcher.
 */
class TmdbMovieProvider extends AbstractMovieProvider
{
    /**
     * TMDB client.
     *
     * @var TmdbClient
     */
    protected $client;

    /**
     * @var string
     */
    protected $imageBaseUrl;

    /**
     * @param MovieRepositoryInterface $movieRepository
     * @param ImageRepositoryInterface $imageRepository
     * @param TmdbClient               $client
     */
    public function __construct(
        MovieRepositoryInterface $movieRepository,
        ImageRepositoryInterface $imageRepository,
        TmdbClient $client
    ) {
        parent::__construct($movieRepository, $imageRepository);
        $this->client = $client;
        $configuration = $this->client->getConfigurationApi()->getConfiguration();
        $this->imageBaseUrl = $configuration['images']['base_url'].'w500';
    }

    /**
     * {@inheritdoc}
     */
    protected function processTask(TaskInterface $task): void
    {
        /** @var MovieSearchOptions $options */
        $options = $task->getOptions();
        $imdbId = $options->getImdbId();

        $find = $this->client->getFindApi()->findBy($imdbId->getValue(), [
            'language' => 'en-US',
            'external_source' => 'imdb_id',
        ]);

        // dump($find);

        if (!empty($find['movie_results'][0])) {
            $d = $find['movie_results'][0];

            $image = $this->checkImage($d, $imdbId);

            /** @var Arrayable $movie */
            $movie = $this->movieRepository->create(
                $d['title'],
                $imdbId,
                new MovieYear($d['release_date']),
                MovieType::MOVIE(),
                MovieProvider::TMDB()
            );

            $task->addResult($movie);
            if ($image) {
                /* @var Arrayable $image */
                $task->addResult($image);
            }

            return;
        }

        if (!empty($find['tv_results'][0])) {
            $d = $find['tv_results'][0];

            $image = $this->checkImage($d, $imdbId);

            /** @var Arrayable $movie */
            $movie = $this->movieRepository->create(
                $d['name'],
                $imdbId,
                new MovieYear($d['first_air_date']),
                MovieType::SERIES(),
                MovieProvider::TMDB()
            );

            $task->addResult($movie);
            if ($image) {
                /* @var Arrayable $image */
                $task->addResult($image);
            }

            return;
        }

        throw new Exception(static::ERR_NOT_FOUND);
    }

    /**
     * @param array  $data
     * @param ImdbId $imdbId
     *
     * @return ImageInterface|null
     *
     * @throws Throwable
     */
    protected function checkImage(array $data, ImdbId $imdbId): ?ImageInterface
    {
        if (empty($data['poster_path'])) {
            return null;
        }

        $url = $this->imageBaseUrl.$data['poster_path'];

        return $this->imageRepository->createFromUrlOrPath(
            $url,
            $imdbId,
            ImageType::POSTER(),
            ImageProvider::TMDB()
        );
    }
}
