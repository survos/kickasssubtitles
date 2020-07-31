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

use Illuminate\Contracts\Support\Arrayable;
use KickAssSubtitles\Movie\VideoInterface;
use KickAssSubtitles\Processor\TaskInterface;
use KickAssSubtitles\Subtitle\Provider\Exception\SubtitlesNotFoundException;
use KickAssSubtitles\Subtitle\SubtitleProvider;
use KickAssSubtitles\Subtitle\SubtitleRepository;
use KickAssSubtitles\Subtitle\SubtitleRepositoryInterface;
use KickAssSubtitles\Subtitle\SubtitleSearchOptions;

/**
 * Class KickAssSubtitlesSubtitleProvider.
 */
class KickAssSubtitlesSubtitleProvider extends AbstractSubtitleProvider
{
    /**
     * @var SubtitleRepository
     */
    protected $tablelessSubtitleRepository;

    public function __construct(
        SubtitleRepositoryInterface $subtitleRepository,
        SubtitleRepository $tablelessSubtitleRepository
    ) {
        parent::__construct($subtitleRepository);
        $this->tablelessSubtitleRepository = $tablelessSubtitleRepository;
    }

    /**
     * {@inheritdoc}
     */
    protected function processTask(TaskInterface $task): void
    {
        /** @var SubtitleSearchOptions $options */
        $options = $task->getOptions();

        $providers = [
            SubtitleProvider::NAPIPROJEKT(),
            SubtitleProvider::OPENSUBTITLES(),
        ];

        foreach ($providers as $provider) {
            $hash = $options->getHash($provider);

            $subtitles = $this->subtitleRepository->findByProviderHashAndLanguage(
                $hash,
                $provider,
                $options->getLanguage()
            );

            if (!$subtitles->isEmpty()) {
                foreach ($subtitles as $subtitle) {
                    /** @var VideoInterface $video */
                    $video = $subtitle->getVideo();
                    if (null !== $video && $video->getUpdateHashes()) {
                        $video->setHashes($options->getHashes());
                        $video->setUpdateHashes(false);
                    }
                    /** @var Arrayable $subtitleCopy */
                    $subtitleCopy = $this->tablelessSubtitleRepository->createFromSubtitle($subtitle);
                    $task->addResult($subtitleCopy);
                }

                return;
            }
        }

        throw new SubtitlesNotFoundException();
    }
}
