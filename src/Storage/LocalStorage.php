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
use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;
use KickAssSubtitles\Support\HydratableInterface;
use KickAssSubtitles\Support\Str;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Config;
use function Safe\fclose;
use function Safe\file_put_contents;
use function Safe\fopen;
use function Safe\realpath;
use function Safe\sprintf;
use Throwable;

/**
 * Class LocalStorage.
 */
class LocalStorage implements StorageInterface, JsonSerializable, Arrayable, HydratableInterface
{
    /**
     * @var string
     */
    protected static $defaultRootPath;

    /**
     * @var string
     */
    protected $identifier;

    /**
     * @var string
     */
    protected $rootPath;

    /**
     * @var array
     */
    protected $files = [];

    /**
     * @var Local
     */
    protected $filesystem;

    /**
     * @var Config
     */
    protected $filesystemConfig;

    public static function setDefaultRootPath(string $path): void
    {
        static::$defaultRootPath = $path;
    }

    public static function getDefaultRootPath(): string
    {
        if (null === static::$defaultRootPath) {
            return sys_get_temp_dir();
        }

        return static::$defaultRootPath;
    }

    /**
     * {@inheritdoc}
     */
    public static function hydrateFromArray(array $array = []): object
    {
        $instance = new static(
            $array[static::IDENTIFIER],
            $array[static::ROOT_PATH]
        );

        $instance->files = $array[static::FILES];

        return $instance;
    }

    /**
     * @throws Throwable
     */
    public static function create(
        ?string $identifier = null,
        ?string $rootPath = null
    ): StorageInterface {
        if (null === $identifier) {
            $identifier = sprintf(
                '%s%s%s',
                static::TEMPLATE_PREFIX_STORAGE,
                static::TEMPLATE_SEPARATOR,
                Str::uuid()
            );
        }
        if (null === $rootPath) {
            $rootPath = static::getDefaultRootPath();
        }

        return new static($identifier, $rootPath);
    }

    protected function __construct(string $identifier, string $rootPath)
    {
        $this->identifier = $identifier;
        $this->rootPath = $rootPath;
        $this->filesystem = new Local($this->rootPath);
        $this->filesystemConfig = new Config();
    }

    /**
     * {@inheritdoc}
     */
    public function hasFile(string $key): bool
    {
        return isset($this->files[$key]);
    }

    /**
     * {@inheritdoc}
     */
    public function getFile(string $key, ?int $element = null): string
    {
        if (!$this->hasFile($key)) {
            throw new Exception(static::ERR_FILE_NOT_FOUND);
        }

        $keyPath = $this->getKeyPath($key);

        if (null === $element) {
            return $keyPath;
        }

        return pathinfo($keyPath, $element);
    }

    /**
     * {@inheritdoc}
     */
    public function addFile(string $key, string $file): string
    {
        if ($this->hasFile($key)) {
            throw new Exception(static::ERR_FILE_ALREADY_ADDED);
        }

        $file = realpath($file);

        $resource = fopen($file, 'r');

        $this->files[$key] = pathinfo($file, \PATHINFO_BASENAME);

        $this->filesystem->writeStream($this->getKeyPathRelative($key), $resource, $this->filesystemConfig);
        fclose($resource);

        return $this->getKeyPath($key);
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

        $path = implode(\DIRECTORY_SEPARATOR, [
            $this->identifier,
            $key,
        ]);

        $this->filesystem->createDir($path, $this->filesystemConfig);

        $this->files[$key] = $name;

        return $this->getKeyPath($key);
    }

    /**
     * {@inheritdoc}
     */
    public function deleteFile(string $key): void
    {
        if (!$this->hasFile($key)) {
            throw new Exception(static::ERR_FILE_NOT_FOUND);
        }

        unset($this->files[$key]);

        $this->filesystem->deleteDir(
            $this->identifier.\DIRECTORY_SEPARATOR.$key
        );
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
        $this->files = [];
        $this->filesystem->deleteDir($this->identifier);
    }

    protected function getKeyPath(string $key): string
    {
        return $this->rootPath.\DIRECTORY_SEPARATOR.$this->getKeyPathRelative($key);
    }

    protected function getKeyPathRelative(string $key): string
    {
        return implode(\DIRECTORY_SEPARATOR, [
            $this->identifier,
            $key,
            $this->files[$key],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return [
            static::IDENTIFIER => $this->identifier,
            static::ROOT_PATH => $this->rootPath,
            static::FILES => $this->files,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
