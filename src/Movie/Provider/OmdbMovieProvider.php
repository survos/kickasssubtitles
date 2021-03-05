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
use GuzzleHttp\ClientInterface as HttpClientInterface;
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

/**
 * Class OmdbMovieProvider.
 */
class OmdbMovieProvider extends AbstractMovieProvider
{
    /**
     * @var string
     */
    protected $apiKey;

    /**
     * @var HttpClientInterface
     */
    protected $httpClient;

    public function __construct(
        MovieRepositoryInterface $movieRepository,
        ImageRepositoryInterface $imageRepository,
        HttpClientInterface $httpClient,
        string $apiKey
    ) {
        parent::__construct($movieRepository, $imageRepository);
        $this->httpClient = $httpClient;
        $this->apiKey = $apiKey;
    }

    /**
     * {@inheritdoc}
     */
    protected function processTask(TaskInterface $task): void
    {
        /** @var MovieSearchOptions $options */
        $options = $task->getOptions();
        $imdbId = $options->getImdbId();

        $response = $this->httpClient->request(
            'GET',
            "http://www.omdbapi.com/?i={$imdbId->getValue()}&plot=short&r=json&apikey={$this->apiKey}"
        );
        $responseArray = json_decode(
            (string) $response->getBody(),
            true,
            512,
            \JSON_THROW_ON_ERROR
        );
        if (!empty($responseArray['Error'])) {
            throw new Exception($responseArray['Error']);
        }

        $image = $this->checkImage($responseArray, $imdbId);

        /** @var Arrayable $movie */
        $movie = $this->movieRepository->create(
            $responseArray['Title'],
            $imdbId,
            new MovieYear($responseArray['Year']),
            new MovieType($responseArray['Type']),
            MovieProvider::OMDB()
        );

        $task->addResult($movie);
        if ($image) {
            /* @var Arrayable $image */
            $task->addResult($image);
        }
    }

    /**
     * @throws Throwable
     */
    protected function checkImage(array $data, ImdbId $imdbId): ?ImageInterface
    {
        if (empty($data['Poster'])) {
            return null;
        }

        $url = $data['Poster'];

        return $this->imageRepository->createFromUrlOrPath(
            $url,
            $imdbId,
            ImageType::POSTER(),
            ImageProvider::OMDB()
        );
    }
}
