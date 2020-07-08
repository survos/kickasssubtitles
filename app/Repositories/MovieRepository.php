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

use App\Models\Movie;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use KickAssSubtitles\Movie\ImdbId;
use KickAssSubtitles\Movie\MovieInterface;
use KickAssSubtitles\Movie\MovieRepository as BaseMovieRepository;
use KickAssSubtitles\Support\FiltersInterface;
use KickAssSubtitles\Support\ModelInterface;

/**
 * Class MovieRepository.
 */
class MovieRepository extends BaseMovieRepository
{
    /**
     * {@inheritdoc}
     */
    public function findAll(FiltersInterface $filters): LengthAwarePaginator
    {
        $movieClass = $this->movieClass;
        $query = (new $movieClass())->newQuery();

        if ($filters->getRecentlySearched()) {
            $query->orderBy(Movie::SEARCHED_AT, 'desc');
        }

        $query->orderBy($movieClass::SLUG, 'asc');

        if ($filters->getLetter()) {
            if ('number' === $filters->getLetter()) {
                $query->where($movieClass::SLUG, 'REGEXP', '^[0-9]');
            } else {
                $query->where($movieClass::SLUG, 'LIKE', $filters->getLetter().'%');
            }
        }

        return $query->paginate($filters->getLimit());
    }

    /**
     * {@inheritdoc}
     */
    public function findByIdOrFail(int $id): ModelInterface
    {
        $movieClass = $this->movieClass;

        return $movieClass::findOrFail($id);
    }

    /**
     * {@inheritdoc}
     */
    public function findByImdbIdOrFail(ImdbId $imdbId): MovieInterface
    {
        $movieClass = $this->movieClass;
        $movie = $movieClass::where(MovieInterface::IMDB_ID, $imdbId->getValue())->firstOrFail();

        return $movie;
    }
}
