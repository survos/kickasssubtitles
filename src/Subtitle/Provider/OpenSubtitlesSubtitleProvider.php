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

use Exception;
use Illuminate\Contracts\Support\Arrayable;
use KickAssSubtitles\Encoding\Encoding;
use KickAssSubtitles\Movie\ImdbId;
use KickAssSubtitles\OpenSubtitles\OpenSubtitlesClient;
use KickAssSubtitles\Processor\TaskInterface;
use KickAssSubtitles\Storage\HasStorageInterface;
use KickAssSubtitles\Subtitle\Provider\Exception\SubtitlesNotFoundException;
use KickAssSubtitles\Subtitle\SubtitleFormat;
use KickAssSubtitles\Subtitle\SubtitleProvider;
use KickAssSubtitles\Subtitle\SubtitleRepositoryInterface;
use KickAssSubtitles\Subtitle\SubtitleSearchOptions;
use KickAssSubtitles\Support\EnumMapper;
use function Safe\base64_decode;
use function Safe\gzinflate;
use function Safe\substr;

/**
 * Class OpenSubtitlesSubtitleProvider.
 */
class OpenSubtitlesSubtitleProvider extends AbstractSubtitleProvider
{
    const SUB = 'sub';

    const SRT = 'srt';

    const TXT = 'txt';

    const SSA = 'ssa';

    const SMI = 'smi';

    const MPL = 'mpl';

    const TMP = 'tmp';

    const VTT = 'vtt';

    /**
     * @return EnumMapper
     */
    public function createFormatMapper(): EnumMapper
    {
        return EnumMapper::create([
            [SubtitleFormat::MICRODVD => static::SUB],
            [SubtitleFormat::SUBRIP => static::SRT],
            [SubtitleFormat::TMPLAYER => static::TXT],
            [SubtitleFormat::SUBSTATIONALPHA => static::SSA],
            [SubtitleFormat::SAMI => static::SMI],
            [SubtitleFormat::MPLAYER2 => static::MPL],
            [SubtitleFormat::TMPLAYER => static::TMP],
            [SubtitleFormat::WEBVTT => static::VTT],
        ], SubtitleFormat::class);
    }

    /**
     * @var OpenSubtitlesClient
     */
    protected $client;

    /**
     * @param SubtitleRepositoryInterface $subtitleRepository
     * @param OpenSubtitlesClient         $client
     */
    public function __construct(
        SubtitleRepositoryInterface $subtitleRepository,
        OpenSubtitlesClient $client
    ) {
        parent::__construct($subtitleRepository);
        $this->client = $client;
    }

    /**
     * {@inheritdoc}
     */
    protected function processTask(TaskInterface $task): void
    {
        /** @var SubtitleSearchOptions $options */
        $options = $task->getOptions();
        /** @var HasStorageInterface $taskWithStorage */
        $taskWithStorage = $task;
        $storage = $taskWithStorage->getStorage();

        if (0 === $options->getFilesize()) {
            throw new Exception(static::ERR_MISSING_FILESIZE);
        }

        $hash = $options->getHash(SubtitleProvider::OPENSUBTITLES());

        $response = $this->client->searchSubtitles([
            [
                'sublanguageid' => $options->getLanguage()->getIso6392(),
                'moviehash' => $hash,
                'moviebytesize' => $options->getFilesize(),
            ],
        ])->toArray();

        if (empty($response['data'])) {
            throw new SubtitlesNotFoundException();
        }

        $found = \count($response['data']);
        $failed = 0;

        foreach ($response['data'] as $item) {
            $response = $this->client->downloadSubtitles([
                $item['IDSubtitleFile'],
            ])->toArray();

            if (empty($response['data'])) {
                ++$failed;
            }

            /** @var SubtitleFormat $format */
            $format = $this->createFormatMapper()->from($item['SubFormat']);
            $encoding = new Encoding($item['SubEncoding']);
            $decoded = base64_decode($response['data'][0]['data'], true);

            $contents = gzinflate(substr($decoded, 10));
            $imdbId = null;

            if (!empty($item['IDMovieImdb'])) {
                $imdbId = new ImdbId($item['IDMovieImdb']);
            }

            /** @var Arrayable $subtitle */
            $subtitle = $this->subtitleRepository->create(
                $contents,
                $format,
                $options->getLanguage(),
                $encoding,
                $imdbId,
                SubtitleProvider::OPENSUBTITLES()
            );

            $task->addResult($subtitle);
        }

        if ($failed === $found) {
            throw new SubtitlesNotFoundException();
        }
    }
}
