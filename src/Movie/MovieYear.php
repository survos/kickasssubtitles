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

use InvalidArgumentException;

/**
 * Class MovieYear.
 */
class MovieYear
{
    /**
     * @var int
     */
    protected $from;

    /**
     * @var int|null
     */
    protected $to;

    /**
     * @param string|int $year
     */
    public function __construct($year)
    {
        if (\is_int($year)) {
            $this->isValidYear($year);
            $this->from = $year;

            return;
        }

        if (!\is_string($year)) {
            throw new InvalidArgumentException();
        }

        $segments = [];
        $currentSegment = '';

        foreach (str_split($year) as $char) {
            if (!is_numeric($char)) {
                if (!empty($currentSegment)) {
                    $segments[] = $currentSegment;
                }
                $currentSegment = '';

                continue;
            }
            $currentSegment .= $char;
        }

        if (!empty($currentSegment)) {
            $segments[] = $currentSegment;
        }

        if (empty($segments)) {
            throw new InvalidArgumentException();
        }

        $segmentsFiltered = [];
        foreach ($segments as $v) {
            if (4 !== \strlen($v)) {
                continue;
            }
            $year = (int) $v;
            $this->isValidYear($year);
            $segmentsFiltered[] = $year;
        }

        $this->from = $segmentsFiltered[0];

        if (isset($segmentsFiltered[1])) {
            $this->to = $segmentsFiltered[1];
        }

        if ($this->from && $this->to) {
            if ($this->from > $this->to) {
                $to = $this->from;
                $from = $this->to;
                $this->from = $from;
                $this->to = $to;
            }
        }
    }

    public function getFrom(): int
    {
        return $this->from;
    }

    public function getTo(): ?int
    {
        return $this->to;
    }

    protected function isValidYear(int $year): void
    {
        $min = 1800;
        $max = (int) date('Y') + 50;
        if ($year < $min || $year > $max) {
            throw new InvalidArgumentException();
        }
    }

    public function __toString(): string
    {
        $str = (string) $this->from;
        if ($this->getTo()) {
            $str .= '-'.$this->getTo();
        }

        return $str;
    }
}
