<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace KickAssSubtitles\Subtitle;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use KickAssSubtitles\Encoding\Encoding;
use KickAssSubtitles\Encoding\EncodingDetectorInterface;
use KickAssSubtitles\Language\Language;
use KickAssSubtitles\Language\LanguageDetectorInterface;
use KickAssSubtitles\Movie\ImdbId;
use KickAssSubtitles\Support\Exception\NotImplementedException;
use KickAssSubtitles\Support\FiltersInterface;
use KickAssSubtitles\Support\ModelInterface;
use function Safe\file_get_contents;
use function Safe\realpath;

/**
 * Class SubtitleRepository.
 */
class SubtitleRepository implements SubtitleRepositoryInterface
{
    /**
     * @var string
     */
    protected $subtitleClass;

    /**
     * @var LanguageDetectorInterface
     */
    protected $languageDetector;

    /**
     * @var EncodingDetectorInterface
     */
    protected $encodingDetector;

    /**
     * @var SubtitleFormatDetectorInterface
     */
    protected $subtitleFormatDetector;

    public function __construct(
        string $subtitleClass,
        LanguageDetectorInterface $languageDetector,
        EncodingDetectorInterface $encodingDetector,
        SubtitleFormatDetectorInterface $subtitleFormatDetector
    ) {
        $this->subtitleClass = $subtitleClass;
        $this->languageDetector = $languageDetector;
        $this->encodingDetector = $encodingDetector;
        $this->subtitleFormatDetector = $subtitleFormatDetector;
    }

    /**
     * {@inheritdoc}
     */
    public function create(
        string $contents,
        SubtitleFormat $format,
        ?Language $language = null,
        ?Encoding $encoding = null,
        ?ImdbId $imdbId = null,
        ?SubtitleProvider $provider = null,
        string $filenameWithoutExtension = self::DEFAULT_FILENAME_WITHOUT_EXTENSION
    ): SubtitleInterface {
        // detect language from contents
        if (!$language) {
            $language = $this->languageDetector->detect($contents);
        }

        // detect encoding
        if (!$encoding) {
            $encoding = $this->encodingDetector->detect($contents, $language);
        }

        $subtitleClass = $this->subtitleClass;
        $subtitle = new $subtitleClass();
        // $subtitle->contents = $contents;
        $subtitle->setAttribute(SubtitleInterface::HASH, md5($contents));
        $subtitle->setAttribute(SubtitleInterface::ENCODING, $encoding);
        $subtitle->setAttribute(SubtitleInterface::LANGUAGE, $language);
        $subtitle->setAttribute(SubtitleInterface::FORMAT, $format);
        $subtitle->setAttribute(SubtitleInterface::IMDB_ID, $imdbId);
        $subtitle->setAttribute(SubtitleInterface::PROVIDER, $provider);
        $subtitle->save();

        $subtitle->getStorage()->addFileContents(
            SubtitleInterface::STORAGE_SUBTITLE,
            $contents,
            sprintf('%s.%s', $filenameWithoutExtension, $format->getExtensions()[0])
        );

        unset($contents);

        return $subtitle;
    }

    /**
     * {@inheritdoc}
     */
    public function createFromFile(
        string $file,
        ?SubtitleFormat $format = null,
        ?Language $language = null,
        ?Encoding $encoding = null,
        ?ImdbId $imdbId = null,
        ?SubtitleProvider $provider = null,
        string $filenameWithoutExtension = self::DEFAULT_FILENAME_WITHOUT_EXTENSION
    ): SubtitleInterface {
        $path = realpath($file);

        if (!$format) {
            $format = $this->subtitleFormatDetector->detectFile($path);
        }

        $contents = file_get_contents($path);

        return $this->create(
            $contents,
            $format,
            $language,
            $encoding,
            $imdbId,
            $provider,
            $filenameWithoutExtension
        );
    }

    /**
     * {@inheritdoc}
     */
    public function createFromSubtitle(
        SubtitleInterface $subtitle,
        string $filenameWithoutExtension = self::DEFAULT_FILENAME_WITHOUT_EXTENSION
    ): SubtitleInterface {
        return $this->create(
            $subtitle->getContents(),
            $subtitle->getFormat(),
            $subtitle->getLanguage(),
            $subtitle->getEncoding(),
            $subtitle->getImdbId(),
            $subtitle->getProvider(),
            $filenameWithoutExtension
        );
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
    public function findByProviderHashAndLanguage(
        string $hash,
        SubtitleProvider $provider,
        Language $language
    ): SubtitleCollectionInterface {
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
