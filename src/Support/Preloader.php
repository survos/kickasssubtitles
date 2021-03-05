<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace KickAssSubtitles\Support;

use function Safe\array_flip;
use function Safe\opendir;
use Throwable;

/**
 * Class Preloader.
 *
 * @see https://stitcher.io/blog/preloading-in-php-74
 */
class Preloader
{
    private static int $count = 0;

    private array $ignores = [];

    private array $paths;

    private array $fileMap;

    /**
     * @throws Throwable
     */
    public function __construct(string $classMapFile, array $paths = [])
    {
        $this->paths = $paths;

        // We'll use composer's classmap
        // to easily find which classes to autoload,
        // based on their filename
        $classMap = require $classMapFile;

        $this->fileMap = array_flip($classMap);
    }

    /**
     * @return $this
     */
    public function paths(array $paths): self
    {
        $this->paths = array_merge(
            $this->paths,
            $paths
        );

        return $this;
    }

    /**
     * @return $this
     */
    public function ignore(array $names): self
    {
        $this->ignores = array_merge(
            $this->ignores,
            $names
        );

        return $this;
    }

    /**
     * @throws Throwable
     */
    public function load(): void
    {
        // We'll loop over all registered paths
        // and load them one by one
        foreach ($this->paths as $path) {
            $this->loadPath(rtrim($path, '/'));
        }

        $count = self::$count;

        echo "[Preloader] Preloaded {$count} classes".\PHP_EOL;
    }

    /**
     * @throws Throwable
     */
    private function loadPath(string $path): void
    {
        // If the current path is a directory,
        // we'll load all files in it
        if (is_dir($path)) {
            $this->loadDir($path);

            return;
        }

        // Otherwise we'll just load this one file
        $this->loadFile($path);
    }

    /**
     * @throws Throwable
     */
    private function loadDir(string $path): void
    {
        $handle = opendir($path);

        // We'll loop over all files and directories
        // in the current path,
        // and load them one by one
        while ($file = readdir($handle)) {
            if (\in_array($file, ['.', '..'], true)) {
                continue;
            }

            $this->loadPath("{$path}/{$file}");
        }

        closedir($handle);
    }

    private function loadFile(string $path): void
    {
        // We resolve the classname from composer's autoload mapping
        $class = $this->fileMap[$path] ?? null;

        // And use it to make sure the class shouldn't be ignored
        if ($this->shouldIgnore($class)) {
            return;
        }

        // Finally we require the path,
        // causing all its dependencies to be loaded as well
        require_once $path;

        ++self::$count;

        echo "[Preloader] Preloaded `{$class}`".\PHP_EOL;
    }

    private function shouldIgnore(?string $name): bool
    {
        if (null === $name) {
            return true;
        }

        foreach ($this->ignores as $ignore) {
            if (0 === strpos($name, $ignore)) {
                return true;
            }
        }

        return false;
    }
}
