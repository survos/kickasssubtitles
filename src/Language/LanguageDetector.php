<?php

declare(strict_types=1);

/*
 * KickAssSubtitles source code file
 *
 * @link      https://kickasssubtitles.com
 * @copyright Copyright (c) 2016-2020
 * @author    grzesw <contact@kickasssubtitles.com>
 */

namespace KickAssSubtitles\Language;

use LanguageDetection\Language as Detector;
use function Safe\file_get_contents;
use function Safe\realpath;

/**
 * Class LanguageDetector.
 */
class LanguageDetector implements LanguageDetectorInterface
{
    /**
     * @var Detector
     */
    protected $detector;

    /**
     * @return LanguageDetectorInterface
     */
    public static function create(): LanguageDetectorInterface
    {
        $languages = [];
        foreach (Language::values() as $language) {
            $languages[] = $language->getValue();
        }

        $detector = new Detector($languages);

        return new static($detector);
    }

    /**
     * @param Detector $detector
     */
    public function __construct(Detector $detector)
    {
        $this->detector = $detector;
    }

    /**
     * {@inheritdoc}
     */
    public function detect(string $input): Language
    {
        $result = $this->detector
            ->detect($input)
            ->bestResults()
            ->close()
        ;

        return new Language(\array_key_first($result));
    }

    /**
     * {@inheritdoc}
     */
    public function detectFile(string $file): Language
    {
        $input = file_get_contents(realpath($file));

        return $this->detect($input);
    }
}
