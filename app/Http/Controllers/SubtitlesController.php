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

use Illuminate\Http\JsonResponse;
use KickAssSubtitles\Subtitle\SubtitleInterface;
use KickAssSubtitles\Subtitle\SubtitleRepositoryInterface;
use Throwable;

/**
 * Class SubtitlesController.
 */
class SubtitlesController extends AbstractController
{
    /**
     * @var SubtitleRepositoryInterface
     */
    protected $subtitleRepository;

    public function __construct(SubtitleRepositoryInterface $subtitleRepository)
    {
        $this->subtitleRepository = $subtitleRepository;
    }

    /**
     * @throws Throwable
     */
    public function show(int $id): JsonResponse
    {
        $subtitle = $this->subtitleRepository->findByIdOrFail($id);

        $subtitleArray = $subtitle->toArray();
        $subtitleArray[SubtitleInterface::CONTENTS] = $subtitle->getContents();

        return response()->json($subtitleArray);
    }
}
