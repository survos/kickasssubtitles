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

use Exception;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemAdapter;
use Intervention\Image\ImageManager;
use KickAssSubtitles\Support\Exception\NotImplementedException;
use Throwable;

/**
 * Class ImageRepository.
 */
class ImageRepository implements ImageRepositoryInterface
{
    const ERR_INVALID_IMAGE = 'Invalid image';

    /**
     * @var string
     */
    protected $imageClass;

    /**
     * @var ImageManager
     */
    protected $imageManager;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    public function __construct(
        string $imageClass,
        ImageManager $imageManager,
        Filesystem $filesystem
    ) {
        $this->imageClass = $imageClass;
        $this->imageManager = $imageManager;
        $this->filesystem = $filesystem;
    }

    /**
     * {@inheritdoc}
     */
    public function createFromUrlOrPath(
        string $urlOrPath,
        ImdbId $imdbId,
        ImageType $type,
        ImageProvider $provider
    ): ImageInterface {
        $tmpDirectory = uniqid();
        /** @var FilesystemAdapter $filesystem */
        $filesystem = $this->filesystem;
        $filesystem->makeDirectory($tmpDirectory);

        try {
            $tmpPath = $filesystem->path($tmpDirectory.\DIRECTORY_SEPARATOR.$imdbId.'.'.$type.'.jpg');

            $img = $this->imageManager
                ->make($urlOrPath)
                ->resize($type->getMaxWidth(), $type->getMaxHeight(), function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })
                ->sharpen(5)
                ->save($tmpPath)
            ;

            if (!$type->matchesConstraints($tmpPath)) {
                throw new Exception(static::ERR_INVALID_IMAGE);
            }

            $imageClass = $this->imageClass;
            $image = new $imageClass();
            $image->setAttribute(ImageInterface::IMDB_ID, $imdbId->getValue());
            $image->setAttribute(ImageInterface::TYPE, $type);
            $image->setAttribute(ImageInterface::WIDTH, $img->getWidth());
            $image->setAttribute(ImageInterface::HEIGHT, $img->getHeight());
            $image->setAttribute(ImageInterface::PROVIDER, $provider->getValue());
            $image->save();

            $image->getStorage()->addFile(ImageInterface::STORAGE_IMAGE, $tmpPath);
            $filesystem->deleteDirectory($tmpDirectory);

            return $image;
        } catch (Throwable $e) {
            $filesystem->deleteDirectory($tmpDirectory);

            throw $e;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function createFromImage(ImageInterface $image): ImageInterface
    {
        return $this->createFromUrlOrPath(
            $image->getFile(),
            $image->getImdbId(),
            $image->getType(),
            $image->getProvider()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function findByImdbIdOrFail(ImdbId $imdbId): ImageInterface
    {
        throw new NotImplementedException();
    }
}
