<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace App\Console\Commands;

use App\Console\Command;
use App\Enums\Filesystem;
use Illuminate\Database\DatabaseManager;
use Illuminate\Filesystem\FilesystemManager;
use InvalidArgumentException;
use KickAssSubtitles\Encoding\Encoding;
use KickAssSubtitles\Encoding\EncodingDetectorInterface;
use KickAssSubtitles\Language\Language;
use KickAssSubtitles\Movie\ImageProvider;
use KickAssSubtitles\Movie\ImageRepositoryInterface;
use KickAssSubtitles\Movie\ImageType;
use KickAssSubtitles\Movie\ImdbId;
use KickAssSubtitles\Movie\MovieProvider;
use KickAssSubtitles\Movie\MovieRepositoryInterface;
use KickAssSubtitles\Movie\MovieType;
use KickAssSubtitles\Movie\MovieYear;
use KickAssSubtitles\Movie\VideoRepositoryInterface;
use KickAssSubtitles\Subtitle\SubtitleFormat;
use KickAssSubtitles\Subtitle\SubtitleFormatDetectorInterface;
use KickAssSubtitles\Subtitle\SubtitleProvider;
use KickAssSubtitles\Subtitle\SubtitleRepositoryInterface;
use function Safe\gzuncompress;
use function Safe\json_decode;

/**
 * Class Import.
 */
