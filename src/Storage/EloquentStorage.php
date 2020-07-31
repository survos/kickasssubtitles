<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace KickAssSubtitles\Storage;

use Exception;
use Illuminate\Database\Eloquent\Model;
use KickAssSubtitles\Support\Str;
use function Safe\file_put_contents;
use function Safe\touch;
use function Safe\unlink;
use Spatie\MediaLibrary\HasMedia\HasMedia;

/**
 * Class EloquentStorage.
 */
class EloquentStorage implements StorageInterface
{
    const ERR_MODEL_NOT_SAVED = 'Model not saved';

    const COLLECTION = 'storage';

    const CUSTOM_PROPERTY_KEY = 'key';

    const MEDIA = 'media';

    /**
     * @var HasMedia&Model
     */
    protected $model;

    /**
     * @var string
     */
    protected $diskName;

    /**
     * @param HasMedia&Model $model
     *
     * @throws Exception
     */
    public static function create(HasMedia $model, string $diskName = ''): StorageInterface
    {
        return new static($model, $diskName);
    }

    /**
     * @param HasMedia&Model $model
     *
     * @throws Exception
     */
    protected function __construct(HasMedia $model, string $diskName = '')
    {
        $this->model = $model;
        $this->diskName = $diskName;
        $this->checkIfModelSaved();
    }

    /**
     * @return HasMedia&Model
     */
    public function getModel(): HasMedia
    {
        return $this->model;
    }

    /**
     * @throws Exception
     */
    protected function checkIfModelSaved(): void
    {
        if (null === $this->model->getKey()) {
            throw new Exception(static::ERR_MODEL_NOT_SAVED);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function hasFile(string $key): bool
    {
        $this->model->load(static::MEDIA);

        $media = $this->model->getMedia(static::COLLECTION, [
            static::CUSTOM_PROPERTY_KEY => $key,
        ]);

        return (bool) \count($media);
    }

    /**
     * {@inheritdoc}
     */
    public function getFile(string $key, ?int $element = null): string
    {
        if (!$this->hasFile($key)) {
            throw new Exception(static::ERR_FILE_NOT_FOUND);
        }

        $media = $this->model->getMedia(static::COLLECTION, [
            static::CUSTOM_PROPERTY_KEY => $key,
        ]);
        $path = $media->first()->getPath();

        if (null === $element) {
            return $path;
        }

        return pathinfo($path, $element);
    }

    /**
     * {@inheritdoc}
     */
    public function addFile(string $key, string $file): string
    {
        if ($this->hasFile($key)) {
            throw new Exception(static::ERR_FILE_ALREADY_ADDED);
        }

        $media = $this->model
            ->addMedia($file)
            ->withCustomProperties([static::CUSTOM_PROPERTY_KEY => $key])
            ->preservingOriginal()
            ->toMediaCollection(static::COLLECTION, $this->diskName)
        ;

        return $media->getPath();
    }

    /**
     * {@inheritdoc}
     */
    public function addFileContents(
        string $key,
        string $contents,
        ?string $name = null
    ): string {
        if (null === $name) {
            $name = Str::uuid();
        }

        $tmpKey = Str::uuid();
        $tmpFile = $this->tmpFile($name, $tmpKey);

        file_put_contents($tmpFile, $contents);

        $path = $this->addFile($key, $tmpFile);
        $this->deleteFile($tmpKey);

        return $path;
    }

    /**
     * {@inheritdoc}
     */
    public function tmpFile(string $name, ?string $key = null): string
    {
        if (null === $key) {
            $key = Str::uuid();
        }

        if ($this->hasFile($key)) {
            throw new Exception(static::ERR_FILE_ALREADY_ADDED);
        }

        $dummyFile = sys_get_temp_dir().\DIRECTORY_SEPARATOR.$key;
        touch($dummyFile);

        $media = $this->model
            ->addMedia($dummyFile)
            ->withCustomProperties([static::CUSTOM_PROPERTY_KEY => $key])
            ->usingFileName($name)
            ->toMediaCollection(static::COLLECTION, $this->diskName)
        ;

        $path = $media->getPath();
        unlink($path);

        return $path;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteFile(string $key): void
    {
        if (!$this->hasFile($key)) {
            throw new Exception(static::ERR_FILE_NOT_FOUND);
        }

        $media = $this->model->getMedia(static::COLLECTION, [
            static::CUSTOM_PROPERTY_KEY => $key,
        ]);

        foreach ($media as $item) {
            $item->delete();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function copyFile(string $key): void
    {
        $this->addFile(
            $key.static::TEMPLATE_SEPARATOR.static::TEMPLATE_SUFFIX_COPY,
            $this->getFile($key)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function restoreFile(string $key): void
    {
        if (!$this->hasFile($key.static::TEMPLATE_SEPARATOR.static::TEMPLATE_SUFFIX_COPY)) {
            return;
        }

        $this->deleteFile($key);

        $this->addFile(
            $key,
            $this->getFile($key.static::TEMPLATE_SEPARATOR.static::TEMPLATE_SUFFIX_COPY)
        );

        $this->deleteFile($key.static::TEMPLATE_SEPARATOR.static::TEMPLATE_SUFFIX_COPY);
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown(): void
    {
        $this->model->load(static::MEDIA);
        $this->model->clearMediaCollection(static::COLLECTION);
    }
}
