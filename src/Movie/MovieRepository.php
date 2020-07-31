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

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use KickAssSubtitles\Support\Exception\NotImplementedException;
use KickAssSubtitles\Support\FiltersInterface;
use KickAssSubtitles\Support\ModelInterface;
use KickAssSubtitles\Support\SluggerInterface;

/**
 * Class MovieRepository.
 */
class MovieRepository implements MovieRepositoryInterface
{
    /**
     * @var string
     */
    protected $movieClass;

    /**
     * @var SluggerInterface
     */
    protected $slugger;

    public function __construct(
        string $movieClass,
        SluggerInterface $slugger
    ) {
        $this->movieClass = $movieClass;
        $this->slugger = $slugger;
    }

    /**
     * {@inheritdoc}
     */
    public function create(
        string $title,
        ImdbId $imdbId,
        MovieYear $year,
        MovieType $type,
        MovieProvider $provider,
        ?int $id = null
    ): MovieInterface {
        $movieClass = $this->movieClass;
        $movie = new $movieClass();
        $movie->setAttribute(MovieInterface::TITLE, $title);
        $movie->setAttribute(MovieInterface::SLUG, $this->slugger->slugify($title));
        $movie->setAttribute(MovieInterface::IMDB_ID, $imdbId->getValue());
        $movie->setAttribute(MovieInterface::YEAR_FROM, $year->getFrom());
        $movie->setAttribute(MovieInterface::YEAR_TO, $year->getTo());
        $movie->setAttribute(MovieInterface::TYPE, $type);
        $movie->setAttribute(MovieInterface::PROVIDER, $provider);

        if (null !== $id) {
            $movie->setAttribute(ModelInterface::ID, $id);
        }

        $movie->save();

        return $movie;
    }

    /**
     * {@inheritdoc}
     */
    public function createFromMovie(MovieInterface $movie): MovieInterface
    {
        return $this->create(
            $movie->getTitle(),
            $movie->getImdbId(),
            $movie->getYear(),
            $movie->getType(),
            $movie->getProvider()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function findByImdbIdOrFail(ImdbId $imdbId): MovieInterface
    {
        throw new NotImplementedException();
    }

    /**
     * {@inheritdoc}
     */
    public function findByIdOrFail(int $id): ModelInterface
    {
        throw new NotImplementedException();
    }

    /**
     * {@inheritdoc}
     */
    public function findAll(FiltersInterface $filters): LengthAwarePaginator
    {
        throw new NotImplementedException();
    }

    /**
     * {@inheritdoc}
     */
    public function delete(object $entity): void
    {
        throw new NotImplementedException();
    }
}