class Import extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports data from legacy KickAssSubtitles database';

    /**
     * @var DatabaseManager
     */
    protected $db;

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
     * @var EncodingDetectorInterface
     */
    protected $encodingDetector;

    /**
     * @var SubtitleFormatDetectorInterface
     */
    protected $subtitleFormatDetector;

    /**
     * @var FilesystemManager
     */
    protected $storage;

    /**
     * @param DatabaseManager                 $db
     * @param MovieRepositoryInterface        $movieRepository
     * @param ImageRepositoryInterface        $imageRepository
     * @param VideoRepositoryInterface        $videoRepository
     * @param SubtitleRepositoryInterface     $subtitleRepository
     * @param EncodingDetectorInterface       $encodingDetector
     * @param SubtitleFormatDetectorInterface $subtitleFormatDetector
     * @param FilesystemManager               $storage
     */
    public function __construct(
        DatabaseManager $db,
        MovieRepositoryInterface $movieRepository,
        ImageRepositoryInterface $imageRepository,
        VideoRepositoryInterface $videoRepository,
        SubtitleRepositoryInterface $subtitleRepository,
        EncodingDetectorInterface $encodingDetector,
        SubtitleFormatDetectorInterface $subtitleFormatDetector,
        FilesystemManager $storage
    ) {
        parent::__construct();
        $this->db = $db->connection('mysql_legacy');
        $this->movieRepository = $movieRepository;
        $this->imageRepository = $imageRepository;
        $this->videoRepository = $videoRepository;
        $this->subtitleRepository = $subtitleRepository;
        $this->encodingDetector = $encodingDetector;
        $this->subtitleFormatDetector = $subtitleFormatDetector;
        $this->storage = $storage;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        parent::handle();

        $this->importMovies();
        $this->importSubtitles();
    }

    protected function importSubtitles(): void
    {
        $this->db
            ->table('videos')
            ->orderBy('id')
            ->chunk(1, function ($videos) {
                foreach ($videos as $video) {
                    $this->info('Importing video: '.$video->id);

                    $subtitles = $this->db
                        ->table('subtitles')
                        ->select()
                        ->where('video_id', $video->id)
                        ->get()
                    ;

                    if ($subtitles->isEmpty()) {
                        $this->info('No subtitles found');

                        continue;
                    }

                    $addedVideoId = null;
                    foreach ($subtitles as $subtitle) {
                        $this->info('Importing subtitle: '.$subtitle->id);
                        $imdbId = null;
                        if (!empty($video->imdb_id)) {
                            $imdbId = $video->imdb_id;
                        } elseif (!empty($subtitle->imdb_id)) {
                            $imdbId = $subtitle->imdb_id;
                        }
                        if (!$imdbId) {
                            $this->info('Undefined $imdbId');

                            continue;
                        }
                        $imdbId = new ImdbId('tt'.$imdbId);

                        $folder = uniqid();
                        $filename = $folder.DIRECTORY_SEPARATOR.'subtitle.srt';
                        $this->storage->disk(Filesystem::TMP)->put(
                            $filename,
                            gzuncompress($this->decodeBase64($subtitle->contents__data__encoded))
                        );
                        $path = $this->storage->disk(Filesystem::TMP)->path($filename);

                        $detectedFormat = $this->subtitleFormatDetector->detectFile($path);
                        $detectedEncoding = $this->encodingDetector->detectFile($path);
                        if (!$detectedFormat->equals(SubtitleFormat::SUBRIP())) {
                            $this->info('Invalid format');

                            continue;
                        }
                        if (!$detectedEncoding->equals(Encoding::UTF_8())) {
                            $this->info('Invalid encoding');

                            continue;
                        }

                        if (null === $addedVideoId) {
                            $addedVideo = $this->videoRepository->create(
                                [
                                    SubtitleProvider::OPENSUBTITLES => $video->hashes__opensubtitles,
                                    SubtitleProvider::NAPIPROJEKT => $video->hashes__napiprojekt,
                                ],
                                json_decode($video->filenames),
                                (int) $video->filesize,
                                $imdbId
                            );
                            $addedVideo->setUpdateHashes(true);
                            $addedVideoId = (int) $addedVideo->getId();
                        } else {
                            $addedVideo = $this->videoRepository->findByIdOrFail($addedVideoId);
                        }

                        $subtitleProvider = new SubtitleProvider($subtitle->provider);
                        $addedSubtitle = $this->subtitleRepository->createFromFile(
                            $path,
                            $detectedFormat,
                            new Language($subtitle->language),
                            $detectedEncoding,
                            $imdbId,
                            $subtitleProvider
                        );
                        $addedSubtitle->setProvider(SubtitleProvider::KICKASSSUBTITLES());
                        $addedVideo->addSubtitle($addedSubtitle);
                        $this->storage->disk(Filesystem::TMP)->deleteDirectory($folder);
                    }
                }
            })
        ;
    }

    protected function importMovies(): void
    {
        $this->db
            ->table('movies')
            ->orderBy('id')
            ->chunk(1, function ($movies) {
                foreach ($movies as $movie) {
                    $this->info('Importing movie: '.$movie->imdb_id);
                    $provider = ('imdb' === $movie->provider) ? 'omdb' : $movie->provider;
                    $imdbId = new ImdbId('tt'.$movie->imdb_id);
                    $createdMovie = $this->movieRepository->create(
                        $movie->title,
                        $imdbId,
                        new MovieYear($movie->year__from.'-'.$movie->year__to),
                        new MovieType($movie->type),
                        new MovieProvider($provider),
                        $movie->id
                    );
                    $createdMovie->setProvider(MovieProvider::KICKASSSUBTITLES());

                    if ($movie->poster__data_url) {
                        $filename = 'tt'.$movie->imdb_id.'.jpg';
                        $this->storage->disk(Filesystem::TMP)->put(
                            $filename,
                            $this->decodeBase64($movie->poster__data_url)
                        );
                        $path = $this->storage->disk(Filesystem::TMP)->path($filename);
                        $createdImage = $this->imageRepository->createFromUrlOrPath(
                            $path,
                            $imdbId,
                            ImageType::POSTER(),
                            new ImageProvider($provider)
                        );
                        $createdImage->setProvider(ImageProvider::KICKASSSUBTITLES());
                        $this->storage->disk(Filesystem::TMP)->delete($filename);
                    }
                }
            })
        ;
    }

    /**
     * @param string $base64data
     *
     * @return string
     */
    protected function decodeBase64(string $base64data): string
    {
        // strip out data uri scheme information (see RFC 2397)
        if (false !== \strpos($base64data, ';base64')) {
            [$_, $base64data] = \explode(';', $base64data);
            [$_, $base64data] = \explode(',', $base64data);
        }

        // strict mode filters for non-base64 alphabet characters
        if (false === \base64_decode($base64data, true)) {
            throw new InvalidArgumentException();
        }

        // decoding and then reencoding should not change the data
        if (\base64_encode(\base64_decode($base64data, true)) !== $base64data) {
            throw new InvalidArgumentException();
        }

        $binaryData = \base64_decode($base64data, true);

        return $binaryData;
    }
}
