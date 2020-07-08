<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace App\Http\Controllers;

use App\Models\MovieFilters;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use KickAssSubtitles\Movie\MovieRepositoryInterface;
use Throwable;

/**
 * Class MoviesController.
 */
class MoviesController extends AbstractController
{
    /**
     * @var MovieRepositoryInterface
     */
    protected $movieRepository;

    /**
     * @param MovieRepositoryInterface $movieRepository
     */
    public function __construct(MovieRepositoryInterface $movieRepository)
    {
        $this->movieRepository = $movieRepository;
    }

    /**
     * @return Response
     */
    public function index(Request $request): Response
    {
        /** @var MovieFilters $filters */
        $filters = MovieFilters::createFromRequest($request);
        $movies = $this->movieRepository->findAll($filters);

        /** @var ResponseFactory $response */
        $response = response();

        return $response->view('controllers.movies.index', [
            'movies' => $movies,
            'filters' => $filters,
        ]);
    }

    /**
     * @return Response
     *
     * @throws Throwable
     */
    public function recentlySearched(Request $request): Response
    {
        /** @var MovieFilters $filters */
        $filters = MovieFilters::createFromRequest($request);
        $filters->setRecentlySearched(true);
        $movies = $this->movieRepository->findAll($filters);

        /** @var ResponseFactory $response */
        $response = response();

        return $response->view('controllers.movies.recently_searched', [
            'movies' => $movies,
            'filters' => $filters,
        ]);
    }

    /**
     * @param int $id
     *
     * @return Response
     */
    public function show(int $id): Response
    {
        $movie = $this->movieRepository->findByIdOrFail($id);

        /** @var ResponseFactory $response */
        $response = response();

        return $response->view('controllers.movies.show', [
            'movie' => $movie,
        ]);
    }
}
