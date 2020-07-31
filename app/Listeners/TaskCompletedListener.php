<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace App\Listeners;

use App\Services\SubtitleConverter;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use KickAssSubtitles\Encoding\Encoding;
use KickAssSubtitles\Movie\Exception\InvalidVideoFilenameException;
use KickAssSubtitles\Movie\ImageInterface;
use KickAssSubtitles\Movie\ImageProvider;
use KickAssSubtitles\Movie\ImageRepositoryInterface;
use KickAssSubtitles\Movie\MovieInterface;
use KickAssSubtitles\Movie\MovieProvider;
use KickAssSubtitles\Movie\MovieRepositoryInterface;
use KickAssSubtitles\Movie\VideoInterface;
use KickAssSubtitles\Movie\VideoRepositoryInterface;
use KickAssSubtitles\Processor\Event\TaskCompleted;
use KickAssSubtitles\Processor\TaskInterface;
use KickAssSubtitles\Processor\TaskRepositoryInterface;
use KickAssSubtitles\Processor\TaskStatus;
use KickAssSubtitles\Processor\TaskType;
use KickAssSubtitles\Subtitle\SubtitleCollection;
use KickAssSubtitles\Subtitle\SubtitleFormat;
use KickAssSubtitles\Subtitle\SubtitleInterface;
use KickAssSubtitles\Subtitle\SubtitleProvider;
use KickAssSubtitles\Subtitle\SubtitleRepositoryInterface;
use KickAssSubtitles\Subtitle\SubtitleSearchOptions;
use Throwable;

/**
 * Class TaskCompletedListener.
 */
class TaskCompletedListener extends AbstractListener implements ShouldQueue
{
    /**
     * @var TaskRepositoryInterface
     */
    protected $taskRepository;

    /**
     * @var MovieRepositoryInterface
     */
    protected $movieRepository;

    /**
     * @var ImageRepositoryInterface
     */
    protected $imageRepository;

    /**
     * @var VideoRepositoryInterface
     */
    protected $videoRepository;

    /**
     * @var SubtitleRepositoryInterface
     */
    protected $subtitleRepository;

    /**
     * @var SubtitleConverter
     */
    protected $subtitleConverter;

    public function __construct(
        TaskRepositoryInterface $taskRepository,
        MovieRepositoryInterface $movieRepository,
        ImageRepositoryInterface $imageRepository,
        VideoRepositoryInterface $videoRepository,
        SubtitleRepositoryInterface $subtitleRepository,
        SubtitleConverter $subtitleConverter
    ) {
        $this->taskRepository = $taskRepository;
        $this->movieRepository = $movieRepository;
        $this->imageRepository = $imageRepository;
        $this->videoRepository = $videoRepository;
        $this->subtitleRepository = $subtitleRepository;
        $this->subtitleConverter = $subtitleConverter;
    }

    /**
     * @throws Throwable
     */
    public function handle(TaskCompleted $event): void
    {
        $task = $this->taskRepository->findByIdentifierOrFail($event->getIdentifier());
        if (!$task->getType()->equals(TaskType::SUBTITLE_SEARCH())) {
            return;
        }

        /** @var SubtitleSearchOptions $options */
        $options = $task->getOptions();

        $movie = null;
        foreach ($task->getResults() as $result) {
            if ($result instanceof MovieInterface) {
                $movie = $result;

                break;
            }
        }
        if ($movie) {
            $this->addMovie($movie);
        }

        $image = null;
        foreach ($task->getResults() as $result) {
            if ($result instanceof ImageInterface) {
                $image = $result;

                break;
            }
        }
        if ($image) {
            $this->addImage($image);
        }

        if (!$movie) {
            return;
        }

        $video = $this->addVideo($movie, $options);

        $subtitles = new SubtitleCollection();
        foreach ($task->getChildren()->filterByStatus(TaskStatus::COMPLETED()) as $childTask) {
            /** @var TaskInterface $childTask */
            $results = $childTask->getResults();
            foreach ($results as $result) {
                if ($result instanceof SubtitleInterface) {
                    $subtitles->push($result);
                }
            }
        }

        $this->addSubtitles($subtitles, $video, $options);
    }

    /**
     * @throws Throwable
     */
    protected function addMovie(MovieInterface $movie): void
    {
        if ($movie->getProvider()->equals(MovieProvider::KICKASSSUBTITLES())) {
            return;
        }

        try {
            $this->movieRepository->findByImdbIdOrFail($movie->getImdbId());
        } catch (ModelNotFoundException $e) {
            $savedMovie = $this->movieRepository->createFromMovie($movie);
            $savedMovie->setProvider(MovieProvider::KICKASSSUBTITLES());
            $savedMovie->touchSearchedAt();
        }
    }

    /**
     * @throws Throwable
     */
    protected function addImage(ImageInterface $image): void
    {
        if ($image->getProvider()->equals(ImageProvider::KICKASSSUBTITLES())) {
            return;
        }

        try {
            $this->imageRepository->findByImdbIdOrFail($image->getImdbId());
        } catch (ModelNotFoundException $e) {
            $savedImage = $this->imageRepository->createFromImage($image);
            $savedImage->setProvider(ImageProvider::KICKASSSUBTITLES());
        }
    }

    /**
     * @throws Throwable
     */
    protected function addVideo(MovieInterface $movie, SubtitleSearchOptions $options): VideoInterface
    {
        try {
            $video = $this->videoRepository->findByProviderHashOrFail(
                $options->getHash(SubtitleProvider::NAPIPROJEKT()),
                SubtitleProvider::NAPIPROJEKT()
            );

            try {
                $video->addFilename($options->getFilename());
            } catch (InvalidVideoFilenameException $e) {
            }
        } catch (ModelNotFoundException $e) {
            $video = $this->videoRepository->create(
                $options->getHashes(),
                [
                    $options->getFilename(),
                ],
                $options->getFilesize(),
                $movie->getImdbId()
            );
        }

        return $video;
    }

    /**
     * @throws Throwable
     */
    protected function addSubtitles(
        SubtitleCollection $subtitles,
        VideoInterface $video,
        SubtitleSearchOptions $options
    ): void {
        if ($subtitles->containsProvider(SubtitleProvider::KICKASSSUBTITLES())) {
            return;
        }

        $subtitles = $subtitles->sortByFormat(SubtitleFormat::SUBRIP());

        foreach ($subtitles as $subtitle) {
            if ($subtitle->getFormat()->equals(SubtitleFormat::SUBRIP()) &&
                $subtitle->getEncoding()->equals(Encoding::UTF_8())
            ) {
                $addedSubtitle = $this->subtitleRepository->createFromSubtitle($subtitle);
                $addedSubtitle->setProvider(SubtitleProvider::KICKASSSUBTITLES());
                $video->addSubtitle($addedSubtitle);

                return;
            }
        }

        // convert
        foreach ($subtitles as $subtitle) {
            $convertedSubtitles = $this->subtitleConverter->convert(
                $subtitle,
                SubtitleFormat::SUBRIP(),
                Encoding::UTF_8()
            );
            if (empty($convertedSubtitles)) {
                continue;
            }

            $addedSubtitle = $this->subtitleRepository->createFromSubtitle($convertedSubtitles[0]);
            $addedSubtitle->setProvider(SubtitleProvider::KICKASSSUBTITLES());
            $video->addSubtitle($addedSubtitle);
            foreach ($convertedSubtitles as $convertedSubtitle) {
                $convertedSubtitle->tearDownStorage();
            }

            return;
        }
    }
}
