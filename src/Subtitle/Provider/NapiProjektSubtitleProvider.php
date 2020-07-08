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

use Archive7z\Archive7z;
use Archive7z\Entry;
use Exception;
use GuzzleHttp\ClientInterface;
use KickAssSubtitles\Encoding\Encoding;
use KickAssSubtitles\Encoding\EncodingDetectorInterface;
use KickAssSubtitles\Language\Language;
use KickAssSubtitles\Movie\ImdbId;
use KickAssSubtitles\Processor\TaskInterface;
use KickAssSubtitles\Storage\HasStorageInterface;
use KickAssSubtitles\Subtitle\Provider\Exception\SubtitlesNotFoundException;
use KickAssSubtitles\Subtitle\SubtitleFormat;
use KickAssSubtitles\Subtitle\SubtitleFormatDetectorInterface;
use KickAssSubtitles\Subtitle\SubtitleProvider;
use KickAssSubtitles\Subtitle\SubtitleRepositoryInterface;
use KickAssSubtitles\Subtitle\SubtitleSearchOptions;
use KickAssSubtitles\Support\EnumMapper;
use function Safe\base64_decode;
use SimpleXMLElement;
use Throwable;

/**
 * It looks like all subtitles from this provider are encoded in WINDOWS1250
 * and in MICRODVD format.
 *
 * @see https://github.com/Diaoul/subliminal/issues/536
 */
class NapiProjektSubtitleProvider extends AbstractSubtitleProvider
{
    const ERR_INVALID_ARCHIVE = 'Invalid archive';

    const PL = 'PL';

    const EN = 'ENG';

    /**
     * @var SubtitleFormatDetectorInterface
     */
    protected $subtitleFormatDetector;

    /**
     * @var EncodingDetectorInterface
     */
    protected $encodingDetector;

    /**
     * HTTP client.
     *
     * @var ClientInterface
     */
    protected $httpClient;

    /**
     * API endpoint.
     *
     * @var string
     */
    protected $endpoint = 'http://napiprojekt.pl/api/api-napiprojekt3.php';

    /**
     * @var string
     */
    protected $sevenZipPassword = 'iBlm8NTigvru0Jr0';

    /**
     * @param SubtitleRepositoryInterface     $subtitleRepository
     * @param SubtitleFormatDetectorInterface $subtitleFormatDetector
     * @param EncodingDetectorInterface       $encodingDetector
     * @param ClientInterface                 $httpClient
     */
    public function __construct(
        SubtitleRepositoryInterface $subtitleRepository,
        SubtitleFormatDetectorInterface $subtitleFormatDetector,
        EncodingDetectorInterface $encodingDetector,
        ClientInterface $httpClient
    ) {
        parent::__construct($subtitleRepository);
        $this->subtitleFormatDetector = $subtitleFormatDetector;
        $this->encodingDetector = $encodingDetector;
        $this->httpClient = $httpClient;
    }

    /**
     * @return EnumMapper
     */
    public function getLanguageMapper(): EnumMapper
    {
        return EnumMapper::create([
            [Language::PL => static::PL],
            [Language::EN => static::EN],
        ], Language::class);
    }

    /**
     * @param TaskInterface $task
     *
     * @throws Throwable
     */
    protected function processTask(TaskInterface $task): void
    {
        /** @var SubtitleSearchOptions $options */
        $options = $task->getOptions();

        /** @var HasStorageInterface $taskWithStorage */
        $taskWithStorage = $task;
        $storage = $taskWithStorage->getStorage();

        if (!$options->getFilesize()) {
            throw new Exception(static::ERR_MISSING_FILESIZE);
        }

        $hash = $options->getHash(SubtitleProvider::NAPIPROJEKT());

        $params = [
            'mode' => 31,
            'client' => 'NapiProjektPython',
            'client_ver' => '2.2.0.2399',
            'user_nick' => '',
            'user_password' => '',
            'downloaded_subtitles_id' => $hash,
            'downloaded_subtitles_lang' => $this->getLanguageMapper()->to($options->getLanguage()),
            'downloaded_cover_id' => $hash,
            'advert_type' => 'flashAllowed',
            'video_info_hash' => $hash,
            'nazwa_pliku' => $options->getFilename(),
            'rozmiar_pliku_bajty' => $options->getFilesize(),
            'the' => 'end',
        ];

        $response = $this->httpClient->request('POST', $this->endpoint, [
            'form_params' => $params,
        ]);

        $xml = new SimpleXMLElement((string) $response->getBody());
        $status = (string) $xml->status;

        if ('success' !== $status) {
            throw new SubtitlesNotFoundException();
        }

        if (empty($xml->subtitles->content)) {
            throw new SubtitlesNotFoundException();
        }

        $sevenZipContents = base64_decode((string) $xml->subtitles->content, true);
        $sevenZip = $storage->addFileContents('7z', $sevenZipContents, $hash.'.7z');

        $archive = new Archive7z($sevenZip);
        $archive->setPassword($this->sevenZipPassword);
        if (!$archive->isValid()) {
            throw new Exception(static::ERR_INVALID_ARCHIVE);
        }

        /** @var Entry $entry */
        $entry = $archive->getEntries()[0];
        $contents = $entry->getContent();
        $subtitle7z = $storage->addFileContents('subtitle7z', $contents, $entry->getPath());

        $imdbId = null;
        if (!empty($xml->movie->direct_links->imdb_com)) {
            $imdbId = new ImdbId((string) $xml->movie->direct_links->imdb_com);
        }

        // try to detect format
        $format = SubtitleFormat::MICRODVD(); // default

        try {
            $format = $this->subtitleFormatDetector->detectFile($subtitle7z);
        } catch (Throwable $e) {
        }

        // try to detect encoding
        $encoding = Encoding::WINDOWS_1250(); // default

        try {
            $encoding = $this->encodingDetector->detectFile($subtitle7z);
        } catch (Throwable $e) {
        }

        $subtitle = $this->subtitleRepository->create(
            $contents,
            $format,
            $options->getLanguage(),
            $encoding,
            $imdbId,
            SubtitleProvider::NAPIPROJEKT()
        );

        $task->addResult($subtitle);
    }
}
